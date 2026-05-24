<?php
// pages/strategy.php  –  Our Strategy page

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/visitor_counter.php';
trackVisitor('/strategy');

$pageTitle    = 'Our Strategy – PlasticPollutions';
$pageDesc     = 'Learn about PlasticPollutions\' strategic approach to tackling plastic waste in Ghana — our goals, pillars, policies, and long-term vision for a plastic-free future.';
$pageKeywords = 'plastic pollution strategy, Ghana environmental policy, Pentecost University action plan, reduce plastic waste goals, sustainability roadmap';

$extraCss = [SITE_URL . '/css/strategy.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero -->
<div class="page-hero bg-dark-green text-white py-5">
  <div class="container text-center">
    <span class="section-badge-light">Our Roadmap</span>
    <h1 class="section-title-white">Our <span class="text-success-light">Strategy</span></h1>
    <p class="text-white-50 mx-auto" style="max-width:580px;">
      A clear, evidence-based approach to eliminating plastic pollution from Ghana's communities, water bodies, and ecosystems.
    </p>
  </div>
</div>

<!-- Mission & Vision -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <div class="content-3d-card">
          <span class="section-badge">Who We Are</span>
          <h2 class="section-title">Mission &amp; <span class="text-success">Vision</span></h2>
          <div class="mv-block mb-4">
            <div class="mv-icon">🎯</div>
            <div>
              <h5 class="fw-bold">Our Mission</h5>
              <p class="text-muted">To mobilise students, communities, and institutions to dramatically reduce plastic waste through education, advocacy, innovation, and direct action — making Ghana a model for sustainable plastic management in Africa.</p>
            </div>
          </div>
          <div class="mv-block">
            <div class="mv-icon">🔭</div>
            <div>
              <h5 class="fw-bold">Our Vision</h5>
              <p class="text-muted">A Ghana where single-use plastics are obsolete, recycling infrastructure is universal, and every citizen understands their role in protecting the environment for future generations.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="img-3d-frame">
          <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=700"
               alt="PlasticPollutions strategy planning session" class="img-fluid rounded-4" loading="lazy">
          <div class="img-3d-badge">🌿 Since 2023</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Strategic Pillars -->
<section class="section-pad bg-light-green">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">How We Work</span>
      <h2 class="section-title">Our 4 Strategic <span class="text-success">Pillars</span></h2>
      <p class="text-muted mx-auto" style="max-width:580px;">Everything we do falls under one of four interconnected pillars that collectively move Ghana toward a plastic-free future.</p>
    </div>
    <div class="row g-4">
      <?php
      $pillars = [
        ['🎓','Education & Awareness','Changing behaviour starts with knowledge. We run workshops in schools, universities, and communities, create accessible digital content, and partner with media organisations to keep plastic pollution on the public agenda.','School workshops | Digital campaigns | Community outreach | Media partnerships','primary'],
        ['📢','Policy Advocacy','Real change requires systemic action. We engage Members of Parliament, work with the Environmental Protection Agency, and submit evidence-based policy briefs calling for Extended Producer Responsibility (EPR) legislation and single-use plastic bans.','Parliamentary engagement | EPA collaboration | Policy briefs | Public petitions','success'],
        ['♻️','Recycling Infrastructure','We support the creation and expansion of recycling facilities, negotiate net-return schemes with fishing communities, and work with municipalities to establish collection points across Greater Accra and the Volta Region.','Community collection points | Fishing net returns | Municipal partnerships | Sorting facilities','warning'],
        ['🔬','Research & Innovation','We commission and publish research on plastic pollution rates in Ghanaian water bodies, and partner with the Faculty of Engineering to develop low-cost, locally manufacturable alternatives to single-use plastics.','Water body surveys | Alternative materials R&D | University partnerships | Published reports','danger'],
      ];
      foreach ($pillars as $i => $p):
      ?>
      <div class="col-lg-6 reveal <?= $i%2===0?'left':'right' ?>">
        <div class="pillar-card-3d">
          <div class="pillar-icon-wrap">
            <span class="pillar-emoji"><?= $p[0] ?></span>
            <div class="pillar-num"><?= $i+1 ?></div>
          </div>
          <div class="pillar-body">
            <h4><?= $p[1] ?></h4>
            <p><?= $p[2] ?></p>
            <div class="pillar-tags">
              <?php foreach (explode(' | ', $p[3]) as $tag): ?>
                <span class="pillar-tag"><?= trim($tag) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Goals Timeline -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Our Roadmap</span>
      <h2 class="section-title">Strategic <span class="text-success">Milestones</span></h2>
    </div>
    <div class="timeline">
      <?php
      $milestones = [
        ['2023','Foundation','Established PlasticPollutions at Pentecost University. Launched first petition. Ran 5 school workshops.','completed'],
        ['2024','Expansion','Reached 1,000 petition signatures. Partnered with 20 schools. First Parliamentary submission on EPR legislation.','completed'],
        ['2025','Impact','Launched #CleanOurCoast monthly drives. Reached 8,500 petition signatures. Established 10 community collection points.','completed'],
        ['2026','Scale','Target 10,000 petition signatures. Launch plastic-free campus programme. Partner with 3 municipalities for curbside plastic collection.','active'],
        ['2027','Policy Win','Advocate for passage of national single-use plastic ban bill. Expand to 100 schools. Partner with 5 manufacturers on EPR.','upcoming'],
        ['2030','Vision','60% reduction in single-use plastic consumption in target communities. Universal recycling access across Greater Accra.','upcoming'],
      ];
      foreach ($milestones as $m):
      ?>
      <div class="timeline-item <?= $m[3] ?>">
        <div class="timeline-marker">
          <?php if ($m[3]==='completed'): ?><i class="bi bi-check-lg"></i>
          <?php elseif ($m[3]==='active'): ?><i class="bi bi-star-fill"></i>
          <?php else: ?><i class="bi bi-circle"></i>
          <?php endif; ?>
        </div>
        <div class="timeline-content">
          <div class="timeline-year"><?= $m[0] ?></div>
          <h5><?= $m[1] ?></h5>
          <p><?= $m[2] ?></p>
          <?php if ($m[3]==='active'): ?>
            <span class="badge bg-success">In Progress</span>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Policies & Commitments -->
<section class="section-pad bg-dark-green text-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge-light">Our Commitments</span>
      <h2 class="section-title-white">Policies &amp; <span class="text-success-light">Principles</span></h2>
    </div>
    <div class="row g-4">
      <?php foreach ([
        ['🌱','Sustainability First','Every decision we make is evaluated against its environmental impact. We practice what we preach — our events are plastic-free and our operations are carbon-conscious.'],
        ['🔍','Evidence-Based','Our campaigns are grounded in peer-reviewed research and credible data. We cite sources, publish findings, and welcome scientific scrutiny.'],
        ['🤝','Inclusive Action','Plastic pollution disproportionately affects low-income communities. We ensure our campaigns are accessible, multilingual, and community-led — not top-down.'],
        ['📊','Measurable Impact','We set specific, measurable targets and publish annual impact reports so our members, donors, and the public can hold us accountable.'],
        ['⚖️','Policy Integrity','We engage government and industry stakeholders constructively and transparently, without accepting funding or direction that compromises our advocacy positions.'],
        ['🔄','Circular Economy','We advocate for a shift from linear take-make-waste models to circular systems where materials are kept in use through design, reuse, and recycling.'],
      ] as $policy): ?>
      <div class="col-lg-4 col-md-6">
        <div class="policy-card">
          <div class="policy-emoji"><?= $policy[0] ?></div>
          <h5><?= $policy[1] ?></h5>
          <p><?= $policy[2] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
