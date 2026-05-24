<?php
// pages/home.php  –  Main landing page

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/visitor_counter.php';

trackVisitor('/');

$pageTitle    = 'Home – Fight Plastic Pollution in Ghana';
$pageDesc     = 'PlasticPollutions is Pentecost University\'s environmental action group. Join us to reduce plastic waste, protect Ghana\'s oceans, and build a sustainable future.';
$pageKeywords = 'plastic pollution Ghana, environmental action Pentecost University, reduce plastic waste, ocean conservation, recycling Ghana, sustainability';

require_once __DIR__ . '/../includes/header.php';

// Fetch stats from DB
$db            = getDB();
$totalDonors   = $db->query("SELECT COUNT(DISTINCT user_id) FROM donations")->fetchColumn() ?: 2847;
$totalDonated  = $db->query("SELECT COALESCE(SUM(amount),0) FROM donations")->fetchColumn() ?: 45820;
$totalMembers  = $db->query("SELECT COUNT(*) FROM users WHERE is_verified=1")->fetchColumn() ?: 1234;
$petitionCount = $db->query("SELECT COUNT(*) FROM petitions")->fetchColumn() ?: 8500;
$visitorCount  = getTotalVisitorCount();
?>

<!-- ===== HERO SECTION ===== -->
<section class="hero-section" id="heroSection">
  <!-- Image Slider Background -->
  <div class="hero-slider" id="heroSlider">
    <div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=1600')"></div>
    <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1621451537084-482c73073a0f?w=1600')"></div>
    <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1600')"></div>
    <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1567862688003-a53c5afee3e5?w=1600')"></div>
    <div class="slide-overlay"></div>
  </div>

  <!-- Slider Controls -->
  <button class="slider-btn slider-prev" aria-label="Previous slide"><i class="bi bi-chevron-left"></i></button>
  <button class="slider-btn slider-next" aria-label="Next slide"><i class="bi bi-chevron-right"></i></button>
  <div class="slider-dots" id="sliderDots"></div>

  <!-- Hero Content -->
  <div class="hero-content">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-xl-9 col-lg-10">
          <div class="hero-badge">
            <span>🌿 Pentecost University Environmental Action Group</span>
          </div>
          <h1 class="hero-title">
            Together We Can<br>
            <span class="hero-title-accent">End Plastic Pollution</span>
          </h1>
          <p class="hero-subtitle">
            Over <strong>8 million tonnes</strong> of plastic enter our oceans every year.
            Less than <strong>5%</strong> is recycled in Ghana. Join us — and be part of the solution.
          </p>
          <div class="hero-actions">
            <?php if (!isset($_SESSION['user_id'])): ?>
              <button class="btn btn-hero-primary btn-3d-pop" data-bs-toggle="modal" data-bs-target="#signUpModal">
                <i class="bi bi-person-plus me-2"></i>Sign Up Now
              </button>
            <?php endif; ?>
            <a href="<?= SITE_URL ?>/donations/donate.php" class="btn btn-hero-secondary btn-3d-pop">
              <i class="bi bi-heart me-2"></i>Donate Today
            </a>
            <a href="<?= SITE_URL ?>/pages/what_to_do.php#petition" class="btn btn-hero-outline btn-3d-pop">
              <i class="bi bi-pen me-2"></i>Sign Petition
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scroll indicator -->
  <div class="hero-scroll-hint">
    <span>Scroll to explore</span>
    <i class="bi bi-chevron-down scroll-bounce"></i>
  </div>
</section>

