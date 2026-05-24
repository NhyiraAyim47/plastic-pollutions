<?php
// donations/history.php  –  Donation history & statistics

require_once __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: ' . SITE_URL . '/auth/login.php'); exit; }

$userId = (int)$_SESSION['user_id'];
$db     = getDB();

// Search & filter
$search   = trim(filter_input(INPUT_GET, 'search',   FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$campaign = trim(filter_input(INPUT_GET, 'campaign', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 10;
$offset   = ($page - 1) * $perPage;

$where  = "WHERE user_id = ?";
$params = [$userId];
if ($search)   { $where .= " AND reference LIKE ?"; $params[] = "%$search%"; }
if ($campaign) { $where .= " AND campaign = ?";     $params[] = $campaign; }

$totalRows = $db->prepare("SELECT COUNT(*) FROM donations $where");
$totalRows->execute($params); $totalRows = (int)$totalRows->fetchColumn();
$totalPages = max(1, ceil($totalRows / $perPage));

$stmt = $db->prepare("SELECT * FROM donations $where ORDER BY donated_at DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$donations = $stmt->fetchAll();

// Stats
$stats = $db->prepare("SELECT COUNT(*) as cnt, COALESCE(SUM(amount),0) as total, MAX(amount) as max_d FROM donations WHERE user_id=?");
$stats->execute([$userId]); $stats = $stats->fetch();

// Campaign breakdown
$breakdown = $db->prepare("SELECT campaign, COUNT(*) as cnt, SUM(amount) as total FROM donations WHERE user_id=? GROUP BY campaign ORDER BY total DESC");
$breakdown->execute([$userId]); $breakdown = $breakdown->fetchAll();

$pageTitle = 'My Donation History';
$extraCss = [SITE_URL . '/css/history.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section-pad">
  <div class="container">
    <div class="row align-items-center mb-5">
      <div class="col">
        <span class="section-badge">Your Contributions</span>
        <h1 class="section-title mb-0">Donation <span class="text-success">History</span></h1>
      </div>
      <div class="col-auto">
        <a href="<?= SITE_URL ?>/donations/donate.php" class="btn btn-green-3d">
          <i class="bi bi-heart me-2"></i>Donate Again
        </a>
      </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-5">
      <div class="col-md-4">
        <div class="stat-card-donate">
          <div class="stat-label-donate">Total Donated</div>
          <div class="stat-value-donate text-success">GHS <?= number_format((float)$stats['total'], 2) ?></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card-donate">
          <div class="stat-label-donate">Total Donations</div>
          <div class="stat-value-donate text-primary"><?= (int)$stats['cnt'] ?></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card-donate">
          <div class="stat-label-donate">Largest Donation</div>
          <div class="stat-value-donate text-warning">GHS <?= number_format((float)$stats['max_d'], 2) ?></div>
        </div>
      </div>
    </div>

    <!-- Search & Filter -->
    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-5">
        <input type="text" class="form-control form-control-3d" name="search"
               value="<?= htmlspecialchars($search) ?>" placeholder="Search by reference...">
      </div>
      <div class="col-md-4">
        <select class="form-select form-control-3d" name="campaign">
          <option value="">All Campaigns</option>
          <?php foreach ($breakdown as $b): ?>
            <option value="<?= htmlspecialchars($b['campaign']) ?>"
                    <?= $campaign === $b['campaign'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($b['campaign']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-green-3d w-100">
          <i class="bi bi-search me-2"></i>Search
        </button>
      </div>
    </form>

    <!-- Table -->
    <div class="dashboard-card">
      <?php if (empty($donations)): ?>
        <div class="text-center py-5">
          <i class="bi bi-inbox display-3 text-muted"></i>
          <p class="mt-3 text-muted">No donations found<?= $search || $campaign ? ' matching your filter' : '' ?>.</p>
          <?php if (!$search && !$campaign): ?>
            <a href="<?= SITE_URL ?>/donations/donate.php" class="btn btn-green-3d">Make Your First Donation</a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-success">
              <tr>
                <th>#</th>
                <th>Reference</th>
                <th>Campaign</th>
                <th>Amount</th>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($donations as $i => $d): ?>
              <tr>
                <td><?= $offset + $i + 1 ?></td>
                <td><code class="text-success"><?= htmlspecialchars($d['reference']) ?></code></td>
                <td><?= htmlspecialchars($d['campaign']) ?></td>
                <td class="fw-bold text-success">GHS <?= number_format($d['amount'], 2) ?></td>
                <td><?= $d['message'] ? htmlspecialchars(substr($d['message'],0,40)).'…' : '<em class="text-muted">—</em>' ?></td>
                <td><?= date('d M Y H:i', strtotime($d['donated_at'])) ?></td>
                <td>
                  <span class="badge bg-<?= $d['status']==='completed'?'success':($d['status']==='pending'?'warning':'danger') ?>">
                    <?= ucfirst($d['status']) ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
          <ul class="pagination justify-content-center">
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
              <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($search) ?>&campaign=<?= urlencode($campaign) ?>"><?= $p ?></a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
        <?php endif; ?>

        <!-- Campaign Breakdown -->
        <?php if (!empty($breakdown)): ?>
        <hr class="mt-4">
        <h6 class="fw-bold mb-3">Donations by Campaign</h6>
        <div class="row g-3">
          <?php foreach ($breakdown as $b): ?>
          <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="small fw-semibold"><?= htmlspecialchars($b['campaign']) ?></span>
              <span class="small text-success fw-bold">GHS <?= number_format($b['total'],2) ?></span>
            </div>
            <div class="progress-3d">
              <div class="progress-fill" style="width:<?= min(100, ($b['total']/$stats['total'])*100) ?>%"></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
