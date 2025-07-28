<?php
include 'renderNwfContent.php';

$system = json_decode(file_get_contents("system.json"), true);
$id = $_GET['id'] ?? '';
if (!$id) {
    http_response_code(400);
    exit("Missing post id");
}

$postFile = null;
foreach ($system['posts'] as $post) {
    if (basename($post['file'], '.nwf') === $id) {
        $postFile = $post['file'];
        break;
    }
}
if (!$postFile || !file_exists($postFile)) {
    http_response_code(404);
    exit("Post not found");
}

$postData = json_decode(file_get_contents($postFile), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= htmlspecialchars($postData['name'] ?? $postData['title'] ?? 'Untitled Post') ?></title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<?= renderNwfContentArray($postData['content'] ?? []) ?>
</body>
</html>
