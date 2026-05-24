<?php
// admin/index.php  –  Admin Dashboard (CRUD: Create, Read, Update, Delete)

require_once __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . SITE_URL . '/auth/login.php'); exit;
}

$db = getDB();

// Handle AJAX actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';

    if ($action === 'delete_user') {
        $uid = (int)($_POST['user_id'] ?? 0);
        if ($uid === (int)$_SESSION['user_id']) { echo json_encode(['success'=>false,'message'=>'Cannot delete your own account.']); exit; }
        $db->prepare("DELETE FROM users WHERE id=?")->execute([$uid]);
        echo json_encode(['success'=>true,'message'=>'User deleted.']);
        exit;
    }

    if ($action === 'toggle_role') {
        $uid = (int)($_POST['user_id'] ?? 0);
        $stmt = $db->prepare("SELECT role FROM users WHERE id=?"); $stmt->execute([$uid]); $row=$stmt->fetch();
        $newRole = ($row['role']==='admin') ? 'user' : 'admin';
        $db->prepare("UPDATE users SET role=? WHERE id=?")->execute([$newRole,$uid]);
        echo json_encode(['success'=>true,'role'=>$newRole]);
        exit;
    }

    if ($action === 'mark_contact_read') {
        $cid = (int)($_POST['contact_id'] ?? 0);
        $db->prepare("UPDATE contact_messages SET is_read=1 WHERE id=?")->execute([$cid]);
        echo json_encode(['success'=>true]);
        exit;
    }
    exit;
}

