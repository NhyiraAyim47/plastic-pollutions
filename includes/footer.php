<?php
// includes/footer.php  –  Global site footer
// NOTE: Social media links are marked with <!-- SOCIAL LINK --> for easy identification
?>
</main><!-- /mainContent -->

<!-- ===== FOOTER ===== -->
<footer class="footer-3d mt-0">
  <!-- Wave Divider -->
  <div class="footer-wave">
    <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
      <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="currentColor"/>
    </svg>
  </div>

  <div class="footer-body">
    <div class="container">
      <div class="row g-5">

        <!-- Brand Column -->
        <div class="col-lg-4 col-md-6">
          <div class="footer-brand mb-4">
            <span class="fs-2">🌿</span>
            <h4 class="mb-1 text-white fw-bold">PlasticPollutions</h4>
            <p class="text-success-light small">Faculty of Engineering Science &amp; Computing<br>Pentecost University</p>
          </div>
          <p class="footer-desc">
            We are a student-led environmental action group committed to reducing plastic waste and protecting Ghana's oceans, wildlife, and communities for future generations.
          </p>
          <!-- ===== SOCIAL MEDIA LINKS ===== -->
          <!-- NOTE: Replace href="#" with your actual social media profile URLs -->
          <div class="social-links mt-4">
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
          <!-- ===== END SOCIAL MEDIA LINKS ===== -->
        </div>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-6">
          <h6 class="footer-heading">Quick Links</h6>
          <ul class="footer-links">
            <li><a href="<?= SITE_URL ?>/pages/home.php"><i class="bi bi-chevron-right"></i> Home</a></li>
            <li><a href="<?= SITE_URL ?>/pages/what_to_do.php"><i class="bi bi-chevron-right"></i> About Plastic</a></li>
            <li><a href="<?= SITE_URL ?>/pages/campaigns.php"><i class="bi bi-chevron-right"></i> Campaigns</a></li>
            <li><a href="<?= SITE_URL ?>/pages/strategy.php"><i class="bi bi-chevron-right"></i> Strategy</a></li>
            <li><a href="<?= SITE_URL ?>/pages/latest.php"><i class="bi bi-chevron-right"></i> Latest News</a></li>
            <li><a href="<?= SITE_URL ?>/pages/developers.php"><i class="bi bi-chevron-right"></i> Our Team</a></li>
          </ul>
        </div>

        <!-- Get Involved -->
        <div class="col-lg-2 col-md-6">
          <h6 class="footer-heading">Get Involved</h6>
          <ul class="footer-links">
            <li><a href="<?= SITE_URL ?>/pages/how_to_help.php"><i class="bi bi-chevron-right"></i> Volunteer</a></li>
            <li><a href="<?= SITE_URL ?>/donations/donate.php"><i class="bi bi-chevron-right"></i> Donate</a></li>
            <li><a href="<?= SITE_URL ?>/pages/what_to_do.php#petition"><i class="bi bi-chevron-right"></i> Sign Petition</a></li>
            <li><a href="<?= SITE_URL ?>/pages/contact.php"><i class="bi bi-chevron-right"></i> Contact Us</a></li>
            <li><a href="<?= SITE_URL ?>/auth/register.php"><i class="bi bi-chevron-right"></i> Register</a></li>
          </ul>
        </div>

        <!-- Contact & Simulated Twitter Feed -->
        <div class="col-lg-4 col-md-6">
          <h6 class="footer-heading"><i class="fab fa-x-twitter me-2"></i>Latest Updates</h6>
          <!-- Simulated Twitter/X Feed -->
          <div class="twitter-feed-simulated" id="twitterFeed">
            <div class="tweet-card">
              <div class="tweet-header">
                <span class="tweet-avatar">🌿</span>
                <div>
                  <strong class="text-white small">PlasticPollutions</strong>
                  <span class="text-muted x-small d-block">@PlasticPollutionsGH</span>
                </div>
                <i class="fab fa-x-twitter ms-auto text-muted"></i>
              </div>
              <p class="tweet-text">🚨 Only 5% of plastic is recycled in Ghana. Together, we can change this. Join our cleanup drive this Saturday! #EndPlasticPollution #Ghana</p>
              <span class="tweet-time">2h ago</span>
            </div>
            <div class="tweet-card">
              <div class="tweet-header">
                <span class="tweet-avatar">🌿</span>
                <div>
                  <strong class="text-white small">PlasticPollutions</strong>
                  <span class="text-muted x-small d-block">@PlasticPollutionsGH</span>
                </div>
                <i class="fab fa-x-twitter ms-auto text-muted"></i>
              </div>
              <p class="tweet-text">🌊 500,000+ plastic bottles enter Ghana's water bodies daily. Sign our petition today and demand change! Link in bio. #PlasticFree</p>
              <span class="tweet-time">1d ago</span>
            </div>
          </div>

          <!-- Follow on X -->
          <a href="https://x.com/nhyirah_ayim" class="btn btn-outline-light btn-sm mt-3 w-100" target="_blank" rel="noopener">
            <!-- SOCIAL LINK: TWITTER/X FOLLOW BUTTON – replace href="#" with your Twitter/X profile URL -->
            <i class="fab fa-x-twitter me-2"></i>Follow @PlasticPollutionsGH
          </a>

          <!-- Visitor Counter -->
          <div class="visitor-counter mt-3">
            <i class="bi bi-eye me-2"></i>
            <span>Site Visitors: </span>
            <strong id="footerVisitorCount" class="text-success">Loading...</strong>
          </div>
        </div>

      </div><!-- /row -->

      <hr class="footer-divider mt-5">

      <div class="row align-items-center py-3">
        <div class="col-md-6 text-center text-md-start">
          <small class="text-muted">
            &copy; <?= date('Y') ?> PlasticPollutions – Pentecost University. All rights reserved.
          </small>
        </div>
        <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
          <small class="text-muted">
            <a href="<?= SITE_URL ?>/pages/privacy.php" class="footer-link-sm">Privacy Policy</a>
            &nbsp;|&nbsp;
            <a href="<?= SITE_URL ?>/pages/home.php#cookies" class="footer-link-sm">Cookie Policy</a>
            &nbsp;|&nbsp;
            <a href="<?= SITE_URL ?>/pages/contact.php" class="footer-link-sm">Contact</a>
          </small>
        </div>
      </div>
    </div><!-- /container -->
  </div><!-- /footer-body -->
</footer>

<!-- ===== BACK TO TOP ===== -->
<button id="backToTop" class="back-to-top" aria-label="Back to top">
  <i class="bi bi-chevron-up"></i>
</button>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


<script>
  const SITE_URL = '<?= SITE_URL ?>';
  const IS_LOGGED_IN = <?= $isLoggedIn ? 'true' : 'false' ?>;
</script>

<script src="<?= SITE_URL ?>/js/form_validation.js"></script>
<script src="<?= SITE_URL ?>/js/main.js"></script>
<script src="<?= SITE_URL ?>/js/cookie.js"></script>
</body>
</html>
