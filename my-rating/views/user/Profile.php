<?php
session_start();
session_regenerate_id(true);
require_once '../../models/Database.php';
require_once '../../models/Comment.php';
require_once '../../models/Media.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/Login.php');
    exit();
}

$db = (new Database())->getConnection();
$commentModel = new Comment($db);
$mediaModel = new Media($db);

// pokud je v url id, zobrazit profil daneho uzivatele, jinak prihlaseneho
$profileUserId = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user']['id'];

// nacteni uzivatele
$stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$profileUserId]);
$profileUser = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$profileUser) {
    echo '<div class="text-center text-danger">Uživatel nebyl nalezen.</div>';
    exit();
}
$username = $profileUser['username'];

// nacteni komentaru daneho uzivatele
$stmt = $db->prepare('SELECT c.*, m.title as media_title, m.id as media_id FROM comments c JOIN media m ON c.media_id = m.id WHERE c.user_id = ? ORDER BY c.created_at DESC');
$stmt->execute([$profileUserId]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($username) ?> – Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/layout.css">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/comments.css">
</head>
<body>
    <?php
    $navbarContext = 'list';
    include __DIR__ . '/../../public/navbar.php';
    ?>
    <div class="main-wrapper">
        <div class="sidebar"></div>
        <div class="content">
            <h2 class="mb-4 text-center">Profil uživatele: <span style="color:#fed500;"><?= htmlspecialchars($username) ?></span></h2>
            <h4 class="mb-3 text-center">Moje komentáře</h4>
            <div class="comments-section">
                <?php if (empty($comments)): ?>
                    <div class="text-center text-secondary" style="grid-column: 1 / -1;">
                        Zatím prázdno...
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $c): ?>
                        <div class="comment-card" style="position:relative;">
                            <div class="comment-meta" style="display:flex;justify-content:space-between;align-items:center;">
                                <span class="comment-rating" style="margin-left:0;"><?= htmlspecialchars($c['rating']) ?>/10*</span>
                                <span style="color:#888;font-size:0.95em;">
                                    <?= htmlspecialchars($c['created_at']) ?>
                                </span>
                            </div>
                            <div style="margin: 8px 0 0 0;">
                                <?= nl2br(htmlspecialchars($c['content'])) ?>
                            </div>
                            <a href="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=detail&id=<?= urlencode($c['media_id']) ?>" class="comment-card-title">
                                <?= htmlspecialchars($c['media_title']) ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="sidebar"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>