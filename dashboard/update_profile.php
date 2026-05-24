<?php
// dashboard/update_profile.php  –  Edit profile (CRUD: Update)

require_once __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: ' . SITE_URL . '/auth/login.php'); exit; }

$userId = (int)$_SESSION['user_id'];
$db     = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $firstName = trim(filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $lastName  = trim(filter_input(INPUT_POST, 'last_name',  FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $email     = trim(filter_input(INPUT_POST, 'email',      FILTER_SANITIZE_EMAIL) ?? '');

    $errors = [];
    if (strlen($firstName) < 2) $errors[] = 'First name too short.';
    if (strlen($lastName)  < 2) $errors[] = 'Last name too short.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';

    // Check email uniqueness (excluding self)
    $check = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $check->execute([$email, $userId]);
    if ($check->fetch()) $errors[] = 'Email already in use by another account.';

    if (!empty($errors)) { echo json_encode(['success'=>false,'errors'=>$errors]); exit; }

    $db->prepare("UPDATE users SET first_name=?, last_name=?, email=? WHERE id=?")
       ->execute([$firstName, $lastName, $email, $userId]);
    $db->prepare("INSERT INTO activity_log (user_id, action) VALUES (?, 'Updated profile')")->execute([$userId]);
    $_SESSION['user_name'] = $firstName;

    echo json_encode(['success'=>true,'message'=>'Profile updated successfully!']);
    exit;
}

// GET: load current data
$user = $db->prepare("SELECT * FROM users WHERE id=?"); $user->execute([$userId]); $user = $user->fetch();
$pageTitle = 'Update Profile';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="auth-section">
<div class="container">
<div class="row justify-content-center"><div class="col-xl-6 col-lg-7">
  <div class="auth-card-3d">
    <div class="auth-card-header"><h2>✏️ Edit Profile</h2><p>Update your personal information</p></div>
    <div class="auth-card-body">
      <form id="updateProfileForm" novalidate data-site-url="<?= SITE_URL ?>">
        <div class="row g-3">
          <div class="col-6">
            <label class="form-label fw-semibold">First Name</label>
            <input type="text" class="form-control form-control-3d" name="first_name"
                   value="<?= htmlspecialchars($user['first_name']) ?>" required minlength="2">
          </div>
          <div class="col-6">
            <label class="form-label fw-semibold">Last Name</label>
            <input type="text" class="form-control form-control-3d" name="last_name"
                   value="<?= htmlspecialchars($user['last_name']) ?>" required minlength="2">
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Email Address</label>
            <input type="email" class="form-control form-control-3d" name="email"
                   value="<?= htmlspecialchars($user['email']) ?>" required>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold text-muted">PU Visitor ID (Read-only)</label>
            <input type="text" class="form-control form-control-3d bg-light" value="<?= $user['pu_id'] ?>" readonly>
          </div>
        </div>
        <div id="profileAlert" class="mt-3"></div>
        <div class="d-flex gap-3 mt-4">
          <button type="submit" class="btn btn-green-3d flex-fill">
            <span class="btn-text"><i class="bi bi-save me-2"></i>Save Changes</span>
            <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm me-2"></span>Saving...</span>
          </button>
          <a href="<?= SITE_URL ?>/dashboard/index.php" class="btn btn-outline-secondary flex-fill">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div></div>
</div>
</section>

<script src="<?= SITE_URL ?>/js/update_profile.js" defer></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