<!-- ===== ANIMATED STATS COUNTERS ===== -->
<section class="stats-section">
  <div class="container">
    <div class="row g-4 justify-content-center">
      <div class="col-lg-3 col-md-6">
        <div class="stat-card-3d">
          <div class="stat-icon">♻️</div>
          <div class="stat-number" data-target="<?= (int)$petitionCount ?>" data-suffix="+">0</div>
          <div class="stat-label">Petition Signatures</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card-3d">
          <div class="stat-icon">🌊</div>
          <div class="stat-number" data-target="<?= (int)$totalMembers ?>" data-suffix="+">0</div>
          <div class="stat-label">Registered Members</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card-3d">
          <div class="stat-icon">💰</div>
          <div class="stat-number" data-target="<?= (int)$totalDonated ?>" data-prefix="GHS " data-suffix="">0</div>
          <div class="stat-label">Total Donations Raised</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card-3d">
          <div class="stat-icon">👁️</div>
          <div class="stat-number" data-target="<?= max((int)$visitorCount, 5200) ?>" data-suffix="+">0</div>
          <div class="stat-label">Site Visitors</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== ABOUT / INTRO ===== -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="content-3d-card">
          <span class="section-badge">Who We Are</span>
          <h2 class="section-title">The <span class="text-success">PlasticPollutions</span> Group</h2>
          <p class="lead-text">
            We are a passionate group of students and faculty at Pentecost University, united by one mission: to drastically reduce plastic waste and protect our environment.
          </p>
          <p>
            Founded in response to alarming rates of plastic waste entering Ghana's water bodies — particularly during heavy rainy seasons — we advocate for sustainable practices, support recycling initiatives, and push for policy reform at local and national levels.
          </p>
          <div class="feature-list mt-4">
            <div class="feature-item">
              <span class="feature-icon">🌿</span>
              <div>
                <strong>Reduce Single-Use Plastics</strong>
                <p>Campaigns targeting manufacturers, retailers, and consumers.</p>
              </div>
            </div>
            <div class="feature-item">
              <span class="feature-icon">♻️</span>
              <div>
                <strong>Promote Recycling</strong>
                <p>Community education and infrastructure support for proper waste disposal.</p>
              </div>
            </div>
            <div class="feature-item">
              <span class="feature-icon">📢</span>
              <div>
                <strong>Policy Advocacy</strong>
                <p>Engaging government bodies to create and enforce plastic-reduction policies.</p>
              </div>
            </div>
          </div>
          <a href="<?= SITE_URL ?>/pages/strategy.php" class="btn btn-green-3d mt-4">
            <i class="bi bi-arrow-right me-2"></i>Learn Our Strategy
          </a>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="img-3d-frame">
          <img src="https://images.unsplash.com/photo-1567862688003-a53c5afee3e5?w=700"
               alt="Volunteers cleaning plastic waste from a beach in Ghana"
               class="img-fluid rounded-4" loading="lazy">
          <div class="img-3d-badge">
            <i class="bi bi-people-fill me-1"></i>
            <?= number_format((int)$totalMembers) ?>+ Members Strong
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== WHY IT MATTERS ===== -->
<section class="section-pad bg-light-green">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">The Crisis</span>
      <h2 class="section-title">Why Plastic Pollution <span class="text-success">Matters Now</span></h2>
      <p class="text-muted mx-auto" style="max-width:600px;">The scale of global plastic waste is staggering. Here's why we can't wait any longer to act.</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="info-card-3d danger">
          <div class="info-card-icon">🌊</div>
          <h5>8 Million Tonnes</h5>
          <p>Of plastic waste enters the world's oceans every year — equivalent to a rubbish truck every minute.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="info-card-3d warning">
          <div class="info-card-icon">🐟</div>
          <h5>100,000+ Marine Animals</h5>
          <p>Are killed by plastic pollution annually. Microplastics have been found in fish consumed by humans.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="info-card-3d info">
          <div class="info-card-icon">📊</div>
          <h5>Less Than 5% Recycled</h5>
          <p>In Ghana, only a tiny fraction of plastic waste is formally recycled. The rest ends up in landfills or our water bodies.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="info-card-3d success">
          <div class="info-card-icon">🏭</div>
          <h5>400 Million Tonnes</h5>
          <p>Of plastic is produced globally each year, with half designed to be used only once before disposal.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="info-card-3d purple">
          <div class="info-card-icon">⏳</div>
          <h5>500 Years to Decompose</h5>
          <p>A single plastic bottle takes up to 500 years to break down in the environment — long after we're gone.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="info-card-3d teal">
          <div class="info-card-icon">🌡️</div>
          <h5>Climate Link</h5>
          <p>Plastic production accounts for 3.4% of global greenhouse gas emissions, accelerating climate change.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== CAMPAIGNS PREVIEW ===== -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="row align-items-center mb-5">
      <div class="col">
        <span class="section-badge">Take Action</span>
        <h2 class="section-title mb-0">Our Active <span class="text-success">Campaigns</span></h2>
      </div>
      <div class="col-auto">
        <a href="<?= SITE_URL ?>/pages/campaigns.php" class="btn btn-outline-success">
          View All <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="campaign-card-3d">
          <div class="campaign-img-wrap">
            <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=500"
                 alt="Ban Single-Use Plastics Campaign" loading="lazy">
            <div class="campaign-category">Policy</div>
          </div>
          <div class="campaign-body">
            <h5>#BanSingleUsePlastics</h5>
            <p>Petitioning the Ghana Parliament to enact a comprehensive ban on single-use plastic bags, straws, and cutlery.</p>
            <div class="campaign-progress">
              <div class="d-flex justify-content-between mb-1">
                <small>Signatures</small>
                <small><strong><?= number_format((int)$petitionCount) ?></strong> / 10,000</small>
              </div>
              <div class="progress" style="height:8px;">
                <div class="progress-bar bg-success" style="width:<?= min(100, ($petitionCount/10000)*100) ?>%"></div>
              </div>
            </div>
            <a href="<?= SITE_URL ?>/pages/what_to_do.php#petition" class="btn btn-green-3d btn-sm mt-3 w-100">Sign Petition</a>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="campaign-card-3d">
          <div class="campaign-img-wrap">
            <img src="https://images.unsplash.com/photo-1621451537084-482c73073a0f?w=500"
                 alt="Ocean Cleanup Drive Campaign" loading="lazy">
            <div class="campaign-category">Clean-Up</div>
          </div>
          <div class="campaign-body">
            <h5>#CleanOurCoast</h5>
            <p>Monthly shoreline cleanup drives across the Volta Region and Greater Accra in partnership with local communities.</p>
            <div class="campaign-progress">
              <div class="d-flex justify-content-between mb-1">
                <small>Volunteers Recruited</small>
                <small><strong>340</strong> / 500</small>
              </div>
              <div class="progress" style="height:8px;">
                <div class="progress-bar bg-info" style="width:68%"></div>
              </div>
            </div>
            <a href="<?= SITE_URL ?>/pages/how_to_help.php" class="btn btn-green-3d btn-sm mt-3 w-100">Volunteer Now</a>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="campaign-card-3d">
          <div class="campaign-img-wrap">
            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500"
                 alt="Plastic-Free Schools Campaign" loading="lazy">
            <div class="campaign-category">Education</div>
          </div>
          <div class="campaign-body">
            <h5>#PlasticFreeSchools</h5>
            <p>Working with 50+ schools in the Greater Accra Region to eliminate single-use plastics from canteens and classrooms.</p>
            <div class="campaign-progress">
              <div class="d-flex justify-content-between mb-1">
                <small>Schools Reached</small>
                <small><strong>32</strong> / 50</small>
              </div>
              <div class="progress" style="height:8px;">
                <div class="progress-bar bg-warning" style="width:64%"></div>
              </div>
            </div>
            <a href="<?= SITE_URL ?>/pages/campaigns.php" class="btn btn-green-3d btn-sm mt-3 w-100">Learn More</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== DONATE CTA BANNER ===== -->
