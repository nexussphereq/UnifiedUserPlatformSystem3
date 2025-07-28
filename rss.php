<?php
header("Content-Type: application/rss+xml; charset=UTF-8");
$system = json_decode(file_get_contents("system.json"), true);

$siteTitle = htmlspecialchars($system['title'] ?? 'NexaWrite Site', ENT_XML1);
$siteAuthor = htmlspecialchars($system['author'] ?? '', ENT_XML1);
$siteLink = rtrim($system['baseurl'], '/');

$posts = $system['posts'] ?? [];
// Filter out posts without valid files
$posts = array_filter($posts, fn($p) => isset($p['file']) && file_exists($p['file']));

// Sort posts by date inside the .nwf files (ISO 8601 date field)
usort($posts, function($a, $b) {
    $aData = json_decode(file_get_contents($a['file']), true);
    $bData = json_decode(file_get_contents($b['file']), true);
    $aDate = $aData['date'] ?? '';
    $bDate = $bData['date'] ?? '';
    return strcmp($bDate, $aDate);
});

// Use the latest post date as lastBuildDate, fallback to now
$lastBuildDate = 'now';
if (!empty($posts)) {
    $latestPost = json_decode(file_get_contents($posts[0]['file']), true);
    $lastBuildDate = $latestPost['date'] ?? 'now';
}

function iso8601ToRfc2822($isoDate) {
    $dt = DateTime::createFromFormat(DateTime::ATOM, $isoDate);
    if (!$dt) return date(DATE_RSS);
    return $dt->format(DATE_RSS);
}

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
?>
<rss version="2.0">
  <channel>
    <title><?= $siteTitle ?></title>
    <link><?= $siteLink ?>/</link>
    <description>RSS Feed of <?= $siteTitle ?></description>
    <language>en-us</language>
    <lastBuildDate><?= iso8601ToRfc2822($lastBuildDate) ?></lastBuildDate>

<?php foreach ($posts as $post): 
    $id = basename($post['file'], ".nwf");
    $postData = json_decode(file_get_contents($post['file']), true);
    if (!$postData) continue;

    $postTitle = htmlspecialchars($postData['name'] ?? $id, ENT_XML1);
    $postDescription = htmlspecialchars($postData['content'][0]['text'] ?? $post['description'] ?? '', ENT_XML1);
    $postDate = iso8601ToRfc2822($postData['date'] ?? '');
    $postUrl = $siteLink . '/post.php?id=' . urlencode($id);
?>
    <item>
      <title><?= $postTitle ?></title>
      <link><?= $postUrl ?></link>
      <guid><?= $postUrl ?></guid>
      <pubDate><?= $postDate ?></pubDate>
      <description><?= $postDescription ?></description>
    </item>
<?php endforeach; ?>

  </channel>
</rss>
