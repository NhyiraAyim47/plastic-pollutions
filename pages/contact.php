<?php
// pages/contact.php  –  Contact Us page

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/visitor_counter.php';
trackVisitor('/contact');

$pageTitle    = 'Contact Us';
$pageDesc     = 'Get in touch with PlasticPollutions at Pentecost University. Send us a message, report plastic pollution, or enquire about volunteering and partnerships.';
$pageKeywords = 'contact PlasticPollutions, Pentecost University, plastic pollution report, volunteer enquiry';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    require_once __DIR__ . '/../includes/mailer.php';
    $db = getDB();

    $name    = trim(filter_input(INPUT_POST, 'name',    FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $email   = trim(filter_input(INPUT_POST, 'email',   FILTER_SANITIZE_EMAIL) ?? '');
    $subject = trim(filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

    $errors = [];
    if (strlen($name) < 2)     $errors[] = 'Name must be at least 2 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
    if (strlen($subject) < 4)  $errors[] = 'Please enter a subject.';
    if (strlen($message) < 10) $errors[] = 'Message must be at least 10 characters.';

    if (!empty($errors)) { echo json_encode(['success'=>false,'errors'=>$errors]); exit; }

    $db->prepare("INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)")
       ->execute([$name, $email, $subject, $message]);

    // Notify admin
    sendContactNotification($name, $email, $subject, $message);

    echo json_encode(['success'=>true,'message'=>'Thank you, ' . htmlspecialchars($name) . '! Your message has been sent. We will get back to you within 24–48 hours.']);
    exit;
}

$extraCss = [SITE_URL . '/css/contact.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero -->
<div class="page-hero bg-dark-green text-white py-5">
  <div class="container text-center">
    <span class="section-badge-light">We'd Love to Hear From You</span>
    <h1 class="section-title-white">Contact <span class="text-success-light">Us</span></h1>
    <p class="text-white-50 mx-auto" style="max-width:520px;">Report plastic pollution, ask about volunteering, request a speaker, or just say hello.</p>
  </div>
</div>

<section class="section-pad">
  <div class="container">
    <div class="row g-5">

      <!-- Contact Info -->
      <div class="col-lg-4">
        <div class="contact-info-card">
          <h5 class="fw-bold mb-4">Get in Touch</h5>
          <div class="contact-detail">
            <div class="contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <div>
              <strong>Location</strong>
              <p>Faculty of Engineering Science &amp; Computing<br>Pentecost University, Accra, Ghana</p>
            </div>
          </div>
          <div class="contact-detail">
            <div class="contact-icon"><i class="bi bi-envelope-fill"></i></div>
            <div>
              <strong>Email</strong>
              <p><a href="https://mail.google.com/mail/?view=cm&to=nhyirahayim@gmail.com">info@plasticpollutions.edu.gh</a></p>
            </div>
          </div>
          <div class="contact-detail">
            <div class="contact-icon"><i class="bi bi-clock-fill"></i></div>
            <div>
              <strong>Response Time</strong>
              <p>Typically within 24–48 hours on weekdays</p>
            </div>
          </div>
          <!-- Social Links -->
          <div class="mt-4">
            <h6 class="fw-semibold mb-3">Follow Us</h6>
            <div class="social-links">
              <a href="https://www.facebook.com/profile.php?id=100076365710478" class="social-btn facebook" target="_blank" rel="noopener"
               title="Follow us on Facebook" aria-label="PlasticPollutions on Facebook">
              <!-- SOCIAL LINK: FACEBOOK – replace href="#" with your Facebook page URL -->
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://x.com/nhyirah_ayim" class="social-btn twitter" target="_blank" rel="noopener"
               title="Follow us on X (Twitter)" aria-label="PlasticPollutions on Twitter/X">
              <!-- SOCIAL LINK: TWITTER/X – replace href="#" with your Twitter/X profile URL -->
              <i class="fab fa-x-twitter"></i>
            </a>
            <a href="https://www.youtube.com/@NhyirahAyim" class="social-btn youtube" target="_blank" rel="noopener"
               title="Watch us on YouTube" aria-label="PlasticPollutions on YouTube">
              <!-- SOCIAL LINK: YOUTUBE – replace href="#" with your YouTube channel URL -->
              <i class="fab fa-youtube"></i>
            </a>
            <a href="https://www.tiktok.com/@nhyirah.ayim" class="social-btn tiktok" target="_blank" rel="noopener"
               title="Follow us on TikTok" aria-label="PlasticPollutions on TikTok">
              <!-- SOCIAL LINK: TIKTOK – replace href="#" with your TikTok profile URL -->
              <i class="fab fa-tiktok"></i>
            </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="col-lg-8">
        <div class="auth-card-3d">
          <div class="auth-card-header">
            <h3>📬 Send Us a Message</h3>
            <p>We read every message and respond promptly</p>
          </div>
          <div class="auth-card-body">
            <form id="contactForm" novalidate data-site-url="<?= SITE_URL ?>">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control form-control-3d" name="name"
                         placeholder="Your full name" required minlength="2"
                         value="<?= isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_name'] ?? '') : '' ?>">
                  <div class="invalid-feedback">Name is required.</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                  <input type="email" class="form-control form-control-3d" name="email"
                         placeholder="you@example.com" required>
                  <div class="invalid-feedback">Valid email required.</div>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                  <select class="form-select form-control-3d" name="subject" required>
                    <option value="">— Select a subject —</option>
                    <option>General Enquiry</option>
                    <option>Report Plastic Pollution</option>
                    <option>Volunteer / Join the Team</option>
                    <option>Donation Enquiry</option>
                    <option>Partnership / Collaboration</option>
                    <option>Media / Press</option>
                    <option>Technical Support</option>
                    <option>Other</option>
                  </select>
                  <div class="invalid-feedback">Please select a subject.</div>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                  <textarea class="form-control form-control-3d" name="message" rows="6"
                            placeholder="Tell us how we can help..." required minlength="10"></textarea>
                  <div class="d-flex justify-content-between mt-1">
                    <div class="invalid-feedback">Message must be at least 10 characters.</div>
                    <small class="text-muted"><span id="msgCount">0</span> / 1000</small>
                  </div>
                </div>
              </div>

              <div id="contactAlert" class="mt-4"></div>

              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-green-3d btn-lg" id="contactBtn">
                  <span class="btn-text"><i class="bi bi-send me-2"></i>Send Message</span>
                  <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm me-2"></span>Sending...</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?= SITE_URL ?>/js/contact.js" defer></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
