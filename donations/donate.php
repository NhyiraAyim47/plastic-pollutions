<?php
// donations/donate.php  –  Donation form and processing

require_once __DIR__ . '/../config/db.php';

$pageTitle    = 'Donate to PlasticPollutions';
$pageDesc     = 'Support PlasticPollutions\' environmental campaigns. Every donation helps us fight plastic waste in Ghana.';
$pageKeywords = 'donate, plastic pollution fund, environmental donation, Ghana, Pentecost University';

$isLoggedIn = isset($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $db = getDB();

    $amount   = filter_input(INPUT_POST, 'amount',   FILTER_VALIDATE_FLOAT);
    $campaign = trim(filter_input(INPUT_POST, 'campaign', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'General Fund');
    $message  = trim(filter_input(INPUT_POST, 'message',  FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $name     = trim(filter_input(INPUT_POST, 'name',     FILTER_SANITIZE_SPECIAL_CHARS) ?? 'Anonymous');
    $email    = trim(filter_input(INPUT_POST, 'email',    FILTER_SANITIZE_EMAIL) ?? '');

    if (!$amount || $amount < 1)       { echo json_encode(['success'=>false,'message'=>'Minimum donation is GHS 1.']); exit; }
    if ($amount > 100000)              { echo json_encode(['success'=>false,'message'=>'Maximum donation is GHS 100,000.']); exit; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !$isLoggedIn) { echo json_encode(['success'=>false,'message'=>'Please enter a valid email.']); exit; }

    $userId    = $isLoggedIn ? (int)$_SESSION['user_id'] : null;
    $reference = 'PP' . strtoupper(bin2hex(random_bytes(6)));

    $stmt = $db->prepare("INSERT INTO donations (user_id, amount, campaign, message, reference) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $amount, $campaign, $message, $reference]);

    if ($userId) {
        $db->prepare("INSERT INTO activity_log (user_id, action) VALUES (?, ?)")
           ->execute([$userId, "Donated GHS " . number_format($amount, 2) . " to {$campaign}"]);
    }

    echo json_encode([
        'success'   => true,
        'message'   => "Thank you for your generous donation of GHS " . number_format($amount, 2) . "! Your reference is {$reference}.",
        'reference' => $reference,
        'amount'    => $amount
    ]);
    exit;
}

// Fetch donation stats
$db = getDB();
$totalRaised = $db->query("SELECT COALESCE(SUM(amount),0) FROM donations")->fetchColumn();
$donorCount  = $db->query("SELECT COUNT(DISTINCT COALESCE(user_id, reference)) FROM donations")->fetchColumn();

$extraCss = [SITE_URL . '/css/donate.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section-pad bg-light-green">
  <div class="container">
    <!-- Page Header -->
    <div class="text-center mb-5">
      <span class="section-badge">Make an Impact</span>
      <h1 class="section-title">Donate to <span class="text-success">PlasticPollutions</span></h1>
      <p class="text-muted mx-auto" style="max-width:580px;">
        Your contribution directly funds our cleanup drives, school workshops, and policy advocacy campaigns.
      </p>
      <!-- Stats -->
      <div class="d-flex justify-content-center gap-4 mt-4">
        <div class="text-center">
          <div class="h4 fw-bold text-success mb-0">GHS <?= number_format((float)$totalRaised, 2) ?></div>
          <small class="text-muted">Total Raised</small>
        </div>
        <div class="text-center">
          <div class="h4 fw-bold text-success mb-0"><?= number_format((int)$donorCount) ?>+</div>
          <small class="text-muted">Generous Donors</small>
        </div>
      </div>
    </div>

    <div class="row g-5 justify-content-center">
      <!-- Donation Form -->
      <div class="col-lg-7">
        <div class="auth-card-3d">
          <div class="auth-card-header">
            <h3>💚 Make Your Donation</h3>
            <p>Safe, secure & impactful</p>
          </div>
          <div class="auth-card-body">
            <form id="donateForm" novalidate data-site-url="<?= SITE_URL ?>">

              <!-- Quick Amount Buttons -->
              <div class="mb-4">
                <label class="form-label fw-semibold">Select Amount (GHS)</label>
                <div class="quick-amounts">
                  <?php foreach ([10, 20, 50, 100, 200, 500] as $preset): ?>
                    <button type="button" class="quick-amount-btn" data-amount="<?= $preset ?>">
                      GHS <?= $preset ?>
                    </button>
                  <?php endforeach; ?>
                </div>
                <div class="input-group mt-3">
                  <span class="input-group-text fw-bold">GHS</span>
                  <input type="number" class="form-control form-control-3d" name="amount"
                         id="donationAmount" placeholder="Enter custom amount" min="1" max="100000"
                         step="0.01" required>
                </div>
                <div class="invalid-feedback">Please enter an amount between GHS 1 and GHS 100,000.</div>
              </div>

              <!-- Campaign -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Choose a Campaign</label>
                <select class="form-select form-control-3d" name="campaign">
                  <option value="General Fund">General Fund</option>
                  <option value="#BanSingleUsePlastics">#BanSingleUsePlastics Campaign</option>
                  <option value="#CleanOurCoast">#CleanOurCoast Drive</option>
                  <option value="#PlasticFreeSchools">#PlasticFreeSchools Campaign</option>
                  <option value="Research & Advocacy">Research & Policy Advocacy</option>
                </select>
              </div>

              <!-- Donor Info (if not logged in) -->
              <?php if (!$isLoggedIn): ?>
              <div class="row g-3 mb-3">
                <div class="col-12">
                  <label class="form-label fw-semibold">Your Name</label>
                  <input type="text" class="form-control form-control-3d" name="name" placeholder="Your full name" required>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">Email Address</label>
                  <input type="email" class="form-control form-control-3d" name="email"
                         placeholder="For your donation receipt" required>
                </div>
              </div>
              <?php endif; ?>

              <!-- Message -->
              <div class="mb-4">
                <label class="form-label fw-semibold">Leave a Message <span class="text-muted">(optional)</span></label>
                <textarea class="form-control form-control-3d" name="message" rows="3"
                          placeholder="Share why you're donating..."></textarea>
              </div>

              <!-- Impact preview -->
              <div class="impact-preview mb-4" id="impactPreview">
                <div class="impact-icon">🌿</div>
                <div class="impact-text">Select an amount to see your impact</div>
              </div>

              <div id="donateAlert" class="mb-3"></div>

              <div class="d-grid">
                <button type="submit" class="btn btn-cta-3d btn-lg glow-pulse" id="donateBtn">
                  <span class="btn-text"><i class="bi bi-heart-fill me-2"></i>Donate Now</span>
                  <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm me-2"></span>Processing...</span>
                </button>
              </div>
              <p class="text-center text-muted small mt-3">
                <i class="bi bi-shield-lock me-1"></i>
                Your donation is safe and secure. We never store payment card details.
              </p>
            </form>
          </div>
        </div>
      </div>

      <!-- Impact sidebar -->
      <div class="col-lg-5">
        <div class="donate-sidebar">
          <h5 class="fw-bold mb-4">💡 Your Impact</h5>
          <div class="impact-items">
            <div class="impact-item-card">
              <span class="impact-emoji">📚</span>
              <div>
                <strong>GHS 10</strong>
                <p>Funds a plastic education workshop for 30 students</p>
              </div>
            </div>
            <div class="impact-item-card">
              <span class="impact-emoji">🧹</span>
              <div>
                <strong>GHS 50</strong>
                <p>Equips a team for a full shoreline cleanup day</p>
              </div>
            </div>
            <div class="impact-item-card">
              <span class="impact-emoji">🌊</span>
              <div>
                <strong>GHS 100</strong>
                <p>Funds our ocean monitoring equipment for one month</p>
              </div>
            </div>
            <div class="impact-item-card">
              <span class="impact-emoji">📢</span>
              <div>
                <strong>GHS 200</strong>
                <p>Sponsors a policy advocacy campaign in Parliament</p>
              </div>
            </div>
            <div class="impact-item-card">
              <span class="impact-emoji">🏫</span>
              <div>
                <strong>GHS 500+</strong>
                <p>Brings PlasticFreeSchools to a new school for a full term</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?= SITE_URL ?>/js/donate.js" defer></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