// Search users
$search = trim(filter_input(INPUT_GET,'search',FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$page   = max(1,(int)($_GET['page']??1));
$perPage= 15; $offset = ($page-1)*$perPage;
$where  = $search ? "WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR pu_id LIKE ?" : "";
$params = $search ? ["%$search%","%$search%","%$search%","%$search%"] : [];

$totalUsers = $db->prepare("SELECT COUNT(*) FROM users $where"); $totalUsers->execute($params); $totalUsers=(int)$totalUsers->fetchColumn();
$totalPages = max(1,ceil($totalUsers/$perPage));
$users = $db->prepare("SELECT * FROM users $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
$users->execute($params); $users=$users->fetchAll();

// Stats
$stats = [
    'users'    => $db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'verified' => $db->query("SELECT COUNT(*) FROM users WHERE is_verified=1")->fetchColumn(),
    'donations'=> $db->query("SELECT COALESCE(SUM(amount),0) FROM donations")->fetchColumn(),
    'messages' => $db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetchColumn(),
];

// Recent donations
$recentDonations = $db->query("SELECT d.*,u.first_name,u.last_name,u.pu_id FROM donations d LEFT JOIN users u ON d.user_id=u.id ORDER BY d.donated_at DESC LIMIT 8")->fetchAll();

// Unread messages
$messages = $db->query("SELECT * FROM contact_messages WHERE is_read=0 ORDER BY sent_at DESC LIMIT 5")->fetchAll();

$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Admin Header -->
<div class="dashboard-header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h1 class="text-white mb-1">🛡️ Admin Dashboard</h1>
        <p class="text-white-50 mb-0">PlasticPollutions Control Panel</p>
      </div>
      <div class="d-flex gap-2">
        <a href="<?= SITE_URL ?>/admin/manage_users.php" class="btn btn-outline-light btn-sm">Manage Users</a>
        <a href="<?= SITE_URL ?>/admin/manage_donations.php" class="btn btn-outline-light btn-sm">Manage Donations</a>
        <a href="<?= SITE_URL ?>/pages/home.php" class="btn btn-outline-light btn-sm">View Site</a>
      </div>
    </div>
  </div>
</div>

<div class="container" style="margin-top:-40px;padding-bottom:60px;">
  <!-- Stats -->
  <div class="row g-4 mb-4">
    <?php foreach ([
      ['👥','Total Users',number_format($stats['users']),'primary'],
      ['✅','Verified Users',number_format($stats['verified']),'success'],
      ['💰','Total Donations','GHS '.number_format($stats['donations'],2),'warning'],
      ['📬','Unread Messages',number_format($stats['messages']),'danger'],
    ] as $s): ?>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card text-center">
        <div style="font-size:2rem;margin-bottom:8px;"><?= $s[0] ?></div>
        <div class="h3 fw-bold text-<?= $s[3] ?> mb-1"><?= $s[2] ?></div>
        <div class="text-muted small"><?= $s[1] ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="row g-4">
    <!-- User Management (CRUD Table) -->
    <div class="col-12">
      <div class="dashboard-card">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
          <h5 class="fw-bold mb-0"><i class="bi bi-people me-2 text-success"></i>User Management</h5>
          <form method="GET" class="d-flex gap-2">
            <input type="text" class="form-control form-control-sm form-control-3d" name="search"
                   value="<?= htmlspecialchars($search) ?>" placeholder="Search users...">
            <button type="submit" class="btn btn-green-3d btn-sm"><i class="bi bi-search"></i></button>
            <?php if ($search): ?>
              <a href="?" class="btn btn-outline-secondary btn-sm">Clear</a>
            <?php endif; ?>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-success">
              <tr>
                <th>PU ID</th><th>Name</th><th>Email</th>
                <th>Role</th><th>Verified</th><th>Joined</th><th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $u): ?>
              <tr id="userRow<?= $u['id'] ?>">
                <td><code><?= $u['pu_id'] ?></code></td>
                <td><?= htmlspecialchars($u['first_name'].' '.$u['last_name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                  <span class="badge bg-<?= $u['role']==='admin'?'danger':'secondary' ?>" id="roleBadge<?= $u['id'] ?>">
                    <?= ucfirst($u['role']) ?>
                  </span>
                </td>
                <td><?= $u['is_verified']?'<span class="badge bg-success">Yes</span>':'<span class="badge bg-warning text-dark">No</span>' ?></td>
                <td><?= date('d M Y',strtotime($u['created_at'])) ?></td>
                <td>
                  <div class="d-flex gap-1 flex-wrap">
                    <?php if ($u['id'] !== (int)$_SESSION['user_id']): ?>
                    <button class="btn btn-outline-primary btn-xs"
                            onclick="toggleRole(<?= $u['id'] ?>)" title="Toggle admin role">
                      <i class="bi bi-arrow-repeat"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-xs"
                            onclick="deleteUser(<?= $u['id'] ?>, '<?= htmlspecialchars($u['first_name']) ?>')"
                            title="Delete user">
                      <i class="bi bi-trash"></i>
                    </button>
                    <?php else: ?>
                    <span class="badge bg-success">You</span>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <?php if ($totalPages>1): ?>
        <nav class="mt-3"><ul class="pagination pagination-sm justify-content-center">
          <?php for($p=1;$p<=$totalPages;$p++): ?>
            <li class="page-item <?= $p===$page?'active':'' ?>">
              <a class="page-link" href="?page=<?=$p?>&search=<?=urlencode($search)?>"><?=$p?></a>
            </li>
          <?php endfor; ?>
        </ul></nav>
        <?php endif; ?>
      </div>
    </div>

    <!-- Recent Donations -->
    <div class="col-lg-7">
      <div class="dashboard-card h-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="fw-bold mb-0"><i class="bi bi-cash-stack me-2 text-success"></i>Recent Donations</h5>
          <a href="<?= SITE_URL ?>/admin/manage_donations.php" class="btn btn-sm btn-outline-success">View All</a>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-sm align-middle">
            <thead class="table-light">
              <tr><th>User</th><th>Amount</th><th>Campaign</th><th>Date</th></tr>
            </thead>
            <tbody>
              <?php foreach ($recentDonations as $d): ?>
              <tr>
                <td><?= htmlspecialchars($d['first_name']??'Guest') ?> <small class="text-muted">(<?= $d['pu_id']??'—' ?>)</small></td>
                <td class="fw-bold text-success">GHS <?= number_format($d['amount'],2) ?></td>
                <td><?= htmlspecialchars($d['campaign']) ?></td>
                <td><?= date('d M',strtotime($d['donated_at'])) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Unread Messages -->
    <div class="col-lg-5">
      <div class="dashboard-card h-100">
        <h5 class="fw-bold mb-4"><i class="bi bi-envelope me-2 text-success"></i>Unread Messages</h5>
        <?php if (empty($messages)): ?>
          <div class="text-center text-muted py-3"><i class="bi bi-inbox display-4"></i><p class="mt-2">No unread messages</p></div>
        <?php else: ?>
          <?php foreach ($messages as $msg): ?>
          <div class="msg-card mb-3" id="msg<?= $msg['id'] ?>">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <strong><?= htmlspecialchars($msg['name']) ?></strong>
                <small class="text-muted d-block"><?= htmlspecialchars($msg['email']) ?></small>
                <span class="text-muted small"><?= htmlspecialchars($msg['subject']) ?></span>
              </div>
              <button class="btn btn-outline-success btn-xs" onclick="markRead(<?= $msg['id'] ?>)">
                <i class="bi bi-check"></i>
              </button>
            </div>
            <p class="small text-muted mt-1 mb-0"><?= htmlspecialchars(substr($msg['message'],0,80)) ?>...</p>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<style>
.btn-xs { padding:3px 8px; font-size:.75rem; }
.msg-card { background:#f8f9fa; border-radius:var(--radius-md); padding:14px; }
</style>

<script>
async function deleteUser(id, name) {
  if (!confirm(`Delete user "${name}"? This cannot be undone.`)) return;
  const fd = new FormData(); fd.append('action','delete_user'); fd.append('user_id',id);
  const res = await fetch('<?= SITE_URL ?>/admin/index.php',{method:'POST',body:fd});
  const json= await res.json();
  if (json.success) {
    document.getElementById('userRow'+id)?.remove();
    showToast('User deleted.','success');
  } else showToast(json.message,'error');
}

async function toggleRole(id) {
  const fd = new FormData(); fd.append('action','toggle_role'); fd.append('user_id',id);
  const res = await fetch('<?= SITE_URL ?>/admin/index.php',{method:'POST',body:fd});
  const json= await res.json();
  if (json.success) {
    const badge = document.getElementById('roleBadge'+id);
    if (badge) { badge.textContent=json.role.charAt(0).toUpperCase()+json.role.slice(1); badge.className='badge bg-'+(json.role==='admin'?'danger':'secondary'); }
    showToast('Role updated to '+json.role,'success');
  }
}

async function markRead(id) {
  const fd = new FormData(); fd.append('action','mark_contact_read'); fd.append('contact_id',id);
  await fetch('<?= SITE_URL ?>/admin/index.php',{method:'POST',body:fd});
  document.getElementById('msg'+id)?.remove();
  showToast('Message marked as read','success');
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
