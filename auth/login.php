<?php
// auth/login.php  –  Login with lockout (3 attempts → 3 min lockout)

require_once __DIR__ . '/../config/db.php';

$pageTitle    = 'Login to Your Account';
$pageDesc     = 'Sign in to your PlasticPollutions account to access your dashboard, manage donations, and join the fight against plastic waste.';
$pageKeywords = 'login, sign in, PlasticPollutions account, Pentecost University';

// Already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/dashboard/index.php');
    exit;
}

// ─── POST: Process login ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $email    = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid email and password.']);
        exit;
    }

    $db = getDB();

    // Fetch user
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
        exit;
    }

    // Check if email is verified
    if (!$user['is_verified']) {
        $_SESSION['pending_email'] = $email;
        $_SESSION['pending_name']  = $user['first_name'];
        echo json_encode([
            'success'  => false,
            'message'  => 'Your email is not verified. We are redirecting you to verify.',
            'redirect' => SITE_URL . '/auth/verify_otp.php'
        ]);
        exit;
    }

    // Check lockout
    if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
        $remaining = ceil((strtotime($user['locked_until']) - time()) / 60);
        echo json_encode([
            'success' => false,
            'message' => "Account temporarily locked due to too many failed attempts. Try again in {$remaining} minute(s).",
            'locked'  => true
        ]);
        exit;
    }

    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        $attempts = $user['login_attempts'] + 1;
        if ($attempts >= 3) {
            // Lock account for 3 minutes
            $lockedUntil = date('Y-m-d H:i:s', strtotime('+3 minutes'));
            $db->prepare("UPDATE users SET login_attempts = ?, locked_until = ? WHERE id = ?")
               ->execute([$attempts, $lockedUntil, $user['id']]);
            echo json_encode([
                'success' => false,
                'message' => 'Too many failed attempts. Your account has been locked for 3 minutes.',
                'locked'  => true
            ]);
        } else {
            $db->prepare("UPDATE users SET login_attempts = ? WHERE id = ?")
               ->execute([$attempts, $user['id']]);
            $remaining = 3 - $attempts;
            echo json_encode([
                'success' => false,
                'message' => "Invalid email or password. {$remaining} attempt(s) remaining before lockout."
            ]);
        }
        exit;
    }

    // Successful login — reset attempts
    $db->prepare("UPDATE users SET login_attempts = 0, locked_until = NULL WHERE id = ?")
       ->execute([$user['id']]);

    // Log activity
    $db->prepare("INSERT INTO activity_log (user_id, action, ip_address) VALUES (?, 'Logged in', ?)")
       ->execute([$user['id'], $_SERVER['REMOTE_ADDR'] ?? '']);

    // Set session
    session_regenerate_id(true);
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['first_name'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['pu_id']     = $user['pu_id'];

    $redirect = ($user['role'] === 'admin')
        ? SITE_URL . '/admin/index.php'
        : SITE_URL . '/dashboard/index.php';

    echo json_encode([
        'success'  => true,
        'message'  => 'Login successful! Redirecting...',
        'redirect' => $redirect
    ]);
    exit;
}

// ─── GET: Render login page ───────────────────────────────────────────────────
require_once __DIR__ . '/../includes/header.php';
?>

<section class="auth-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-5 col-lg-6 col-md-8">

        <div class="auth-card-3d">
          <div class="auth-card-header">
            <div class="auth-logo">🌿</div>
            <h2>Welcome Back</h2>
            <p>Sign in to your PlasticPollutions account</p>
          </div>

          <div class="auth-card-body">
            <!-- Lockout Banner (hidden by default) -->
            <div id="lockoutBanner" class="alert alert-danger d-none" role="alert">
              <i class="bi bi-lock-fill me-2"></i>
              <strong>Account Locked.</strong>
              <span id="lockoutMsg"></span>
              <div class="progress mt-2" style="height:4px;">
                <div id="lockoutProgress" class="progress-bar bg-danger" style="width:100%;transition:width 1s linear;"></div>
              </div>
            </div>

            <form id="loginForm" novalidate data-site-url="<?= SITE_URL ?>">
              <div class="mb-3">
                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control form-control-3d" name="email"
                       placeholder="you@example.com" required autocomplete="email">
                <div class="invalid-feedback">Enter a valid email.</div>
              </div>
              <div class="mb-3">
                <div class="d-flex justify-content-between">
                  <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                  <a href="<?= SITE_URL ?>/auth/forgot_password.php" class="small text-success">Forgot password?</a>
                </div>
                <div class="input-group">
                  <input type="password" class="form-control form-control-3d" name="password"
                         id="loginPwd" placeholder="Enter your password" required autocomplete="current-password">
                  <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="loginPwd">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
                <div class="invalid-feedback">Password is required.</div>
              </div>

              <div id="loginAlert" class="mb-3"></div>

              <div class="d-grid">
                <button type="submit" class="btn btn-green-3d btn-lg" id="loginBtn">
                  <span class="btn-text"><i class="bi bi-box-arrow-in-right me-2"></i>Login</span>
                  <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm me-2"></span>Signing in...</span>
                </button>
              </div>

              <p class="text-center mt-4 mb-0">
                Don't have an account?
                <a href="<?= SITE_URL ?>/auth/register.php" class="text-success fw-semibold">Register here</a>
              </p>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<script src="<?= SITE_URL ?>/js/login.js" defer></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
