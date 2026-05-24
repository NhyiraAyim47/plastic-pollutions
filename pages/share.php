<?php
// pages/share.php  –  Share & Inspire page

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/visitor_counter.php';
trackVisitor('/share');

$pageTitle    = 'Share & Inspire – Spread the Word About Plastic Pollution';
$pageDesc     = 'Help fight plastic pollution by sharing our message on social media, in your workplace, school, and community. Every share creates more awareness and drives change.';
$pageKeywords = 'share plastic pollution, spread awareness, social media campaign, inspire others, Ghana environment, plastic pollution awareness';

$db = getDB();
$petitionCount = (int)$db->query("SELECT COUNT(*) FROM petitions")->fetchColumn();
$memberCount   = (int)$db->query("SELECT COUNT(*) FROM users WHERE is_verified=1")->fetchColumn();

// Current page full URL for sharing
$shareUrl     = SITE_URL . '/pages/home.php';
$shareText    = urlencode('I just joined the fight against plastic pollution in Ghana with PlasticPollutions at Pentecost University. Join me! 🌿 #EndPlasticPollution #Ghana #PlasticPollutions');
$shareUrlEnc  = urlencode($shareUrl);

$extraCss = [SITE_URL . '/css/share.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero -->
<div class="page-hero bg-dark-green text-white py-5">
  <div class="container text-center">
    <span class="section-badge-light">Be the Change</span>
    <h1 class="section-title-white">Share &amp; <span class="text-success-light">Inspire</span></h1>
    <p class="text-white-50 mx-auto" style="max-width:580px;">
      You don't need a megaphone to make noise. A single share on social media can reach hundreds of people and spark a chain reaction of environmental action.
    </p>
    <div class="mt-4">
      <span class="badge bg-success fs-6 me-2">🌿 <?= number_format($memberCount) ?>+ Members</span>
      <span class="badge bg-warning text-dark fs-6">✍️ <?= number_format($petitionCount) ?>+ Petition Signatures</span>
    </div>
  </div>
</div>

<!-- Why Sharing Matters -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">The Power of One Share</span>
      <h2 class="section-title">Why Your Voice <span class="text-success">Matters</span></h2>
      <p class="text-muted mx-auto" style="max-width:580px;">
        Awareness is the first step to action. When you share, you don't just inform — you inspire others to act too.
      </p>
    </div>
    <div class="row g-4 justify-content-center">
      <?php foreach ([
        ['📱','1 Post','Can reach 200–500 people in your network alone — all potential allies in the fight against plastic.'],
        ['🔁','1 Share','If just 10 of your followers reshare, your message reaches 2,000–5,000 people organically.'],
        ['💬','1 Conversation','Talking to a friend, colleague, or family member about plastic pollution is more persuasive than any advert.'],
        ['📧','1 Email','Forwarding our newsletter or campaign update to your workplace or school group can inspire collective action.'],
      ] as $why): ?>
      <div class="col-lg-3 col-md-6">
        <div class="share-why-card reveal">
          <div class="share-why-icon"><?= $why[0] ?></div>
          <h5><?= $why[1] ?></h5>
          <p><?= $why[2] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Direct Social Share Buttons -->
<section class="section-pad bg-light-green">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Share Right Now</span>
      <h2 class="section-title">Share on <span class="text-success">Social Media</span></h2>
      <p class="text-muted mx-auto" style="max-width:520px;">
        Click any button below to instantly share the PlasticPollutions message on your favourite platform.
      </p>
    </div>

    <div class="row g-4 justify-content-center">

      <!-- Facebook -->
      <div class="col-lg-4 col-md-6">
        <div class="share-platform-card">
          <div class="share-platform-header facebook-bg">
            <i class="fab fa-facebook-f share-platform-icon"></i>
            <h5>Share on Facebook</h5>
          </div>
          <div class="share-platform-body">
            <p>Post to your Facebook timeline and reach your entire friend network.</p>
            <!-- SOCIAL LINK: FACEBOOK SHARE – shares the PlasticPollutions home page -->
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrlEnc ?>"
               target="_blank" rel="noopener"
               class="btn share-btn facebook-btn w-100">
              <i class="fab fa-facebook-f me-2"></i>Share on Facebook
            </a>
            <!-- SOCIAL LINK: FACEBOOK PAGE – replace href="#" with your Facebook page URL -->
            <a href="https://www.facebook.com/profile.php?id=100076365710478" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm w-100 mt-2">
              <i class="fab fa-facebook-f me-2"></i>Follow Our Page
              <!-- SOCIAL LINK: FACEBOOK PAGE FOLLOW BUTTON -->
            </a>
          </div>
        </div>
      </div>

      <!-- Twitter / X -->
      <div class="col-lg-4 col-md-6">
        <div class="share-platform-card">
          <div class="share-platform-header twitter-bg">
            <i class="fab fa-x-twitter share-platform-icon"></i>
            <h5>Post on X (Twitter)</h5>
          </div>
          <div class="share-platform-body">
            <p>Tweet our message and join the global conversation on plastic pollution.</p>
            <!-- SOCIAL LINK: TWITTER/X SHARE – shares a pre-written tweet -->
            <a href="https://twitter.com/intent/tweet?text=<?= $shareText ?>&url=<?= $shareUrlEnc ?>"
               target="_blank" rel="noopener"
               class="btn share-btn twitter-btn w-100">
              <i class="fab fa-x-twitter me-2"></i>Post on X
            </a>
            <!-- SOCIAL LINK: TWITTER/X FOLLOW BUTTON – replace href="#" with your Twitter/X profile URL -->
            <a href="https://x.com/nhyirah_ayim" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm w-100 mt-2">
              <i class="fab fa-x-twitter me-2"></i>Follow @PlasticPollutionsGH
              <!-- SOCIAL LINK: TWITTER/X FOLLOW -->
            </a>
          </div>
        </div>
      </div>

      <!-- WhatsApp -->
      <div class="col-lg-4 col-md-6">
        <div class="share-platform-card">
          <div class="share-platform-header whatsapp-bg">
            <i class="fab fa-whatsapp share-platform-icon"></i>
            <h5>Share on WhatsApp</h5>
          </div>
          <div class="share-platform-body">
            <p>Send to your WhatsApp contacts and groups — perfect for community mobilisation.</p>
            <a href="https://wa.me/?text=<?= $shareText ?>%20<?= $shareUrlEnc ?>"
               target="_blank" rel="noopener"
               class="btn share-btn whatsapp-btn w-100">
              <i class="fab fa-whatsapp me-2"></i>Share on WhatsApp
            </a>
            <a href="https://wa.me/?text=<?= urlencode('Join me in signing the petition to ban single-use plastics in Ghana! 🌿 Sign here: ' . SITE_URL . '/pages/what_to_do.php') ?>"
               target="_blank" rel="noopener"
               class="btn btn-outline-secondary btn-sm w-100 mt-2">
              <i class="fab fa-whatsapp me-2"></i>Share the Petition
            </a>
          </div>
        </div>
      </div>

      <!-- Instagram -->
      <div class="col-lg-4 col-md-6">
        <div class="share-platform-card">
          <div class="share-platform-header instagram-bg">
            <i class="fab fa-instagram share-platform-icon"></i>
            <h5>Share on Instagram</h5>
          </div>
          <div class="share-platform-body">
            <p>Copy our caption and share it with your photo to spread our message visually.</p>
            <button class="btn share-btn instagram-btn w-100" onclick="copyCaption()">
              <i class="fab fa-instagram me-2"></i>Copy Caption & Hashtags
            </button>
            <!-- SOCIAL LINK: INSTAGRAM FOLLOW BUTTON – replace href="#" with your Instagram profile URL -->
            <a href="#" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm w-100 mt-2">
              <i class="fab fa-instagram me-2"></i>Follow @PlasticPollutionsGH
              <!-- SOCIAL LINK: INSTAGRAM FOLLOW -->
            </a>
          </div>
        </div>
      </div>

      <!-- YouTube -->
      <div class="col-lg-4 col-md-6">
  <div class="share-platform-card">
    <div class="share-platform-header youtube-bg">
      <i class="fab fa-youtube share-platform-icon"></i>
      <h5>Watch on YouTube</h5>
    </div>
    <div class="share-platform-body">
      <p>Watch our environmental documentaries and campaign videos, and subscribe to stay updated on our latest content.</p>
      <a href="https://www.youtube.com/share?url=<?= $shareUrlEnc ?>"
         target="_blank" rel="noopener"
         class="btn share-btn youtube-btn w-100">
        <i class="fab fa-youtube me-2"></i>Share on YouTube
      </a>
      <a href="https://www.youtube.com/@NhyirahAyim" target="_blank" rel="noopener"
         class="btn btn-outline-secondary btn-sm w-100 mt-2">
        <i class="fab fa-youtube me-2"></i>Subscribe to Our Channel
      </a>
    </div>
  </div>
</div>

      <!-- TikTok -->
      <div class="col-lg-4 col-md-6">
        <div class="share-platform-card">
          <div class="share-platform-header tiktok-bg">
            <i class="fab fa-tiktok share-platform-icon"></i>
            <h5>Share on TikTok</h5>
          </div>
          <div class="share-platform-body">
            <p>Create a short video about plastic pollution and tag us. TikTok spreads awareness fast.</p>
            <button class="btn share-btn tiktok-btn w-100" onclick="copyTikTokCaption()">
              <i class="fab fa-tiktok me-2"></i>Copy TikTok Caption
            </button>
            <!-- SOCIAL LINK: TIKTOK FOLLOW BUTTON – replace href="#" with your TikTok profile URL -->
            <a href="https://www.tiktok.com/@nhyirah.ayim" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm w-100 mt-2">
              <i class="fab fa-tiktok me-2"></i>Follow @PlasticPollutionsGH
              <!-- SOCIAL LINK: TIKTOK FOLLOW -->
            </a>
          </div>
        </div>
      </div>

    </div>

    <!-- Copy Link -->
    <div class="copy-link-box mt-5">
      <h5 class="fw-bold mb-3"><i class="bi bi-link-45deg me-2 text-success"></i>Or Copy Our Link</h5>
      <div class="input-group">
        <input type="text" class="form-control form-control-3d" id="copyLinkInput"
               value="<?= SITE_URL ?>/pages/home.php" readonly>
        <button class="btn btn-green-3d" onclick="copyLink()" id="copyLinkBtn">
          <i class="bi bi-clipboard me-2"></i>Copy Link
        </button>
      </div>
    </div>
  </div>
</section>

<!-- Ready-Made Captions -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Ready-Made Content</span>
      <h2 class="section-title">Copy &amp; Paste <span class="text-success">Captions</span></h2>
      <p class="text-muted mx-auto" style="max-width:520px;">
        Not sure what to write? Use one of these ready-made captions for your posts.
      </p>
    </div>
    <div class="row g-4">
      <?php
$captions = [
  [
    'platform' => 'General / Facebook',
    'icon'     => 'fab fa-facebook-f',
    'color'    => '#1877f2',
    'text'     => "🌊 Did you know less than 5% of plastic waste in Ghana is recycled? The rest ends up in our rivers, oceans, and communities.\n\nI've joined PlasticPollutions at Pentecost University to help change that. Sign our petition, volunteer, or donate today.\n\n👉 " . SITE_URL . "\n\n#EndPlasticPollution #Ghana #PlasticFree #Sustainability",
  ],
  [
    'platform' => 'Twitter / X',
    'icon'     => 'fab fa-x-twitter',
    'color'    => '#000',
    'text'     => "🚨 Only 5% of plastic is recycled in Ghana. 8 million tonnes enter our oceans every year.\n\nI just signed the @PlasticPollutionsGH petition demanding action. Join me 👇\n\n" . SITE_URL . "/pages/what_to_do.php\n\n#PlasticPollution #Ghana #EndPlasticNow",
  ],
  [
    'platform' => 'WhatsApp / SMS',
    'icon'     => 'fab fa-whatsapp',
    'color'    => '#25d366',
    'text'     => "Hi! 👋 I wanted to share something important with you.\n\nPlasticPollutions is a group at Pentecost University fighting to reduce plastic waste in Ghana. They have a petition, cleanup drives, and school campaigns.\n\nPlease sign the petition here: " . SITE_URL . "/pages/what_to_do.php\n\nEvery signature counts! 🌿",
  ],
  [
    'platform' => 'Instagram / TikTok',
    'icon'     => 'fab fa-instagram',
    'color'    => '#e1306c',
    'text'     => "🌿 Joining the fight against plastic pollution in Ghana 💚\n\nLess than 5% of plastic in Ghana gets recycled. Together we can change that.\n\n✅ Signed the petition\n✅ Joined PlasticPollutions\n✅ Spreading the word\n\nLink in bio to join me! 👆\n\n#PlasticPollution #Ghana #EndPlasticNow #PlasticFree #Sustainability #PentecostUniversity #CleanGhana #OceanConservation #GoGreen",
  ],
  [
    'platform' => 'YouTube',
    'icon'     => 'fab fa-youtube',
    'color'    => '#ff0000',
    'text'     => "🎥 We just uploaded a new video about plastic pollution in Ghana!\n\nDid you know less than 5% of plastic is recycled here? Our latest video shows what we are doing about it — cleanup drives, school campaigns, and policy advocacy.\n\n👇 Watch, like, and subscribe to stay updated:\nhttps://www.youtube.com/@NhyirahAyim\n\n#PlasticPollution #Ghana #EndPlasticNow #PlasticFree #PentecostUniversity #CleanGhana #OceanConservation #YouTube",
  ],
];
$loop = -1;
foreach ($captions as $cap):
$loop++;
?>
<div class="col-lg-6">
  <div class="caption-card">
    <div class="caption-card-header" style="border-left: 4px solid <?= $cap['color'] ?>">
      <i class="<?= $cap['icon'] ?> me-2" style="color:<?= $cap['color'] ?>"></i>
      <strong><?= $cap['platform'] ?></strong>
    </div>
    <pre class="caption-text" id="caption<?= $loop ?>"><?= htmlspecialchars($cap['text']) ?></pre>
    <button class="btn btn-green-3d btn-sm w-100 mt-2"
            onclick="copyCaption('caption<?= $loop ?>', this)">
      <i class="bi bi-clipboard me-2"></i>Copy Caption
    </button>
  </div>
</div>
<?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Share in Your Community -->
<section class="section-pad bg-dark-green text-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge-light">Beyond Social Media</span>
      <h2 class="section-title-white">Inspire Your <span class="text-success-light">Community</span></h2>
    </div>
    <div class="row g-4">
      <?php foreach ([
        ['🏫','In Your School','Talk to your headmaster about joining #PlasticFreeSchools. Organise a class discussion on plastic pollution. Start a green club.'],
        ['🏢','In Your Workplace','Propose a single-use plastic ban at your office. Share our campaigns in your company group chat. Suggest a team volunteer day.'],
        ['⛪','In Your Community','Speak to your community leader about a local cleanup drive. Share printed flyers at your place of worship or community centre.'],
        ['👨‍👩‍👧','With Your Family','Make plastic-free choices at home together. Explain to children why single-use plastics are harmful. Start a household recycling routine.'],
      ] as $comm): ?>
      <div class="col-lg-3 col-md-6">
        <div class="community-card">
          <div class="community-emoji"><?= $comm[0] ?></div>
          <h5><?= $comm[1] ?></h5>
          <p><?= $comm[2] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Final CTA -->
    <div class="text-center mt-5 pt-3">
      <h4 class="text-white mb-3">Ready to do more than just share?</h4>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="<?= SITE_URL ?>/pages/how_to_help.php#volunteer" class="btn btn-hero-primary btn-lg">
          <i class="bi bi-people me-2"></i>Volunteer With Us
        </a>
        <a href="<?= SITE_URL ?>/donations/donate.php" class="btn btn-hero-outline btn-lg">
          <i class="bi bi-heart me-2"></i>Make a Donation
        </a>
        <a href="<?= SITE_URL ?>/pages/what_to_do.php#petition" class="btn btn-hero-outline btn-lg">
          <i class="bi bi-pen me-2"></i>Sign the Petition
        </a>
      </div>
    </div>
  </div>
</section>

<script src="<?= SITE_URL ?>/js/share.js" defer></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
