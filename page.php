<?php
include 'renderNwfContent.php';

$system = json_decode(file_get_contents("system.json"), true);
$name = $_GET['name'] ?? '';
if (!$name) {
    http_response_code(400);
    exit("Missing page name");
}

$pageFile = null;
foreach ($system['pages'] as $page) {
    if (basename($page['file'], '.nwf') === $name) {
        $pageFile = $page['file'];
        break;
    }
}
if (!$pageFile || !file_exists($pageFile)) {
    http_response_code(404);
    exit("Page not found");
}

$pageData = json_decode(file_get_contents($pageFile), true);
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= htmlspecialchars($pageData['name'] ?? $pageData['title'] ?? 'Untitled Page') ?></title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<?= renderNwfContentArray($pageData['content'] ?? []) ?>
</body>
</html>
