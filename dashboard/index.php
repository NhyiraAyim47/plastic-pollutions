<?php
// dashboard/index.php  –  User dashboard

require_once __DIR__ . '/../config/db.php';

// Auth guard
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/auth/login.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];
$db     = getDB();

// Fetch user data
$user = $db->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$userId]);
$user = $user->fetch();

if (!$user) {
    session_destroy();
    header('Location: ' . SITE_URL . '/auth/login.php');
    exit;
}

// Fetch stats
$totalDonated  = $db->prepare("SELECT COALESCE(SUM(amount),0) FROM donations WHERE user_id = ?");
$totalDonated->execute([$userId]);
$totalDonated  = (float)$totalDonated->fetchColumn();

$donationCount = $db->prepare("SELECT COUNT(*) FROM donations WHERE user_id = ?");
$donationCount->execute([$userId]);
$donationCount = (int)$donationCount->fetchColumn();

$recentDonations = $db->prepare("SELECT * FROM donations WHERE user_id = ? ORDER BY donated_at DESC LIMIT 5");
$recentDonations->execute([$userId]);
$recentDonations = $recentDonations->fetchAll();

$recentActivity = $db->prepare("SELECT * FROM activity_log WHERE user_id = ? ORDER BY created_at DESC LIMIT 8");
$recentActivity->execute([$userId]);
$recentActivity = $recentActivity->fetchAll();

$hasSigned = $db->prepare("SELECT id FROM petitions WHERE user_id = ?");
$hasSigned->execute([$userId]);
$hasSigned = (bool)$hasSigned->fetch();

