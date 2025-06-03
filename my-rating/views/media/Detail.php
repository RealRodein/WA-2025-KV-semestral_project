<?php
require_once '../../models/Database.php';

$mediaId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$media = null;

if ($mediaId > 0) {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare("SELECT * FROM media WHERE id = :id");
    $stmt->execute([':id' => $mediaId]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($media) {
    $title = $media['title'];
    $description = $media['description'];
    $image_url = $media['image_url'];
    $banner_url = $media['banner_url'];
    $type = $media['type'];
    $year = $media['year'];
    $genre = $media['genre'];
} else {
    $title = $description = $image_url = $banner_url = $type = $year = $genre = '';
}
?>


<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Moje-Hodnocení</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/style.css">
</head>

<body>
    <div class="header">
        <a class="logo" href="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php">
            <img src="/WA-2025-KV-semestral_project/my-rating/public/images/icon.svg" alt="Moje-Hodnocení" style="width:32px;height:32px;vertical-align:middle;">
        </a>

        <div class="menu">
            <a href="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php">Procházet</a>
            <a href="/WA-2025-KV-semestral_project/my-rating/views/user/Profile.php">Profil</a>
        </div>

        <div class="user">
            <a class="user" href="/WA-2025-KV-semestral_project/my-rating/views/auth/Login.php">Přihlášení</a>
            <a class="user" href="/WA-2025-KV-semestral_project/my-rating/views/auth/Register.php">Registrace</a>
        </div>
    </div> <!-- end of .header -->

    <div class="media-banner" style="background-image: url('<?php echo htmlspecialchars($banner_url); ?>');">
        <div class="media-banner-overlay"></div>
    </div>

    <div class="main-container">
        <div class="media-info-bar">
            <div class="media-info-flex">
                <div class="media-info-poster">
                    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($title); ?>">
                </div>
                <div class="media-info-content">
                    <h1 class="media-title"><?php echo htmlspecialchars($title); ?></h1>
                    <p class="media-description"><?php echo htmlspecialchars($description); ?></p>
                </div>
            </div>
        </div>

        <div style="background-color: #111;">
            <div>
            </div>
        </div> <!-- end of .main-container -->

        <div class="media-details-panel">
            <div class="media-details-row">
                <span class="media-details-label">Typ:</span>
                <span class="media-details-value"><?php echo htmlspecialchars($type); ?></span>
            </div>
            <div class="media-details-row">
                <span class="media-details-label">Žánr:</span>
                <span class="media-details-value"><?php echo htmlspecialchars($genre); ?></span>
            </div>
            <div class="media-details-row">
                <span class="media-details-label">Rok:</span>
                <span class="media-details-value"><?php echo htmlspecialchars($year); ?></span>
            </div>
            <div class="media-details-row">
                <span class="media-details-label">Délka:</span>
                <span class="media-details-value"><?php echo isset($duration) ? htmlspecialchars($duration) . ' min' : '—'; ?></span>
            </div>
            <div class="media-details-row">
                <span class="media-details-label">Počet epizod:</span>
                <span class="media-details-value"><?php echo isset($episode_count) ? htmlspecialchars($episode_count) : '—'; ?></span>
            </div>
            <div class="media-details-row">
                <span class="media-details-label">Autor:</span>
                <span class="media-details-value"><?php echo isset($author) ? htmlspecialchars($author) : '—'; ?></span>
            </div>
        </div>
    </div>
</body>

</html>