<section class="cta-banner-3d">
  <div class="container">
    <div class="row align-items-center text-center text-lg-start">
      <div class="col-lg-8">
        <h2 class="cta-title">Your Donation Makes a Real Difference</h2>
        <p class="cta-subtitle">GHS 10 funds a school workshop. GHS 50 cleans a stretch of beach. Every cedi counts.</p>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <a href="<?= SITE_URL ?>/donations/donate.php" class="btn btn-cta-3d btn-lg">
          <i class="bi bi-heart-fill me-2"></i>Donate Now
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ===== LATEST NEWS PREVIEW ===== -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="row align-items-center mb-5">
      <div class="col">
        <span class="section-badge">Stay Informed</span>
        <h2 class="section-title mb-0">Latest on <span class="text-success">Plastic</span></h2>
      </div>
      <div class="col-auto">
        <a href="<?= SITE_URL ?>/pages/latest.php" class="btn btn-outline-success">
          All News <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
    <div class="row g-4">
      <?php
      $posts = $db->query("SELECT * FROM blog_posts ORDER BY published_at DESC LIMIT 3")->fetchAll();
      foreach ($posts as $post):
      ?>
      <div class="col-lg-4 col-md-6">
        <article class="blog-card-3d">
          <?php if ($post['image_url']): ?>
          <div class="blog-card-img-wrap">
            <img src="<?= htmlspecialchars($post['image_url']) ?>"
                 alt="<?= htmlspecialchars($post['title']) ?>" loading="lazy">
            <span class="blog-category"><?= htmlspecialchars($post['category']) ?></span>
          </div>
          <?php endif; ?>
          <div class="blog-card-body">
            <h5><a href="<?= SITE_URL ?>/pages/latest.php?slug=<?= urlencode($post['slug']) ?>">
              <?= htmlspecialchars($post['title']) ?></a></h5>
            <p><?= htmlspecialchars($post['excerpt']) ?></p>
            <div class="blog-card-footer">
              <span><i class="bi bi-person me-1"></i><?= htmlspecialchars($post['author']) ?></span>
              <span><i class="bi bi-calendar me-1"></i><?= date('M j, Y', strtotime($post['published_at'])) ?></span>
            </div>
          </div>
        </article>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== HOW YOU CAN HELP TEASER ===== -->
