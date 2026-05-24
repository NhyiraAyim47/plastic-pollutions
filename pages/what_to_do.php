<?php
// pages/what_to_do.php  –  What To Do About Plastic

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/visitor_counter.php';
trackVisitor('/what-to-do');

$pageTitle    = 'What To Do About Plastic';
$pageDesc     = 'Learn about plastic types, their environmental impact, recycling methods, and how you can sign our petition to demand change in Ghana.';
$pageKeywords = 'plastic types, recycling Ghana, reduce plastic, single-use plastic, environmental impact, petition plastic ban';

// Petition submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'sign_petition') {
    header('Content-Type: application/json');
    $db    = getDB();
    $name  = trim(filter_input(INPUT_POST,'name',  FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $email = trim(filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL) ?? '');
    if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success'=>false,'message'=>'Please provide your name and a valid email.']); exit;
    }
    // Check duplicate
    $chk = $db->prepare("SELECT id FROM petitions WHERE email=?"); $chk->execute([$email]);
    if ($chk->fetch()) { echo json_encode(['success'=>false,'message'=>'You have already signed the petition. Thank you!']); exit; }
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    $db->prepare("INSERT INTO petitions (user_id,name,email) VALUES (?,?,?)")->execute([$userId,$name,$email]);
    $count = $db->query("SELECT COUNT(*) FROM petitions")->fetchColumn();
    echo json_encode(['success'=>true,'message'=>'Thank you for signing! You are signatory #'.number_format($count).'.','count'=>$count]); exit;
}

