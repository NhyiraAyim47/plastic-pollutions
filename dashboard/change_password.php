<?php
// dashboard/change_password.php

require_once __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: ' . SITE_URL . '/auth/login.php'); exit; }

$userId = (int)$_SESSION['user_id'];
$db     = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password']     ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $user = $db->prepare("SELECT password_hash FROM users WHERE id=?"); $user->execute([$userId]); $user=$user->fetch();

    if (!password_verify($current, $user['password_hash'])) {
        echo json_encode(['success'=>false,'message'=>'Current password is incorrect.']); exit;
    }
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $new)) {
        echo json_encode(['success'=>false,'message'=>'New password must be 8+ chars, 1 uppercase, 1 number.']); exit;
    }
    if ($new !== $confirm) {
        echo json_encode(['success'=>false,'message'=>'Passwords do not match.']); exit;
    }
    $db->prepare("UPDATE users SET password_hash=? WHERE id=?")->execute([password_hash($new, PASSWORD_BCRYPT, ['cost'=>12]), $userId]);
    $db->prepare("INSERT INTO activity_log (user_id, action) VALUES (?, 'Changed password')")->execute([$userId]);
    echo json_encode(['success'=>true,'message'=>'Password updated successfully!']);
    exit;
}

$pageTitle = 'Change Password';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="auth-section">
<div class="container">
<div class="row justify-content-center"><div class="col-xl-5 col-lg-6">
  <div class="auth-card-3d">
    <div class="auth-card-header"><h2>🔒 Change Password</h2><p>Keep your account secure</p></div>
    <div class="auth-card-body">
      <form id="changePwdForm" novalidate data-site-url="<?= SITE_URL ?>">
        <div class="mb-3">
          <label class="form-label fw-semibold">Current Password</label>
          <div class="input-group">
            <input type="password" class="form-control form-control-3d" name="current_password" id="curPwd" required>
            <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="curPwd"><i class="bi bi-eye"></i></button>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">New Password</label>
          <div class="input-group">
            <input type="password" class="form-control form-control-3d" name="new_password" id="newPwd"
                   required pattern="^(?=.*[A-Z])(?=.*\d).{8,}$">
            <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="newPwd"><i class="bi bi-eye"></i></button>
          </div>
          <div class="password-strength-wrap mt-2"><div class="password-strength-bar" id="newPwdBar"></div></div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Confirm New Password</label>
          <div class="input-group">
            <input type="password" class="form-control form-control-3d" name="confirm_password" id="confPwd" required>
            <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="confPwd"><i class="bi bi-eye"></i></button>
          </div>
        </div>
        <div id="pwdAlert" class="mt-3"></div>
        <div class="d-flex gap-3 mt-4">
          <button type="submit" class="btn btn-green-3d flex-fill">
            <span class="btn-text"><i class="bi bi-lock me-2"></i>Update Password</span>
            <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm me-2"></span>Updating...</span>
          </button>
          <a href="<?= SITE_URL ?>/dashboard/index.php" class="btn btn-outline-secondary flex-fill">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div></div>
</div>
</section>
<script src="<?= SITE_URL ?>/js/form_validation.js"></script>
<script src="<?= SITE_URL ?>/js/change_password.js" defer></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