$pageTitle    = 'My Dashboard';
$pageDesc     = 'Manage your PlasticPollutions account, view donations, and track your environmental impact.';
$extraCss = [SITE_URL . '/css/dashboard.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Dashboard Header -->
<div class="dashboard-header">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1 class="text-white mb-1">
          Welcome back, <?= htmlspecialchars($user['first_name']) ?>! 👋
        </h1>
        <p class="text-white-50 mb-0">
          <i class="bi bi-shield-check me-1 text-success"></i>
          Visitor ID: <strong class="text-success"><?= htmlspecialchars($user['pu_id']) ?></strong>
          &nbsp;|&nbsp;
          Member since <?= date('M Y', strtotime($user['created_at'])) ?>
        </p>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="<?= SITE_URL ?>/donations/donate.php" class="btn btn-hero-primary">
          <i class="bi bi-heart me-2"></i>Make a Donation
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Dashboard Body pulled up to overlap header -->
<div class="container" style="margin-top:-40px; padding-bottom: 60px;">

  <!-- ─── Stats Row ─────────────────────────────────── -->
  <div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card text-center">
        <div class="stat-icon-dash">💰</div>
        <div class="h3 fw-bold text-success mb-1">GHS <?= number_format($totalDonated, 2) ?></div>
        <div class="text-muted small">Total Donated</div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card text-center">
        <div class="stat-icon-dash">🎁</div>
        <div class="h3 fw-bold text-primary mb-1"><?= $donationCount ?></div>
        <div class="text-muted small">Donations Made</div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card text-center">
        <div class="stat-icon-dash">✍️</div>
        <div class="h3 fw-bold <?= $hasSigned ? 'text-success' : 'text-muted' ?> mb-1">
          <?= $hasSigned ? 'Signed ✅' : 'Not yet' ?>
        </div>
        <div class="text-muted small">Petition Status</div>
        <?php if (!$hasSigned): ?>
          <a href="<?= SITE_URL ?>/pages/what_to_do.php#petition" class="btn btn-sm btn-outline-success mt-2">Sign Now</a>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card text-center">
        <div class="stat-icon-dash">🌍</div>
        <div class="h3 fw-bold text-info mb-1"><?= number_format($totalDonated / 10) ?>+</div>
        <div class="text-muted small">Students Educated (est.)</div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- ─── Profile Card ─────────────────────────────── -->
    <div class="col-lg-4">
      <div class="dashboard-card h-100">
        <h5 class="fw-bold mb-4"><i class="bi bi-person-circle me-2 text-success"></i>My Profile</h5>
        <div class="text-center mb-4">
          <div class="avatar-circle mx-auto mb-3">
            <?= strtoupper(substr($user['first_name'],0,1) . substr($user['last_name'],0,1)) ?>
          </div>
          <h5 class="mb-0 fw-bold"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h5>
          <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small><br>
          <span class="badge bg-success mt-1"><?= ucfirst($user['role']) ?></span>
        </div>
        <ul class="list-unstyled profile-list">
          <li><i class="bi bi-card-text me-2 text-success"></i><strong>PU ID:</strong> <?= $user['pu_id'] ?></li>
          <li><i class="bi bi-calendar me-2 text-success"></i><strong>Joined:</strong> <?= date('d M Y', strtotime($user['created_at'])) ?></li>
          <li><i class="bi bi-shield-check me-2 text-success"></i><strong>Status:</strong> Verified</li>
        </ul>
        <div class="d-grid gap-2 mt-4">
          <a href="<?= SITE_URL ?>/dashboard/update_profile.php" class="btn btn-green-3d">
            <i class="bi bi-pencil me-2"></i>Edit Profile
          </a>
          <a href="<?= SITE_URL ?>/dashboard/change_password.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-lock me-2"></i>Change Password
          </a>
        </div>
      </div>
    </div>

    <!-- ─── Recent Donations ─────────────────────────── -->
    <div class="col-lg-8">
      <div class="dashboard-card h-100">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-success"></i>Recent Donations</h5>
          <a href="<?= SITE_URL ?>/donations/history.php" class="btn btn-sm btn-outline-success">View All</a>
        </div>
        <?php if (empty($recentDonations)): ?>
          <div class="empty-state text-center py-4">
            <i class="bi bi-heart display-4 text-muted"></i>
            <p class="text-muted mt-3">No donations yet. Be the first to contribute!</p>
            <a href="<?= SITE_URL ?>/donations/donate.php" class="btn btn-green-3d">Donate Now</a>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-light">
                <tr>
                  <th>Reference</th>
                  <th>Campaign</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recentDonations as $d): ?>
                <tr>
                  <td><code><?= htmlspecialchars($d['reference']) ?></code></td>
                  <td><?= htmlspecialchars($d['campaign']) ?></td>
                  <td class="fw-bold text-success">GHS <?= number_format($d['amount'], 2) ?></td>
                  <td><?= date('d M Y', strtotime($d['donated_at'])) ?></td>
                  <td>
                    <span class="badge bg-<?= $d['status']==='completed' ? 'success' : ($d['status']==='pending' ? 'warning' : 'danger') ?>">
                      <?= ucfirst($d['status']) ?>
                    </span>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ─── Activity Log ─────────────────────────────── -->
    <div class="col-12">
      <div class="dashboard-card">
        <h5 class="fw-bold mb-4"><i class="bi bi-activity me-2 text-success"></i>Recent Activity</h5>
        <?php if (empty($recentActivity)): ?>
          <p class="text-muted">No activity recorded yet.</p>
        <?php else: ?>
          <div class="activity-timeline">
            <?php foreach ($recentActivity as $log): ?>
            <div class="activity-item">
              <div class="activity-dot"></div>
              <div class="activity-content">
                <strong><?= htmlspecialchars($log['action']) ?></strong>
                <span class="text-muted small ms-2">
                  <i class="bi bi-clock me-1"></i><?= date('d M Y H:i', strtotime($log['created_at'])) ?>
                </span>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ─── Delete Account ───────────────────────────── -->
    <div class="col-12">
      <div class="dashboard-card border-danger">
        <h5 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h5>
        <p class="text-muted small mb-3">Deleting your account is permanent and cannot be undone. All your data will be removed.</p>
        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
          <i class="bi bi-trash me-2"></i>Delete My Account
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirm Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">⚠️ Delete Account</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>This action is <strong>permanent</strong>. All your data — profile, donations, and activity — will be permanently deleted.</p>
        <p>Type <strong>DELETE</strong> to confirm:</p>
        <input type="text" id="deleteConfirmInput" class="form-control" placeholder="Type DELETE here">
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="<?= SITE_URL ?>/dashboard/delete_account.php" id="confirmDeleteBtn"
           class="btn btn-danger disabled">Yes, Delete My Account</a>
      </div>
    </div>
  </div>
</div>

<script src="<?= SITE_URL ?>/js/dashboard.js" defer></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
