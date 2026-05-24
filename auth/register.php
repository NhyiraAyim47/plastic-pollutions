<?php
// auth/register.php  –  User registration with OTP email verification

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/mailer.php';
$calledAsInclude = true;
require_once __DIR__ . '/../api/generate_pu_id.php';

$pageTitle    = 'Create Your Account';
$pageDesc     = 'Join PlasticPollutions at Pentecost University. Register to contribute to environmental action, donate, and make a difference.';
$pageKeywords = 'register, sign up, PlasticPollutions, Pentecost University, environmental group';

// Handle AJAX registration POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $firstName = trim(filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $lastName  = trim(filter_input(INPUT_POST, 'last_name',  FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $email     = trim(filter_input(INPUT_POST, 'email',      FILTER_SANITIZE_EMAIL) ?? '');
    $password  = $_POST['password'] ?? '';
    $confirmPw = $_POST['confirm_password'] ?? '';

    // Validation
    $errors = [];
    if (strlen($firstName) < 2) $errors[] = 'First name must be at least 2 characters.';
    if (strlen($lastName)  < 2) $errors[] = 'Last name must be at least 2 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $errors[] = 'Password must be at least 8 characters, include 1 uppercase letter and 1 number.';
    }
    if ($password !== $confirmPw) $errors[] = 'Passwords do not match.';

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    $db = getDB();

    // Check email uniqueness
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'errors' => ['This email is already registered. Please login.']]);
        exit;
    }

    // Generate secure OTP
    $otp       = sprintf('%06d', random_int(100000, 999999));
    $otpExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    // Hash password
    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

    // Generate unique PU ID
    $puId = generateUniquePUId($db);

    // Insert user (unverified)
    $stmt = $db->prepare("
        INSERT INTO users (pu_id, first_name, last_name, email, password_hash, otp_code, otp_expires, is_verified)
        VALUES (?, ?, ?, ?, ?, ?, ?, 0)
    ");
    $stmt->execute([$puId, $firstName, $lastName, $email, $hash, $otp, $otpExpiry]);

    // Send OTP email
    $fullName  = $firstName . ' ' . $lastName;
    $mailResult = sendOTPEmail($email, $fullName, $otp);

    if (!$mailResult['success']) {
        // Roll back the user insert so they can try again
        $db->prepare("DELETE FROM users WHERE email = ?")->execute([$email]);
        echo json_encode(['success' => false, 'errors' => ['Failed to send verification email. Please try again or contact support.']]);
        exit;
    }

    // Store email in session for OTP page
    $_SESSION['pending_email'] = $email;
    $_SESSION['pending_name']  = $firstName;

    $redirectUrl = SITE_URL . '/auth/verify_otp.php';
    echo json_encode([
    'success'  => true,
    'message'  => 'Registration successful!...',
    'redirect' => $redirectUrl,
    'debug_url' => $redirectUrl,
    'debug_site_url' => SITE_URL
    ]);
    exit;
}

// ─── GET: Render registration page ───────────────────────────────────────────
require_once __DIR__ . '/../includes/header.php';
?>
<section class="auth-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-7 col-md-9">

        <!-- Auth Card 3D -->
        <div class="auth-card-3d">
          <div class="auth-card-header">
            <div class="auth-logo">🌿</div>
            <h2>Create Your Account</h2>
            <p>Join the movement against plastic pollution</p>
          </div>

          <div class="auth-card-body">
            <form id="registerForm" novalidate data-site-url="<?= SITE_URL ?>">
              <div class="row g-3">
                <div class="col-6">
                  <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control form-control-3d" name="first_name"
                         placeholder="e.g. Kwame" required minlength="2" autocomplete="given-name">
                  <div class="invalid-feedback">Min 2 characters required.</div>
                </div>
                <div class="col-6">
                  <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control form-control-3d" name="last_name"
                         placeholder="e.g. Asante" required minlength="2" autocomplete="family-name">
                  <div class="invalid-feedback">Last name required.</div>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                  <input type="email" class="form-control form-control-3d" name="email"
                         placeholder="you@example.com" required autocomplete="email">
                  <div class="invalid-feedback">Please enter a valid email.</div>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="password" class="form-control form-control-3d" name="password"
                           id="regPassword" placeholder="Min 8 chars, 1 uppercase, 1 number"
                           required pattern="^(?=.*[A-Z])(?=.*\d).{8,}$" autocomplete="new-password">
                    <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="regPassword">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                  <!-- Strength Bar -->
                  <div class="password-strength-wrap mt-2">
                    <div class="password-strength-bar" id="regPwdBar"></div>
                  </div>
                  <small class="text-muted" id="regPwdStrengthLabel"></small>
                  <div class="invalid-feedback">8+ chars, 1 uppercase, 1 number required.</div>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="password" class="form-control form-control-3d" name="confirm_password"
                           id="regConfirmPwd" placeholder="Re-enter your password" required autocomplete="new-password">
                    <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="regConfirmPwd">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                  <div class="invalid-feedback">Passwords do not match.</div>
                </div>
              </div><!-- /row -->

              <!-- Alert box -->
              <div id="registerAlert" class="mt-3"></div>

              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-green-3d btn-lg" id="registerBtn">
                  <span class="btn-text"><i class="bi bi-person-plus me-2"></i>Register &amp; Get OTP</span>
                  <span class="btn-spinner d-none">
                    <span class="spinner-border spinner-border-sm me-2"></span>Sending OTP...
                  </span>
                </button>
              </div>

              <p class="text-center mt-4 mb-0">
                Already have an account?
                <a href="<?= SITE_URL ?>/auth/login.php" class="text-success fw-semibold">Sign in</a>
              </p>
            </form>
          </div>
        </div><!-- /auth-card-3d -->

      </div>
    </div>
  </div>
</section>

<script src="<?= SITE_URL ?>/js/register.js"></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
