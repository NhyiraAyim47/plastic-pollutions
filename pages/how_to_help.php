<?php
// pages/how_to_help.php  –  How You Can Help

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/visitor_counter.php';
trackVisitor('/how-to-help');

$pageTitle    = 'How You Can Help – PlasticPollutions';
$pageDesc     = 'Find out how you can fight plastic pollution in Ghana — volunteer, donate, sign petitions, change your lifestyle, and make a measurable difference today.';
$pageKeywords = 'volunteer Ghana, help plastic pollution, environmental volunteer, reduce plastic lifestyle, donate Ghana environment';

// Pledge submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'pledge') {
    header('Content-Type: application/json');
    $db     = getDB();
    $name   = trim(filter_input(INPUT_POST,'name',  FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $email  = trim(filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL) ?? '');
    $pledge = trim(filter_input(INPUT_POST,'pledge',FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    if (strlen($name)<2 || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($pledge)<5) {
        echo json_encode(['success'=>false,'message'=>'Please complete all pledge fields.']); exit;
    }
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    $db->prepare("INSERT INTO pledges (user_id,name,email,pledge) VALUES (?,?,?,?)")->execute([$userId,$name,$email,$pledge]);
    $count = $db->query("SELECT COUNT(*) FROM pledges")->fetchColumn();
    echo json_encode(['success'=>true,'message'=>'Your pledge has been recorded! Thank you, '.$name.'. You are pledge #'.number_format($count).'.','count'=>$count]);
    exit;
}

$db = getDB();
$pledgeCount = (int)$db->query("SELECT COUNT(*) FROM pledges")->fetchColumn();
$extraCss = [SITE_URL . '/css/how_to_help.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero -->
<div class="page-hero bg-dark-green text-white py-5">
  <div class="container text-center">
    <span class="section-badge-light">Join the Movement</span>
    <h1 class="section-title-white">How <span class="text-success-light">You</span> Can Help</h1>
    <p class="text-white-50 mx-auto" style="max-width:580px;">You don't need to be an expert. You just need to care. Here are practical ways to make a real difference today.</p>
  </div>
</div>

<!-- Quick Ways to Act -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Start Today</span>
      <h2 class="section-title">4 Ways to <span class="text-success">Get Involved</span></h2>
    </div>
    <div class="row g-4">
      <?php foreach ([
       ['🙋','Volunteer','Join our monthly cleanup drives, school workshops, and events. No experience required — just bring your energy and a reusable water bottle.','Cleanup drives | School visits | Community events | Social media team', SITE_URL.'/pages/contact.php','volunteer','Apply to Volunteer'],
       ['💰','Donate','Financially support our campaigns. Even a small contribution goes a long way — GHS 10 funds a workshop for 30 students.','One-time | Monthly | Campaign-specific | In-kind', SITE_URL.'/donations/donate.php','donate','Donate Now'],
       ['✍️','Sign Petitions','Add your voice to demand government action. Our petition to ban single-use plastics needs 10,000 signatures before parliamentary submission.','National plastic ban | EPR legislation | Municipal recycling', SITE_URL.'/pages/what_to_do.php#petition','petition','Sign Now'],
       ['📢','Spread the Word','Share our content on social media, talk to your family and workplace, and bring the conversation about plastic pollution into your daily life.','Social sharing | Word of mouth | Workplace campaigns | School projects',SITE_URL.'/pages/share.php','share','Share & Inspire'],
      ] as $way): ?>
      <div class="col-lg-3 col-md-6" id="<?= $way[5] ?>">
        <div class="help-way-card">
          <div class="help-way-emoji"><?= $way[0] ?></div>
          <h4><?= $way[1] ?></h4>
          <p class="text-muted"><?= $way[2] ?></p>
          <ul class="help-tags">
            <?php foreach (explode(' | ',$way[3]) as $tag): ?>
              <li><?= trim($tag) ?></li>
            <?php endforeach; ?>
          </ul>
          <a href="<?= $way[4] ?>" class="btn btn-green-3d w-100 mt-3">
            <i class="bi bi-arrow-right me-2"></i><?= $way[6] ?>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Lifestyle Changes -->
<section class="section-pad bg-light-green">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Daily Actions</span>
      <h2 class="section-title">Simple <span class="text-success">Lifestyle Changes</span></h2>
      <p class="text-muted mx-auto" style="max-width:560px;">The most powerful thing you can do is reduce your own plastic footprint. Here's how.</p>
    </div>
    <div class="row g-3">
      <?php
      $changes = [
        ['🛍️','Carry a reusable bag','Keep a foldable bag in your pocket or handbag. Refuse plastic bags at every shop.','Easy'],
        ['🍶','Use a reusable water bottle','A single reusable bottle prevents hundreds of single-use plastic bottles per year.','Easy'],
        ['🥤','Ditch plastic straws','Carry a metal or bamboo straw. Request "no straw" at restaurants and bars.','Easy'],
        ['🥡','Avoid plastic takeaway containers','Choose restaurants that use biodegradable packaging, or bring your own container.','Moderate'],
        ['🛒','Buy in bulk','Bulk buying reduces packaging waste significantly. Shop at markets where you control the containers.','Moderate'],
        ['♻️','Sort your waste properly','Learn your local recycling rules and separate plastics by type. Clean containers before recycling.','Easy'],
        ['🧴','Choose refillable products','Switch to shampoo bars, refillable cleaning products, and concentrated detergents.','Moderate'],
        ['📣','Talk about it','Bring up plastic pollution with friends, family, coworkers, and on social media. Awareness is contagious.','Easy'],
      ];
      foreach ($changes as $change):
      ?>
      <div class="col-lg-3 col-md-6">
        <div class="lifestyle-card reveal">
          <span class="lifestyle-emoji"><?= $change[0] ?></span>
          <div>
            <strong><?= $change[1] ?></strong>
            <p><?= $change[2] ?></p>
            <span class="badge bg-<?= $change[3]==='Easy'?'success':'warning' ?> bg-opacity-10 text-<?= $change[3]==='Easy'?'success':'warning' ?>"><?= $change[3] ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Pledge System -->
<section class="section-pad bg-dark-green text-white">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <span class="section-badge-light">Make a Commitment</span>
        <h2 class="section-title-white">Take the <span class="text-success-light">Plastic-Free Pledge</span></h2>
        <p class="text-white-50 mb-4">Join <?= number_format($pledgeCount) ?>+ others who have publicly committed to reducing their plastic footprint. Share your pledge and inspire others to follow.</p>

        <div class="pledge-counter mb-4">
          <span class="counter-num" id="pledgeCount"><?= number_format($pledgeCount) ?></span>
          <span class="counter-label d-block">people have pledged so far</span>
        </div>

        <form id="pledgeForm" novalidate data-site-url="<?= SITE_URL ?>">
          <input type="hidden" name="action" value="pledge">
          <div class="row g-3">
            <div class="col-md-6">
              <input type="text" class="form-control form-control-3d" name="name" placeholder="Your name" required minlength="2"
                     value="<?= isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_name']??'') : '' ?>">
            </div>
            <div class="col-md-6">
              <input type="email" class="form-control form-control-3d" name="email" placeholder="Your email" required>
            </div>
            <div class="col-12">
              <select class="form-select form-control-3d" name="pledge" required>
                <option value="">— Choose your pledge —</option>
                <option value="I will stop using single-use plastic bags">I will stop using single-use plastic bags</option>
                <option value="I will carry a reusable water bottle every day">I will carry a reusable water bottle every day</option>
                <option value="I will sort and recycle my plastic waste properly">I will sort and recycle my plastic waste properly</option>
                <option value="I will volunteer for a PlasticPollutions cleanup drive">I will volunteer for a cleanup drive</option>
                <option value="I will encourage my workplace/school to reduce plastic use">I will encourage my workplace/school to reduce plastic</option>
                <option value="I will donate monthly to PlasticPollutions">I will donate monthly to PlasticPollutions</option>
              </select>
            </div>
          </div>
          <div id="pledgeAlert" class="mt-3"></div>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-hero-primary btn-lg" id="pledgeBtn">
              <span class="btn-text"><i class="bi bi-award me-2"></i>Make My Pledge</span>
              <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm me-2"></span>Pledging...</span>
            </button>
          </div>
        </form>
      </div>

      <div class="col-lg-6">
        <div class="pledge-wall">
          <h5 class="text-white fw-bold mb-3">Recent Pledges 🌿</h5>
          <div class="pledge-items" id="pledgeWall">
            <?php
            $recent = $db->query("SELECT name, pledge, pledged_at FROM pledges ORDER BY pledged_at DESC LIMIT 6")->fetchAll();
            foreach ($recent as $p):
            ?>
            <div class="pledge-item">
              <div class="pledge-avatar"><?= strtoupper(substr($p['name'],0,1)) ?></div>
              <div>
                <strong><?= htmlspecialchars($p['name']) ?></strong>
                <p>"<?= htmlspecialchars($p['pledge']) ?>"</p>
                <small><?= date('d M Y', strtotime($p['pledged_at'])) ?></small>
              </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($recent)): ?>
              <p class="text-white-50">Be the first to pledge! 🌿</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?= SITE_URL ?>/js/how_to_help.js" defer></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
