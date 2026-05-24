<?php
// pages/developers.php  –  Profile of Developers

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/visitor_counter.php';
trackVisitor('/team');

$pageTitle    = 'Our Team – Profile of Developers';
$pageDesc     = 'Meet the student developers behind PlasticPollutions at Pentecost University, Faculty of Engineering Science and Computing.';
$pageKeywords = 'PlasticPollutions team, Pentecost University developers, web development team, student developers Ghana';

// ─── Replace with your actual team members ──────────────────────────────────
$team = [
  [
    'name'      => 'Nhyira Ayim',
    'role'      => 'Lead Developer & Project Manager',
    'image'     => 'https://ui-avatars.com/api/?name=YN&background=0a4d2f&color=fff&size=200',
    'statement' => 'I believe technology is the most powerful tool we have to solve environmental challenges. Building PlasticPollutions has reinforced my commitment to using my skills for social good.',
    'philosophy'=> 'Code with purpose. Build for impact.',
    'skills'    => ['PHP','MySQL','JavaScript','Bootstrap','UI/UX Design'],
    'github'    => 'https://github.com/NhyiraAyim47',   // <!-- SOCIAL LINK: GITHUB – replace # with your GitHub profile URL -->
    'linkedin'  => '#',   // <!-- SOCIAL LINK: LINKEDIN – replace # with your LinkedIn profile URL -->
    'email'     => 'nhyirahayim@gmail.com',
  ],
  [
    'name'      => 'Amos Oware',
    'role'      => 'Backend Developer',
    'image'     => 'https://ui-avatars.com/api/?name=TM&background=1a8a52&color=fff&size=200',
    'statement' => 'Working on the database architecture and security features of this system gave me a deep appreciation for building software that protects user data while delivering real-world value.',
    'philosophy'=> 'Security is not an option — it\'s a foundation.',
    'skills'    => ['PHP','MySQL','PDO','AJAX','Security'],
    'github'    => '#',   // <!-- SOCIAL LINK: GITHUB – replace # with your GitHub profile URL -->
    'linkedin'  => '#',   // <!-- SOCIAL LINK: LINKEDIN – replace # with your LinkedIn profile URL -->
    'email'     => 'ayimnhyirahamos@gmail.com',
  ],
  [
    'name'      => 'Ebenezer Ayim',
    'role'      => 'Frontend Developer & Designer',
    'image'     => 'https://ui-avatars.com/api/?name=TM&background=00a040&color=fff&size=200',
    'statement' => 'Great design is invisible — users should feel the experience, not think about it. Crafting the 3D UI and animations for PlasticPollutions challenged me to push my CSS skills to new levels.',
    'philosophy'=> 'Design that serves people, not just screens.',
    'skills'    => ['HTML5','CSS3','Bootstrap','Tailwind','UX Design'],
    'github'    => '#',   // <!-- SOCIAL LINK: GITHUB – replace # with your GitHub profile URL -->
    'linkedin'  => '#',   // <!-- SOCIAL LINK: LINKEDIN – replace # with your LinkedIn profile URL -->
    'email'     => 'ebenezerayim0@gmail.com',
  ],
  [
    'name'      => 'Martha Anane',
    'role'      => 'Full-Stack Developer',
    'image'     => 'https://ui-avatars.com/api/?name=TM&background=004d25&color=fff&size=200',
    'statement' => 'Building the donation and authentication systems taught me that every line of code has a real-world consequence. This project has made me a more responsible developer.',
    'philosophy'=> 'Build systems that earn trust.',
    'skills'    => ['PHP','JavaScript','MySQL','AJAX','API Integration'],
    'github'    => '#',   // <!-- SOCIAL LINK: GITHUB – replace # with your GitHub profile URL -->
    'linkedin'  => '#',   // <!-- SOCIAL LINK: LINKEDIN – replace # with your LinkedIn profile URL -->
    'email'     => 'member4@plasticpollutions.edu.gh',
  ],
];

