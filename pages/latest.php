<?php
// pages/latest.php  –  Latest on Plastic (blog)

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/visitor_counter.php';
trackVisitor('/latest');

$pageTitle    = 'Latest on Plastic – News & Updates';
$pageDesc     = 'Stay up to date with the latest news, research, and developments in plastic pollution, environmental policy, and our campaigns in Ghana.';
$pageKeywords = 'plastic pollution news, Ghana plastic policy, recycling news, ocean plastic updates, environmental news Ghana';

$db   = getDB();
$slug = filter_input(INPUT_GET, 'slug', FILTER_SANITIZE_SPECIAL_CHARS);

// Single post view
if ($slug) {
    $post = $db->prepare("SELECT * FROM blog_posts WHERE slug=?"); $post->execute([$slug]); $post=$post->fetch();
    if (!$post) { header('Location: '.SITE_URL.'/pages/latest.php'); exit; }
    // Increment views
    $db->prepare("UPDATE blog_posts SET views=views+1 WHERE id=?")->execute([$post['id']]);
    $pageTitle = htmlspecialchars($post['title']);
    require_once __DIR__ . '/../includes/header.php';
    ?>
    <div class="page-hero bg-dark-green text-white py-5">
      <div class="container">
        <div class="row justify-content-center"><div class="col-lg-9 text-center">
          <span class="section-badge-light"><?= htmlspecialchars($post['category']) ?></span>
          <h1 class="section-title-white mt-2"><?= htmlspecialchars($post['title']) ?></h1>
          <p class="text-white-50">
            <i class="bi bi-person me-1"></i><?= htmlspecialchars($post['author']) ?>
            &nbsp;&bull;&nbsp;
            <i class="bi bi-calendar me-1"></i><?= date('d F Y', strtotime($post['published_at'])) ?>
            &nbsp;&bull;&nbsp;
            <i class="bi bi-eye me-1"></i><?= number_format($post['views']) ?> views
          </p>
        </div></div>
      </div>
    </div>
    <section class="section-pad">
      <div class="container"><div class="row justify-content-center"><div class="col-lg-9">
        <?php if ($post['image_url']): ?>
          <img src="<?= htmlspecialchars($post['image_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>"
               class="img-fluid rounded-4 mb-4 w-100" style="max-height:420px;object-fit:cover;" loading="lazy">
        <?php endif; ?>
        <div class="blog-post-content"><?= $post['content'] ?></div>
        <hr class="my-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
          <a href="<?= SITE_URL ?>/pages/latest.php" class="btn btn-outline-success">
            <i class="bi bi-arrow-left me-2"></i>Back to All News
          </a>
          <div class="d-flex gap-2">
            <span class="text-muted small me-2">Share:</span>
            <!-- SOCIAL LINK: Share buttons - update these with actual social share URLs -->
            <a href="https://twitter.com/intent/tweet?text=<?= urlencode($post['title']) ?>&url=<?= urlencode(SITE_URL.'/pages/latest.php?slug='.$post['slug']) ?>"
               class="social-btn twitter" target="_blank" rel="noopener" aria-label="Share on X (Twitter)">
               <i class="fab fa-x-twitter"></i></a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL.'/pages/latest.php?slug='.$post['slug']) ?>"
               class="social-btn facebook" target="_blank" rel="noopener" aria-label="Share on Facebook">
               <i class="fab fa-facebook-f"></i></a>
          </div>
        </div>
      </div></div>
    </section>
    <style>.blog-post-content { font-size:1.05rem; line-height:1.9; color:var(--pp-text); }
    .blog-post-content p { margin-bottom:1.2rem; }
    .blog-post-content h2,.blog-post-content h3 { color:var(--pp-green); margin:2rem 0 1rem; }</style>
    <?php require_once __DIR__ . '/../includes/footer.php'; return;
}

// Blog listing
$category = filter_input(INPUT_GET, 'cat', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
$page     = max(1,(int)($_GET['page']??1));
$perPage  = 6;
$offset   = ($page-1)*$perPage;

$where  = $category ? "WHERE category=?" : "";
$params = $category ? [$category] : [];

$total = $db->prepare("SELECT COUNT(*) FROM blog_posts $where");
$total->execute($params); $total=(int)$total->fetchColumn();
$totalPages = max(1, ceil($total/$perPage));

$stmt = $db->prepare("SELECT * FROM blog_posts $where ORDER BY published_at DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params); $posts=$stmt->fetchAll();

$categories = $db->query("SELECT DISTINCT category FROM blog_posts ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-hero bg-dark-green text-white py-5">
  <div class="container text-center">
    <span class="section-badge-light">Stay Informed</span>
    <h1 class="section-title-white">Latest on <span class="text-success-light">Plastic</span></h1>
    <p class="text-white-50 mx-auto" style="max-width:520px;">News, research, policy updates, and stories from the front lines of the fight against plastic pollution.</p>
  </div>
</div>

<section class="section-pad">
  <div class="container">
    <!-- Category Filter -->
    <div class="d-flex flex-wrap gap-2 justify-content-center mb-5">
      <a href="<?= SITE_URL ?>/pages/latest.php"
         class="btn btn-sm <?= !$category?'btn-green-3d':'btn-outline-success' ?>">All</a>
      <?php foreach ($categories as $cat): ?>
        <a href="?cat=<?= urlencode($cat) ?>"
           class="btn btn-sm <?= $category===$cat?'btn-green-3d':'btn-outline-success' ?>"><?= htmlspecialchars($cat) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="row g-4">
      <?php foreach ($posts as $post): ?>
      <div class="col-lg-4 col-md-6">
        <article class="blog-card-3d h-100">
          <?php if ($post['image_url']): ?>
          <div class="blog-card-img-wrap">
            <img src="<?= htmlspecialchars($post['image_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" loading="lazy">
            <span class="blog-category"><?= htmlspecialchars($post['category']) ?></span>
          </div>
          <?php endif; ?>
          <div class="blog-card-body">
            <h5><a href="?slug=<?= urlencode($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a></h5>
            <p><?= htmlspecialchars($post['excerpt']) ?></p>
            <div class="blog-card-footer">
              <span><i class="bi bi-person me-1"></i><?= htmlspecialchars($post['author']) ?></span>
              <span><i class="bi bi-eye me-1"></i><?= number_format($post['views']) ?></span>
            </div>
            <a href="?slug=<?= urlencode($post['slug']) ?>" class="btn btn-green-3d btn-sm mt-3">Read More</a>
          </div>
        </article>
      </div>
      <?php endforeach; ?>
      <?php if (empty($posts)): ?>
        <div class="col-12 text-center py-5">
          <i class="bi bi-newspaper display-3 text-muted"></i>
          <p class="mt-3 text-muted">No articles found in this category.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="mt-5"><ul class="pagination justify-content-center">
      <?php for($p=1;$p<=$totalPages;$p++): ?>
        <li class="page-item <?= $p===$page?'active':'' ?>">
          <a class="page-link" href="?page=<?=$p?><?= $category?'&cat='.urlencode($category):'' ?>"><?=$p?></a>
        </li>
      <?php endfor; ?>
    </ul></nav>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
