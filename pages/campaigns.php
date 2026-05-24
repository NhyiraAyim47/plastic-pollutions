<?php
// pages/campaigns.php  –  Campaigns page

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/visitor_counter.php';
trackVisitor('/campaigns');

$pageTitle    = 'Our Campaigns – PlasticPollutions';
$pageDesc     = 'Explore PlasticPollutions\' active and past campaigns: #BanSingleUsePlastics, #CleanOurCoast, #PlasticFreeSchools and more. See the measurable impact we are making in Ghana.';
$pageKeywords = 'plastic campaigns Ghana, ban single use plastics, beach cleanup Ghana, Pentecost University environment, plastic free schools';

$db = getDB();
$petitionCount = (int)$db->query("SELECT COUNT(*) FROM petitions")->fetchColumn();
$extraCss = [SITE_URL . '/css/campaigns.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero -->
<div class="page-hero bg-dark-green text-white py-5">
  <div class="container text-center">
    <span class="section-badge-light">Making Change Happen</span>
    <h1 class="section-title-white">Our <span class="text-success-light">Campaigns</span></h1>
    <p class="text-white-50 mx-auto" style="max-width:580px;">Real action, measurable results. From Parliament to classrooms to coastlines — here's what we're doing about plastic pollution.</p>
  </div>
</div>

<!-- Campaign Impact Summary -->
<section class="stats-section">
  <div class="container">
    <div class="row g-4 justify-content-center">
      <div class="col-lg-3 col-md-6">
        <div class="stat-card-3d">
          <div class="stat-icon">✍️</div>
          <div class="stat-number" data-target="<?= $petitionCount ?>" data-suffix="+">0</div>
          <div class="stat-label">Petition Signatures</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card-3d">
          <div class="stat-icon">🏫</div>
          <div class="stat-number" data-target="32" data-suffix="+">0</div>
          <div class="stat-label">Schools Reached</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card-3d">
          <div class="stat-icon">🧹</div>
          <div class="stat-number" data-target="18" data-suffix="">0</div>
          <div class="stat-label">Cleanup Drives Run</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card-3d">
          <div class="stat-icon">🏛️</div>
          <div class="stat-number" data-target="5" data-suffix="">0</div>
          <div class="stat-label">Parliamentary Submissions</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Active Campaigns -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Currently Running</span>
      <h2 class="section-title">Active <span class="text-success">Campaigns</span></h2>
    </div>
    <?php
    $campaigns = [
      [
        'hashtag'  => '#BanSingleUsePlastics',
        'title'    => 'Petition for a National Single-Use Plastic Ban',
        'category' => 'Policy Advocacy',
        'status'   => 'active',
        'progress' => ['current' => $petitionCount, 'target' => 10000, 'label' => 'Signatures'],
        'image'    => 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=700',
        'desc'     => 'We are calling on the Ghana Parliament to enact comprehensive legislation banning non-essential single-use plastics including bags, straws, and cutlery. Our petition targets 10,000 signatures before formal submission to the Speaker of Parliament.',
        'impact'   => ['5 Parliamentary submissions made','Endorsed by 3 national NGOs','Featured in Daily Graphic and Joy FM'],
        'link'     => SITE_URL.'/pages/what_to_do.php#petition',
        'cta'      => 'Sign the Petition'
      ],
      [
        'hashtag'  => '#CleanOurCoast',
        'title'    => 'Monthly Shoreline Cleanup Drive',
        'category' => 'Direct Action',
        'status'   => 'active',
        'progress' => ['current' => 340, 'target' => 500, 'label' => 'Volunteers Recruited'],
        'image'    => 'https://images.unsplash.com/photo-1621451537084-482c73073a0f?w=700',
        'desc'     => 'Every first Saturday of the month, our volunteers tackle Ghana\'s most polluted shorelines in the Volta Region and Greater Accra. Each drive removes an average of 800kg of plastic waste. We partner with local fishing communities to implement responsible net-disposal schemes.',
        'impact'   => ['18 cleanup drives completed','14.4 tonnes of plastic removed','340 volunteers mobilised','12 fishing communities partnered'],
        'link'     => SITE_URL.'/pages/how_to_help.php',
        'cta'      => 'Volunteer Now'
      ],
      [
        'hashtag'  => '#PlasticFreeSchools',
        'title'    => 'Eliminating Plastics from School Canteens',
        'category' => 'Education',
        'status'   => 'active',
        'progress' => ['current' => 32, 'target' => 50, 'label' => 'Schools Participating'],
        'image'    => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=700',
        'desc'     => 'Working with school headmasters, parents, and canteen operators in the Greater Accra Region to replace single-use plastic bags, sachets, and cutlery with reusable and biodegradable alternatives. We provide training, starter kits, and ongoing support to participating schools.',
        'impact'   => ['32 schools enrolled','4,800 students educated','Estimated 120,000 plastics prevented monthly','8 canteen operators switched to reusable packaging'],
        'link'     => SITE_URL.'/pages/how_to_help.php',
        'cta'      => 'Partner Your School'
      ],
    ];
    foreach ($campaigns as $i => $c):
    $pct = min(100, ($c['progress']['current'] / $c['progress']['target']) * 100);
    ?>
    <div class="campaign-feature-card mb-5 reveal <?= $i%2===0?'left':'right' ?>">
      <div class="row g-0 align-items-center <?= $i%2!==0?'flex-row-reverse':'' ?>">
        <div class="col-lg-5">
          <div class="campaign-feat-img-wrap">
            <img src="<?= $c['image'] ?>" alt="<?= htmlspecialchars($c['title']) ?>" loading="lazy">
            <div class="campaign-status-badge <?= $c['status'] ?>">
              <i class="bi bi-circle-fill me-1 blink"></i><?= ucfirst($c['status']) ?>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="campaign-feat-body">
            <div class="d-flex gap-2 align-items-center mb-3">
              <span class="campaign-hashtag"><?= $c['hashtag'] ?></span>
              <span class="badge bg-light text-dark"><?= $c['category'] ?></span>
            </div>
            <h3><?= $c['title'] ?></h3>
            <p class="text-muted"><?= $c['desc'] ?></p>

            <!-- Progress -->
            <div class="mb-4">
              <div class="d-flex justify-content-between mb-2">
                <small class="fw-semibold"><?= $c['progress']['label'] ?></small>
                <small class="fw-bold text-success"><?= number_format($c['progress']['current']) ?> / <?= number_format($c['progress']['target']) ?></small>
              </div>
              <div class="progress-3d">
                <div class="progress-fill" style="width:<?= $pct ?>%"></div>
              </div>
              <small class="text-muted"><?= round($pct) ?>% of target</small>
            </div>

            <!-- Impact Points -->
            <ul class="impact-list mb-4">
              <?php foreach ($c['impact'] as $imp): ?>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i><?= $imp ?></li>
              <?php endforeach; ?>
            </ul>

            <a href="<?= $c['link'] ?>" class="btn btn-green-3d">
              <i class="bi bi-arrow-right me-2"></i><?= $c['cta'] ?>
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Government Engagement -->
<section class="section-pad bg-light-green">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Policy Impact</span>
      <h2 class="section-title">Influencing <span class="text-success">Government Policy</span></h2>
    </div>
    <div class="row g-4 align-items-center">
      <div class="col-lg-6">
        <div class="policy-timeline">
          <?php foreach ([
            ['Jan 2024','First Submission to Parliament','Submitted a 12-page evidence brief on single-use plastic impacts to the Environment Committee of Parliament.','bi-file-earmark-text'],
            ['Jun 2024','EPA Partnership','Signed a Memorandum of Understanding with the Environmental Protection Agency for joint awareness campaigns.','bi-handshake'],
            ['Oct 2024','National Plastic Summit','Participated in Ghana\'s National Plastic Waste Summit, presenting our community data to ministers and industry leaders.','bi-people'],
            ['Mar 2025','Media Campaign','Collaborated with Joy FM and Adom TV on a 4-episode radio/TV series on plastic pollution reaching 2.3M listeners.','bi-broadcast'],
          ] as $event): ?>
          <div class="policy-event">
            <div class="policy-event-icon"><i class="bi <?= $event[3] ?>"></i></div>
            <div class="policy-event-body">
              <span class="policy-event-date"><?= $event[0] ?></span>
              <h6><?= $event[1] ?></h6>
              <p><?= $event[2] ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="img-3d-frame">
          <div class="img-3d-badge"><i class="bi bi-building me-1"></i>5 Parliamentary Submissions</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Manufacturer Collaboration -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Industry Partners</span>
      <h2 class="section-title">Working with <span class="text-success">Manufacturers</span></h2>
      <p class="text-muted mx-auto" style="max-width:580px;">We believe producers must take responsibility for the full lifecycle of their packaging. Here's how we engage the private sector.</p>
    </div>
    <div class="row g-4">
      <?php foreach ([
        ['🏭','Extended Producer Responsibility','We advocate for EPR legislation requiring manufacturers to fund collection and recycling of their packaging — shifting the cost from taxpayers to producers.'],
        ['🌱','Biodegradable Alternatives','We partner with the Faculty of Engineering to connect manufacturers with locally produced biodegradable packaging alternatives made from cassava starch and banana leaves.'],
        ['📦','Packaging Audits','We invite manufacturers to voluntarily undergo plastic packaging audits and publish results, building consumer trust and driving internal sustainability targets.'],
        ['🏅','PlasticFree Certification','We are developing a PlasticPollutions certification scheme for businesses that meet our standards for plastic reduction — rewarding leaders and incentivising laggards.'],
      ] as $mfr): ?>
      <div class="col-lg-3 col-md-6">
        <div class="info-card-3d success text-center">
          <div class="info-card-icon"><?= $mfr[0] ?></div>
          <h5><?= $mfr[1] ?></h5>
          <p><?= $mfr[2] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script src="<?= SITE_URL ?>/js/counter.js"></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
