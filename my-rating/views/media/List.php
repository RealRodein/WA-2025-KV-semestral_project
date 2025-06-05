<?php
session_start();
session_regenerate_id(true);
if (!isset($mediaList) || !isset($filterOptions)) {
    $query = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
    header('Location: ../../controllers/MediaController.php?action=list' . $query);
    exit();
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Moje-Hodnocení</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/layout.css">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/filter-bar.css">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/media-grid.css">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/media-cards.css">
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
            <?php if (isset($_SESSION['user'])): ?>
                <span class="user"><strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></span>
                <a class="user" href="/WA-2025-KV-semestral_project/my-rating/controllers/UserController.php?action=logout">Odhlásit se</a>
            <?php else: ?>
                <a class="user" href="/WA-2025-KV-semestral_project/my-rating/views/auth/Login.php">Přihlášení</a>
                <a class="user" href="/WA-2025-KV-semestral_project/my-rating/views/auth/Register.php">Registrace</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="main-wrapper">

        <div class="sidebar"></div>

        <div class="content">
            <!-- Filter Bar -->
            <form method="GET" action="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php" class="filter-bar d-flex gap-3 mb-4">
                <input type="hidden" name="action" value="list">
                <input type="text" name="search" placeholder="Search" class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

                <?php
                $filters = [
                    'genre' => 'Any Genre',
                    'year' => 'Any Year',
                    'type' => 'Any Type'
                ];
                foreach ($filters as $filter => $placeholder): ?>
                    <select name="<?= $filter ?>" class="form-select">
                        <option value=""><?= $placeholder ?></option>
                        <?php foreach ($filterOptions[$filter . 's'] as $option): ?>
                            <option value="<?= htmlspecialchars($option) ?>" <?= (isset($_GET[$filter]) && $_GET[$filter] === $option) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($option) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-primary">Filter</button>
            </form>

            <!-- Display Filtered Media -->
            <div class="media-grid">
                <?php if (empty($mediaList)): ?>
                    <div class="w-100 text-center text-secondary py-5" style="grid-column: 1 / -1;">
                        Žádné médium nebylo nalezeno.
                    </div>
                <?php else: ?>
                    <?php foreach ($mediaList as $item): ?>
                        <a href="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=detail&id=<?= urlencode($item['id']) ?>" class="media-card-link" style="text-decoration:none;color:inherit;">
                            <div class="media-card">
                                <div class="media-poster-hoverbox">
                                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                                    <div class="media-poster-overlay"></div>
                                    <div class="media-poster-rating">
                                        <?= isset($item['weighted_rating']) ? htmlspecialchars($item['weighted_rating']) . '/10' : '' ?>
                                    </div>
                                </div>
                                <div class="media-title"><?= htmlspecialchars($item['title']) ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="sidebar"></div>
    </div>
</body>
</html>