$extraCss = [SITE_URL . '/css/developers.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero -->
<div class="page-hero bg-dark-green text-white py-5">
  <div class="container text-center">
    <span class="section-badge-light">The People Behind the Code</span>
    <h1 class="section-title-white">Meet Our <span class="text-success-light">Team</span></h1>
    <p class="text-white-50 mx-auto" style="max-width:560px;">
      Level 300 Computer Science students, Faculty of Engineering Science & Computing, Pentecost University — united by a passion for technology and environmental action.
    </p>
  </div>
</div>

<!-- Team Cards -->
<section class="section-pad bg-white">
  <div class="container">
    <div class="row g-5 justify-content-center">
      <?php foreach ($team as $i => $member): ?>
      <div class="col-lg-6">
        <!-- Flip Card -->
        <div class="flip-card">
          <div class="flip-card-inner">
            <!-- Front -->
            <div class="flip-card-front team-card-front">
              <div class="team-img-wrap">
                <img src="<?= htmlspecialchars($member['image']) ?>"
                     alt="Photo of <?= htmlspecialchars($member['name']) ?>"
                     loading="lazy">
              </div>
              <div class="team-card-body">
                <h4><?= htmlspecialchars($member['name']) ?></h4>
                <p class="team-role"><?= htmlspecialchars($member['role']) ?></p>
                <div class="team-skills">
                  <?php foreach ($member['skills'] as $skill): ?>
                    <span class="team-skill"><?= htmlspecialchars($skill) ?></span>
                  <?php endforeach; ?>
                </div>
                <p class="team-hint mt-3 text-muted small"><i class="bi bi-arrow-repeat me-1"></i>Hover to see more</p>
              </div>
            </div>
            <!-- Back -->
            <div class="flip-card-back team-card-back">
              <div class="team-back-content">
                <div class="team-quote">
                  <i class="bi bi-quote"></i>
                  <p>"<?= htmlspecialchars($member['statement']) ?>"</p>
                </div>
                <div class="team-philosophy">
                  <i class="bi bi-lightbulb-fill text-warning me-2"></i>
                  <em><?= htmlspecialchars($member['philosophy']) ?></em>
                </div>
                <div class="team-social-links mt-4">
                  <!-- SOCIAL LINK: GITHUB profile link -->
                  <a href="<?= htmlspecialchars($member['github']) ?>" class="team-social-btn github"
                     target="_blank" rel="noopener" title="GitHub Profile"
                     aria-label="View <?= htmlspecialchars($member['name']) ?>'s GitHub profile">
                    <i class="fab fa-github"></i> GitHub
                  </a>
                  <!-- SOCIAL LINK: LINKEDIN profile link -->
                  <a href="<?= htmlspecialchars($member['linkedin']) ?>" class="team-social-btn linkedin-t"
                     target="_blank" rel="noopener" title="LinkedIn Profile"
                     aria-label="View <?= htmlspecialchars($member['name']) ?>'s LinkedIn profile">
                    <i class="fab fa-linkedin-in"></i> LinkedIn
                  </a>
                  <a href="https://mail.google.com/mail/?view=cm&to=<?= htmlspecialchars($member['email']) ?>" class="team-social-btn email-t"
                  target="_blank" rel="noopener"
                  title="Send email" aria-label="Email <?= htmlspecialchars($member['name']) ?>">
                    <i class="bi bi-envelope-fill"></i> Email
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Project Info -->
<section class="section-pad bg-light-green">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <span class="section-badge">About This Project</span>
        <h2 class="section-title">PBIT304 Semester <span class="text-success">Project</span></h2>
        <p class="text-muted">
          This web application was developed as the Level 300 Semester Project for <strong>PBIT304 – Web Application Development</strong> at Pentecost University's Faculty of Engineering Science & Computing. It demonstrates practical skills in PHP, MySQL, JavaScript, HTML5, and CSS3 — including advanced topics such as SMTP email delivery, OTP verification, password hashing, SQL injection prevention, and responsive 3D design.
        </p>
        <div class="row g-4 mt-3">
          <?php foreach ([
            ['🏫','Institution','Pentecost University'],
            ['💻','Tech Stack','PHP · MySQL · JavaScript · Bootstrap · CSS3'],
            ['🔒','Security','BCrypt · PDO · OTP · Input Sanitization'],
          ] as $info): ?>
          <div class="col-md-3 col-6">
            <div class="project-info-card">
              <div class="project-info-icon"><?= $info[0] ?></div>
              <div class="project-info-label"><?= $info[1] ?></div>
              <div class="project-info-value"><?= $info[2] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
