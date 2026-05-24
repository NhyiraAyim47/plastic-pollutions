<?php
// includes/header.php  –  Global site header, nav & meta tags
// Variables expected from calling page:
//   $pageTitle    (string) – specific page title
//   $pageDesc     (string) – meta description
//   $pageKeywords (string) – meta keywords
//   $ogImage      (string) – Open Graph image URL (optional)

if (!isset($pageTitle))    $pageTitle    = 'PlasticPollutions | Pentecost University';
if (!isset($pageDesc))     $pageDesc     = 'PlasticPollutions is an environmental action group at Pentecost University fighting to reduce plastic waste and protect Ghana\'s oceans, wildlife, and communities.';
if (!isset($pageKeywords)) $pageKeywords = 'plastic pollution, environmental action, recycling Ghana, Pentecost University, reduce plastic waste, ocean conservation';
if (!isset($ogImage))      $ogImage      = SITE_URL . '/assets/images/og-cover.jpg';

$fullTitle = $pageTitle . ' | PlasticPollutions';
$currentPage = basename($_SERVER['PHP_SELF']);
$isLoggedIn  = isset($_SESSION['user_id']);
$userName    = $isLoggedIn ? ($_SESSION['user_name'] ?? 'User') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- ===== SEO Meta Tags ===== -->
  <title><?= htmlspecialchars($fullTitle) ?></title>
  <meta name="description"   content="<?= htmlspecialchars($pageDesc) ?>">
  <meta name="keywords"      content="<?= htmlspecialchars($pageKeywords) ?>">
  <meta name="author"        content="PlasticPollutions – Pentecost University">
  <meta name="robots"        content="index, follow">
  <link rel="canonical"      href="<?= SITE_URL . '/' . $currentPage ?>">

  <!-- Open Graph / Social Sharing -->
  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= SITE_URL . '/' . $currentPage ?>">
  <meta property="og:title"       content="<?= htmlspecialchars($fullTitle) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($pageDesc) ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($ogImage) ?>">

  <!-- Twitter Card -->
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="<?= htmlspecialchars($fullTitle) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($pageDesc) ?>">
  <meta name="twitter:image"       content="<?= htmlspecialchars($ogImage) ?>">

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= SITE_URL ?>/assets/images/favicon.svg">

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Site CSS -->
  <link rel="stylesheet" href="<?= SITE_URL ?>/css/main.css">
  <link rel="stylesheet" href="<?= SITE_URL ?>/css/animations.css">

  <!-- Structured Data (SEO) -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "PlasticPollutions",
    "url": "<?= SITE_URL ?>",
    "logo": "<?= SITE_URL ?>/assets/images/logo.png",
    "description": "<?= htmlspecialchars($pageDesc) ?>",
    "foundingOrganization": {
      "@type": "EducationalOrganization",
      "name": "Pentecost University",
      "address": { "@type": "PostalAddress", "addressCountry": "GH" }
    },
    "sameAs": [
      "https://facebook.com/YOUR_FACEBOOK_PAGE",
      "https://twitter.com/YOUR_TWITTER_HANDLE",
      "https://instagram.com/YOUR_INSTAGRAM_HANDLE"
    ]
  }
  </script>
<!-- Page-specific CSS -->
<?php if (!empty($extraCss)): foreach ($extraCss as $css): ?>
  <link rel="stylesheet" href="<?= $css ?>">
<?php endforeach; endif; ?>
</head>
<body>

<!-- ===== COOKIE NOTIFICATION (rendered by JS) ===== -->
<div id="cookieBanner" class="cookie-banner d-none">
  <div class="d-flex align-items-center gap-3 flex-wrap">
    <i class="bi bi-cookie fs-4 text-warning"></i>
    <p class="mb-0 flex-grow-1">
      We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.
    </p>
    <div class="d-flex gap-2">
      <a href="<?= SITE_URL ?>/pages/privacy.php" class="btn btn-sm btn-outline-light">Learn More</a>
      <button id="acceptCookies" class="btn btn-sm btn-success">Accept</button>
    </div>
  </div>