<section class="section-pad bg-dark-green text-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge-light">Take Action Today</span>
      <h2 class="section-title-white">How <span class="text-success-light">You</span> Can Help</h2>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-lg-3 col-md-6">
        <div class="help-card-3d">
          <i class="bi bi-people-fill help-icon"></i>
          <h5>Volunteer</h5>
          <p>Join our cleanup drives and awareness campaigns across Ghana.</p>
          <a href="<?= SITE_URL ?>/pages/how_to_help.php#volunteer" class="btn btn-outline-light btn-sm">Get Started</a>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="help-card-3d">
          <i class="bi bi-heart-fill help-icon"></i>
          <h5>Donate</h5>
          <p>Fund our campaigns, workshops, and cleanup equipment.</p>
          <a href="<?= SITE_URL ?>/donations/donate.php" class="btn btn-outline-light btn-sm">Donate Now</a>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="help-card-3d">
          <i class="bi bi-share-fill help-icon"></i>
          <h5>Spread the Word</h5>
          <p>Share our message on social media and bring others on board.</p>
          <!-- SOCIAL LINK: Sharing buttons - update URLs with your actual social profiles -->
          <a href="#" class="btn btn-outline-light btn-sm" target="_blank">Share Now</a>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="help-card-3d">
          <i class="bi bi-pen-fill help-icon"></i>
          <h5>Sign Petitions</h5>
          <p>Add your voice to demand government action on plastic waste.</p>
          <a href="<?= SITE_URL ?>/pages/what_to_do.php#petition" class="btn btn-outline-light btn-sm">Sign Now</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== SOCIAL MEDIA SECTION ===== -->
<section class="section-pad bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Stay Connected</span>
      <h2 class="section-title">Follow Us on <span class="text-success">Social Media</span></h2>
      <p class="text-muted">Get real-time updates, campaign news, and environmental tips across our platforms.</p>
    </div>
    <div class="row g-4 justify-content-center">
      <!-- SOCIAL LINK: FACEBOOK -->
      <div class="col-lg-2 col-md-4 col-6">
        <a href="#" class="social-platform-card facebook-card" target="_blank" rel="noopener"
           title="Follow PlasticPollutions on Facebook">
          <!-- SOCIAL LINK: FACEBOOK – replace href="#" above with your Facebook page URL -->
          <i class="fab fa-facebook-f social-platform-icon"></i>
          <span>Facebook</span>
          <small>@PlasticPollutionsGH</small>
        </a>
      </div>
      <!-- SOCIAL LINK: TWITTER/X -->
      <div class="col-lg-2 col-md-4 col-6">
        <a href="#" class="social-platform-card twitter-card" target="_blank" rel="noopener"
           title="Follow PlasticPollutions on X (Twitter)">
          <!-- SOCIAL LINK: TWITTER/X – replace href="#" above with your Twitter/X profile URL -->
          <i class="fab fa-x-twitter social-platform-icon"></i>
          <span>X (Twitter)</span>
          <small>@PlasticPollutionsGH</small>
        </a>
      </div>
      <!-- SOCIAL LINK: INSTAGRAM -->
      <div class="col-lg-2 col-md-4 col-6">
        <a href="#" class="social-platform-card instagram-card" target="_blank" rel="noopener"
           title="Follow PlasticPollutions on Instagram">
          <!-- SOCIAL LINK: INSTAGRAM – replace href="#" above with your Instagram profile URL -->
          <i class="fab fa-instagram social-platform-icon"></i>
          <span>Instagram</span>
          <small>@PlasticPollutionsGH</small>
        </a>
      </div>
      <!-- SOCIAL LINK: YOUTUBE -->
      <div class="col-lg-2 col-md-4 col-6">
        <a href="#" class="social-platform-card youtube-card" target="_blank" rel="noopener"
           title="Subscribe to PlasticPollutions on YouTube">
          <!-- SOCIAL LINK: YOUTUBE – replace href="#" above with your YouTube channel URL -->
          <i class="fab fa-youtube social-platform-icon"></i>
          <span>YouTube</span>
          <small>PlasticPollutions GH</small>
        </a>
      </div>
      <!-- SOCIAL LINK: TIKTOK -->
      <div class="col-lg-2 col-md-4 col-6">
        <a href="#" class="social-platform-card tiktok-card" target="_blank" rel="noopener"
           title="Follow PlasticPollutions on TikTok">
          <!-- SOCIAL LINK: TIKTOK – replace href="#" above with your TikTok profile URL -->
          <i class="fab fa-tiktok social-platform-icon"></i>
          <span>TikTok</span>
          <small>@PlasticPollutionsGH</small>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Load page-specific JS -->
<script src="<?= SITE_URL ?>/js/slider.js"></script>
<script src="<?= SITE_URL ?>/js/counter.js"></script>
<script src="<?= SITE_URL ?>/js/signup_popup.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
