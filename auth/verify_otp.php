<?php
// auth/verify_otp.php  –  OTP email verification page

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/mailer.php';

$pageTitle = 'Verify Your Email – OTP';
$pageDesc  = 'Enter the 6-digit OTP sent to your email to activate your PlasticPollutions account.';

// Redirect if no pending email
if (!isset($_SESSION['pending_email'])) {
    header('Location: ' . SITE_URL . '/auth/register.php');
    exit;
}

$pendingEmail = $_SESSION['pending_email'];
$pendingName  = $_SESSION['pending_name'] ?? 'User';

// ─── POST: Verify OTP ────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $db = getDB();

    if ($_POST['action'] === 'verify') {
        $otp = trim($_POST['otp'] ?? '');

        // Validate format
        if (!preg_match('/^\d{6}$/', $otp)) {
            echo json_encode(['success' => false, 'message' => 'OTP must be exactly 6 digits.']);
            exit;
        }

        // Look up user
        $stmt = $db->prepare("
            SELECT id, first_name, last_name, pu_id, otp_code, otp_expires
            FROM users WHERE email = ? AND is_verified = 0
        ");
        $stmt->execute([$pendingEmail]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Account not found or already verified.']);
            exit;
        }

        // Check expiry
        if (strtotime($user['otp_expires']) < time()) {
            echo json_encode(['success' => false, 'message' => 'Your OTP has expired. Please request a new one.']);
            exit;
        }

        // Check OTP match
        if (!hash_equals($user['otp_code'], $otp)) {
            echo json_encode(['success' => false, 'message' => 'Incorrect OTP. Please try again.']);
            exit;
        }

        // Mark as verified
        $db->prepare("UPDATE users SET is_verified = 1, otp_code = NULL, otp_expires = NULL WHERE id = ?")
           ->execute([$user['id']]);

        // Log activity
        $db->prepare("INSERT INTO activity_log (user_id, action, ip_address) VALUES (?, 'Account verified via OTP', ?)")
           ->execute([$user['id'], $_SERVER['REMOTE_ADDR'] ?? '']);

        // Send welcome email
        sendWelcomeEmail($pendingEmail, $user['first_name'] . ' ' . $user['last_name'], $user['pu_id']);

        // Auto-login
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['first_name'];
        $_SESSION['user_role'] = 'user';
        $_SESSION['pu_id']     = $user['pu_id'];
        unset($_SESSION['pending_email'], $_SESSION['pending_name']);

        echo json_encode([
            'success'  => true,
            'message'  => 'Email verified! Welcome to PlasticPollutions, ' . $user['first_name'] . '!',
            'redirect' => SITE_URL . '/dashboard/index.php'
        ]);
        exit;
    }

    if ($_POST['action'] === 'resend') {
        // Generate new OTP
        $otp       = sprintf('%06d', random_int(100000, 999999));
        $otpExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $stmt = $db->prepare("UPDATE users SET otp_code = ?, otp_expires = ? WHERE email = ? AND is_verified = 0");
        $stmt->execute([$otp, $otpExpiry, $pendingEmail]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Account not found. Please register again.']);
            exit;
        }

        $result = sendOTPEmail($pendingEmail, $pendingName, $otp);
        echo json_encode($result);
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="auth-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-5 col-lg-6 col-md-8">

        <div class="auth-card-3d otp-card">
          <div class="auth-card-header">
            <div class="otp-email-icon">📧</div>
            <h2>Check Your Email</h2>
            <p>We sent a 6-digit code to<br><strong class="text-success"><?= htmlspecialchars($pendingEmail) ?></strong></p>
          </div>

          <div class="auth-card-body">
            <!-- OTP Input Group -->
            <div class="otp-input-group" id="otpInputGroup">
              <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric" autofocus>
              <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric">
              <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric">
              <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric">
              <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric">
              <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric">
            </div>
            <input type="hidden" id="otpHidden" value="">

            <!-- Timer -->
            <div class="otp-timer text-center mt-3">
              <i class="bi bi-clock me-1"></i>
              Code expires in: <strong id="otpTimer" class="text-success">10:00</strong>
            </div>

            <!-- Alert -->
            <div id="otpAlert" class="mt-3"></div>

            <!-- Verify Button -->
            <div class="d-grid mt-4">
              <button id="verifyBtn" class="btn btn-green-3d btn-lg" disabled>
                <span class="btn-text"><i class="bi bi-shield-check me-2"></i>Verify Email</span>
                <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm me-2"></span>Verifying...</span>
              </button>
            </div>

            <!-- Resend -->
            <p class="text-center mt-3 mb-0 small">
              Didn't receive a code?
              <button id="resendBtn" class="btn btn-link btn-sm p-0 text-success fw-semibold" disabled>
                Resend OTP <span id="resendCountdown"></span>
              </button>
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<script src="<?= SITE_URL ?>/js/otp.js"></script>
<script>
// Initialize OTP page
initOTPPage({
  verifyUrl:  '<?= SITE_URL ?>/auth/verify_otp.php',
  expiryMins: 10
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