</div>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-3d sticky-top" id="mainNav">
  <div class="container">
    <!-- Brand -->
    <a class="navbar-brand brand-3d" href="<?= SITE_URL ?>/pages/home.php">
      <span class="brand-icon">🌿</span>
      <span class="brand-text">Plastic<span class="brand-accent">Pollutions</span></span>
    </a>

    <!-- Toggler -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Nav Links -->
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-1">

        <li class="nav-item">
          <a class="nav-link nav-link-3d <?= ($currentPage === 'home.php') ? 'active' : '' ?>"
             href="<?= SITE_URL ?>/pages/home.php">Home</a>
        </li>

        <!-- About Plastic Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link nav-link-3d dropdown-toggle" href="#" data-bs-toggle="dropdown">
            About Plastic
          </a>
          <ul class="dropdown-menu dropdown-menu-3d">
            <li><a class="dropdown-item" href="<?= SITE_URL ?>/pages/what_to_do.php">
              <i class="bi bi-recycle me-2 text-success"></i>What To Do About Plastic</a></li>
            <li><a class="dropdown-item" href="<?= SITE_URL ?>/pages/latest.php">
              <i class="bi bi-newspaper me-2 text-info"></i>Latest on Plastic</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?= SITE_URL ?>/pages/strategy.php">
              <i class="bi bi-diagram-3 me-2 text-warning"></i>Our Strategy</a></li>
          </ul>
        </li>

        <!-- Campaigns -->
        <li class="nav-item">
          <a class="nav-link nav-link-3d <?= ($currentPage === 'campaigns.php') ? 'active' : '' ?>"
             href="<?= SITE_URL ?>/pages/campaigns.php">Campaigns</a>
        </li>

        <!-- Get Involved Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link nav-link-3d dropdown-toggle" href="#" data-bs-toggle="dropdown">
            Get Involved
          </a>
          <ul class="dropdown-menu dropdown-menu-3d">
            <li><a class="dropdown-item" href="<?= SITE_URL ?>/pages/how_to_help.php">
              <i class="bi bi-heart me-2 text-danger"></i>How You Can Help</a></li>
            <li><a class="dropdown-item" href="<?= SITE_URL ?>/donations/donate.php">
              <i class="bi bi-currency-dollar me-2 text-success"></i>Donate</a></li>
            <li><a class="dropdown-item" href="<?= SITE_URL ?>/pages/what_to_do.php#petition">
              <i class="bi bi-pen me-2 text-primary"></i>Sign a Petition</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-link-3d <?= ($currentPage === 'developers.php') ? 'active' : '' ?>"
             href="<?= SITE_URL ?>/pages/developers.php">Our Team</a>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-link-3d <?= ($currentPage === 'contact.php') ? 'active' : '' ?>"
             href="<?= SITE_URL ?>/pages/contact.php">Contact</a>
        </li>

        <!-- Auth buttons -->
        <?php if ($isLoggedIn): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle btn-nav-user" href="#" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($userName) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-3d dropdown-menu-end">
              <li><a class="dropdown-item" href="<?= SITE_URL ?>/dashboard/index.php">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
              <li><a class="dropdown-item" href="<?= SITE_URL ?>/donations/history.php">
                <i class="bi bi-clock-history me-2"></i>Donations</a></li>
              <li><a class="dropdown-item" href="<?= SITE_URL ?>/dashboard/update_profile.php">
                <i class="bi bi-pencil-square me-2"></i>Edit Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?= SITE_URL ?>/auth/logout.php">
                <i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link nav-link-3d" href="<?= SITE_URL ?>/auth/login.php">Login</a>
          </li>
          <li class="nav-item">
            <button class="btn btn-nav-signup" data-bs-toggle="modal" data-bs-target="#signUpModal">
              Sign Up Now
            </button>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<!-- ===== SIGN UP MODAL (Pop-up) ===== -->
<?php if (!$isLoggedIn): ?>
<div class="modal fade" id="signUpModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-3d">
      <div class="modal-header modal-header-green">
        <div>
          <h5 class="modal-title mb-0">🌿 Join PlasticPollutions</h5>
          <small class="text-light opacity-75">Make a difference for our planet</small>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="quickSignupForm" novalidate data-site-url="<?= SITE_URL ?>">
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-3d" name="first_name" placeholder="e.g. Kwame" required minlength="2">
              <div class="invalid-feedback">Please enter your first name (min 2 chars).</div>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-3d" name="last_name" placeholder="e.g. Asante" required minlength="2">
              <div class="invalid-feedback">Please enter your last name.</div>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
              <input type="email" class="form-control form-control-3d" name="email" placeholder="you@example.com" required>
              <div class="invalid-feedback">Please enter a valid email.</div>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" class="form-control form-control-3d" name="password"
                       placeholder="Min 8 chars, 1 uppercase, 1 number" required minlength="8"
                       pattern="^(?=.*[A-Z])(?=.*\d).{8,}$" id="modalPwd">
                <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="modalPwd">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
              <div class="invalid-feedback">Password must be 8+ chars with 1 uppercase letter and 1 number.</div>
              <div class="password-strength mt-1" id="pwdStrengthBar"></div>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
              <input type="password" class="form-control form-control-3d" name="confirm_password"
                     placeholder="Re-enter password" required id="modalConfirmPwd">
              <div class="invalid-feedback">Passwords do not match.</div>
            </div>
          </div>
          <div id="quickSignupAlert" class="mt-3"></div>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-green-3d btn-lg" id="quickSignupBtn">
              <span class="btn-text"><i class="bi bi-person-plus me-2"></i>Create Account</span>
              <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm me-2"></span>Creating...</span>
            </button>
          </div>
          <p class="text-center mt-3 mb-0 small text-muted">
            Already have an account? <a href="<?= SITE_URL ?>/auth/login.php" class="text-success fw-semibold">Login here</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<main id="mainContent">