$db = getDB();
$petitionCount = (int)$db->query("SELECT COUNT(*) FROM petitions")->fetchColumn();
$extraCss = [SITE_URL . '/css/what_to_do.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero -->
<div class="page-hero bg-dark-green text-white py-5">
  <div class="container text-center">
    <span class="section-badge-light">Take Action</span>
    <h1 class="section-title-white">What To Do About <span class="text-success-light">Plastic</span></h1>
    <p class="text-white-50 mx-auto" style="max-width:580px;">Understanding plastics is the first step to eliminating them. Learn, act, and demand change.</p>
  </div>
</div>

<!-- Plastic Types -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Knowledge is Power</span>
      <h2 class="section-title">The 7 Types of <span class="text-success">Plastic</span></h2>
    </div>
    <div class="row g-4">
      <?php
      $plastics = [
        ['1','PET / PETE','Polyethylene Terephthalate','Water bottles, soda bottles, food packaging','♻️ Widely recyclable. Take to collection points or curbside bins.','success'],
        ['2','HDPE','High-Density Polyethylene','Milk jugs, shampoo bottles, detergent containers','♻️ Widely recyclable. Most commonly accepted.','success'],
        ['3','PVC','Polyvinyl Chloride','Pipes, window frames, blister packaging','⚠️ Rarely recycled. Avoid when possible; contains toxic additives.','warning'],
        ['4','LDPE','Low-Density Polyethylene','Shopping bags, cling film, squeezable bottles','⚠️ Some drop-off points accept. Not curbside recyclable.','warning'],
        ['5','PP','Polypropylene','Yoghurt containers, bottle caps, straws','♻️ Increasingly recyclable. Check local facilities.','info'],
        ['6','PS','Polystyrene (Styrofoam)','Cups, takeaway boxes, packing peanuts','❌ Rarely recycled. Try to refuse and reduce use.','danger'],
        ['7','Other','Mixed/Other (inc. Polycarbonate)','DVDs, sunglasses, baby bottles, water cooler jugs','❌ Difficult to recycle. Avoid single-use forms entirely.','danger'],
      ];
      foreach ($plastics as $p):
      ?>
      <div class="col-lg-4 col-md-6 reveal reveal-delay-<?= min(4,$p[0]) ?>">
        <div class="plastic-card-3d <?= $p[5] ?>">
          <div class="plastic-number"><?= $p[0] ?></div>
          <div class="plastic-body">
            <div class="plastic-code"><?= $p[1] ?></div>
            <h6><?= $p[2] ?></h6>
            <p class="text-muted small mb-2"><strong>Found in:</strong> <?= $p[3] ?></p>
            <div class="plastic-recycle alert-<?= $p[5] ?>"><?= $p[4] ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Multimedia / Video -->
<section class="section-pad bg-light-green">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="section-badge">Watch & Learn</span>
        <h2 class="section-title">The Ocean Plastic <span class="text-success">Crisis</span></h2>
        <p>Millions of tonnes of plastic enter our oceans each year, breaking down into microplastics that enter the food chain. Watch this short documentary to understand the scale of the problem and what collective action can achieve.</p>
        <ul class="list-unstyled mt-3">
          <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>500,000+ marine animals die from plastic entanglement annually</li>
          <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Microplastics found in 83% of tap water samples worldwide</li>
          <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Ghana generates 1.1M tonnes of plastic waste per year</li>
        </ul>
      </div>
      <div class="col-lg-6">
        <div class="video-frame-3d">
          <!-- Accessible video with captions placeholder -->
          <div class="video-placeholder" id="videoPlaceholder">
            <div class="video-thumb">
              <img src="https://images.unsplash.com/photo-1621451537084-482c73073a0f?w=700"
                   alt="Ocean plastic pollution documentary thumbnail" class="img-fluid">
              <div class="play-overlay">
                <button class="play-btn" onclick="loadVideo()" aria-label="Play ocean plastic documentary">
                  <i class="bi bi-play-fill"></i>
                </button>
                <p class="text-white mt-2 small">The Plastic Ocean – 4 min documentary</p>
              </div>
            </div>
          </div>
          <div id="videoEmbed" class="d-none">
            <!-- Replace VIDEO_ID with actual YouTube video ID -->
            <iframe width="100%" height="340" src="about:blank" data-src="https://www.youtube.com/embed/BHACKCNDMW8?autoplay=1"
                    title="Ocean Plastic Crisis Documentary"
                    allow="autoplay; fullscreen"
                    allowfullscreen
                    style="border-radius:var(--radius-lg);border:none;"
                    aria-label="Documentary video about ocean plastic pollution"></iframe>
          </div>
          <p class="small text-muted mt-2"><i class="bi bi-info-circle me-1"></i>
            Captions available. Click CC in the video player. For screen readers: a transcript is available on request via the <a href="<?= SITE_URL ?>/pages/contact.php">Contact page</a>.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Recycling Guide -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Take Action Now</span>
      <h2 class="section-title">How to <span class="text-success">Recycle Right</span></h2>
    </div>
    <div class="row g-4">
      <?php foreach ([
        ['1','Clean It',  'bi-droplet-fill',       'Rinse containers before recycling. Food residue contaminates entire batches.', 'primary'],
        ['2','Sort It',   'bi-grid-3x3-gap-fill',  'Separate by plastic type. Use the resin code (number) on the bottom of each item.', 'success'],
        ['3','Drop It',   'bi-geo-alt-fill',       'Take non-curbside plastics to designated collection points. Find your nearest one below.', 'warning'],
        ['4','Refuse It', 'bi-x-circle-fill',      'Best solution: refuse single-use plastics at source. Bring reusable bags, bottles, and cutlery.', 'danger'],
      ] as $step): ?>
      <div class="col-md-6 col-lg-3">
        <div class="recycle-step-card">
          <div class="step-number"><?= $step[0] ?></div>
          <div class="step-icon text-<?= $step[4] ?>"><i class="bi <?= $step[2] ?>"></i></div>
          <h6><?= $step[1] ?></h6>
          <p><?= $step[3] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Donate + Petition Section -->
<section class="section-pad bg-dark-green text-white" id="petition">
  <div class="container">
    <div class="row g-5">
      <!-- Petition -->
      <div class="col-lg-6">
        <span class="section-badge-light">Sign the Petition</span>
        <h2 class="section-title-white">Demand a <span class="text-success-light">Plastic Ban</span></h2>
        <p class="text-white-50 mb-4">We are calling on the Ghana Parliament to enact comprehensive legislation banning single-use plastics. Add your signature and be counted.</p>

        <div class="petition-counter mb-4">
          <div class="counter-display">
            <span id="petitionCount" class="counter-num"><?= number_format($petitionCount) ?></span>
            <span class="counter-label">signatures of 10,000 goal</span>
          </div>
          <div class="progress mt-2" style="height:10px;border-radius:5px;">
            <div class="progress-bar bg-success" style="width:<?= min(100,($petitionCount/10000)*100) ?>%" id="petitionBar"></div>
          </div>
        </div>

        <form id="petitionForm" novalidate data-site-url="<?= SITE_URL ?>">
          <input type="hidden" name="action" value="sign_petition">
          <div class="row g-3">
            <div class="col-12">
              <input type="text" class="form-control form-control-3d" name="name"
                     placeholder="Your full name" required minlength="2"
                     value="<?= isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_name']??'') : '' ?>">
            </div>
            <div class="col-12">
              <input type="email" class="form-control form-control-3d" name="email"
                     placeholder="Your email address" required>
            </div>
          </div>
          <div id="petitionAlert" class="mt-3"></div>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-hero-primary btn-lg" id="petitionBtn">
              <span class="btn-text"><i class="bi bi-pen me-2"></i>Sign the Petition</span>
              <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm me-2"></span>Submitting...</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Donate CTA -->
      <div class="col-lg-6">
        <div class="donate-cta-box">
          <span class="section-badge">Fund the Fight</span>
          <h3>Every Cedi Makes a Difference</h3>
          <p>Your donation funds our campaigns, school workshops, and beach cleanup operations.</p>
          <div class="donate-amounts-quick my-4">
            <?php foreach ([10,20,50,100] as $amt): ?>
              <a href="<?= SITE_URL ?>/donations/donate.php?amount=<?= $amt ?>" class="donate-quick-btn">GHS <?= $amt ?></a>
            <?php endforeach; ?>
          </div>
          <a href="<?= SITE_URL ?>/donations/donate.php" class="btn btn-hero-primary btn-lg">
            <i class="bi bi-heart me-2"></i>Donate Custom Amount
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="<?= SITE_URL ?>/js/what_to_do.js" defer></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
