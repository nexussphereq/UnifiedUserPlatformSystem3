<?php
// Unified User Platform System 3.0
// The world's leading CMS framework
// a NexussphereQ product
// released under MIT license

$system = json_decode(file_get_contents("system.json"), true);

$posts = $system['posts'] ?? [];
// Filter out posts where file doesn't exist to avoid filemtime errors
$posts = array_filter($posts, fn($p) => isset($p['file']) && file_exists($p['file']));

usort($posts, function($a, $b) {
    return filemtime($b['file']) - filemtime($a['file']);
});

$pages = $system['pages'] ?? [];
// Filter pages as well
$pages = array_filter($pages, fn($p) => isset($p['file']) && file_exists($p['file']));

$postPerPageDefault = 10;
$perPage = isset($system['postpage']) && is_int($system['postpage']) && $system['postpage'] > 0
    ? $system['postpage']
    : $postPerPageDefault;

$pageNum = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalPages = ceil(count($posts) / $perPage);
$offset = ($pageNum - 1) * $perPage;
$currentPosts = array_slice($posts, $offset, $perPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= htmlspecialchars($system['title'] ?? 'NexaWrite Site') ?></title>
<link rel="stylesheet" href="style.css" />
<?php if (!empty($system['manifest'])): ?>
<link rel="manifest" href="<?= htmlspecialchars($system['manifest']) ?>" />
<?php endif; ?>

</head>
<body>
<header class="site-header">
    <h1><?= htmlspecialchars($system['title'] ?? 'NexaWrite Site') ?></h1>

    <?php if (!empty($system['author'])): ?>
    <p class="site-author">By <?= htmlspecialchars($system['author']) ?></p>
    <?php endif; ?>
</header>

<nav class="page-nav" aria-label="Site pages">
    <h2>Pages</h2>
    <?php if (empty($pages)): ?>
        <p>No pages available.</p>
    <?php else: ?>
        <ul class="pages">
            <?php foreach ($pages as $page): 
                $pageName = basename($page['file'], ".nwf");
            ?>
            <li><a href="page.php?name=<?= urlencode($pageName) ?>"><?= htmlspecialchars($page['name'] ?? $pageName) ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</nav>

<section class="post-list">
    <h2>Posts</h2>
    <?php if (empty($currentPosts)): ?>
        <p>No posts found.</p>
    <?php else: ?>
        <?php foreach ($currentPosts as $post): 
            $id = basename($post['file'], ".nwf");
        ?>
        <article class="post-card">
            <h3 class="post-title">
                <a href="post.php?id=<?= urlencode($id) ?>">
                    <?= htmlspecialchars($post['name'] ?? $id) ?>
                </a>
            </h3>
            <?php if (!empty($post['description'])): ?>
            <p class="post-description"><?= htmlspecialchars($post['description']) ?></p>
            <?php endif; ?>
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php if ($totalPages > 1): ?>
<nav class="pagination" aria-label="Post navigation">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>" class="page-link <?= $i === $pageNum ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</nav>
<?php endif; ?>

</body>
</html>
