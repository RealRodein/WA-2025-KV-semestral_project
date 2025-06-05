<?php
// Expecting: $media, $relatedMedia, $comments, $mediaId
$title = $media['title'] ?? '';
$description = $media['description'] ?? '';
$image_url = $media['image_url'] ?? '';
$banner_url = $media['banner_url'] ?? '';
$type = $media['type'] ?? '';
$year = $media['year'] ?? '';
$genre = $media['genre'] ?? '';
$author = $media['author'] ?? null;
$duration = $media['duration'] ?? null;
$episode_count = $media['episode_count'] ?? null;
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
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/media-detail.css">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/comments.css">
</head>

<body>
    <div class="header">
        <a class="logo" href="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php">
            <img src="/WA-2025-KV-semestral_project/my-rating/public/images/icon.svg" alt="Moje-Hodnocení"
                style="width:32px;height:32px;vertical-align:middle;">
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

    <div>
        <div class="media-info-bar" style="padding-bottom: 20px;">
            <div class="media-info-flex">
                <div class="media-info-poster">
                    <img src="<?php echo htmlspecialchars($image_url); ?>"
                        alt="<?php echo htmlspecialchars($title); ?>">
                </div>
                <div class="media-info-content">
                    <h1 class="media-title"><?php echo htmlspecialchars($title); ?></h1>
                    <p class="media-description"><?php echo htmlspecialchars($description); ?></p>
                    <?php if (isset($weightedRating) && $weightedRating !== null): ?>
                        <div class="media-mean-score">
                            <?= $weightedRating ?>/10
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (isset($meanScore) && $meanScore !== null): ?>
                <div class="mean-score-overlay">
                    <?= $meanScore ?>/10
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="sidebar"></div>

        <div class="content">
            <div class="media-info-flex" style="align-items: flex-start; margin-bottom: 20px; ">
                <div class="media-details-panel">
                    <div class="media-details-row">
                        <span class="media-details-label">Autor:</span>
                        <span
                            class="media-details-value"><?php echo isset($author) ? htmlspecialchars($author) : '—'; ?></span>
                    </div>
                    <div class="media-details-row">
                        <span class="media-details-label">Typ:</span>
                        <span class="media-details-value"><?php echo htmlspecialchars(ucfirst($type)); ?></span>
                    </div>
                    <div class="media-details-row">
                        <span class="media-details-label">Délka:</span>
                        <span
                            class="media-details-value"><?php echo isset($duration) ? htmlspecialchars($duration) . ' minut' : '—'; ?></span>
                    </div>
                    <div class="media-details-row">
                        <span class="media-details-label">Počet epizod:</span>
                        <span
                            class="media-details-value"><?php echo isset($episode_count) ? htmlspecialchars($episode_count) : '—'; ?></span>
                    </div>
                    <div class="media-details-row">
                        <span class="media-details-label">Žánr:</span>
                        <span class="media-details-value">
                            <?php
                            if (!empty($genre)) {
                                $genres = array_map('trim', explode(',', $genre));
                                foreach ($genres as $g) {
                                    echo '<div>' . htmlspecialchars(ucfirst($g)) . '</div>';
                                }
                            } else {
                                echo '—';
                            }
                            ?>
                        </span>
                    </div>
                    <div class="media-details-row">
                        <span class="media-details-label">Rok:</span>
                        <span class="media-details-value"><?php echo htmlspecialchars($year); ?></span>
                    </div>
                </div>

                <div style="flex:1; margin-left:32px;">
                    <?php if (!empty($relatedMedia)): ?>
                        <div>
                            <h4 class="text-light mb-3">Související média</h4>
                            <div class="media-grid-related">
                                <?php foreach ($relatedMedia as $rel): ?>
                                    <a href="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=detail&id=<?= urlencode($rel['id']) ?>"
                                        class="media-card-link" style="text-decoration:none;color:inherit;">
                                        <div class="media-card related-media-card">
                                            <div class="media-poster-hoverbox">
                                                <img src="<?= htmlspecialchars($rel['image_url']) ?>"
                                                    alt="<?= htmlspecialchars($rel['title']) ?>">
                                                <div class="media-poster-overlay"></div>
                                                <div class="media-poster-rating-related">
                                                    <?= isset($rel['weighted_rating']) && $rel['weighted_rating'] !== null ? htmlspecialchars($rel['weighted_rating']) . '/10' : '' ?>
                                                </div>
                                            </div>
                                            <div class="media-title related-media-title"><?= htmlspecialchars($rel['title']) ?>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>


            <h3 style="text-align: center;">Recenze a komentáře</h3>

            <div class="comments-section">
                <form class="comment-form" method="POST"
                    action="/WA-2025-KV-semestral_project/my-rating/controllers/CommentController.php?action=add">
                    <input type="hidden" name="media_id" value="<?= htmlspecialchars($mediaId) ?>">
                    <textarea name="content" required placeholder="Napište svůj komentář..."></textarea>
                    <div style="align-items: center; display: flex; justify-content: space-between;">
                        <label style="color:#fff; margin-bottom: 8px;">Hodnocení:
                            <select name="rating" required>
                                <option value="">—</option>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </label>
                        <button type="submit" style="margin-top: 4px;">Přidat komentář</button>
                    </div>

                </form>

                <!-- Example of displaying comments -->
                <?php foreach ($comments as $c): ?>
                    <div class="comment-card">
                        <div class="comment-meta">
                            <?= htmlspecialchars($c['username']) ?>
                            <span class="comment-rating">★ <?= htmlspecialchars($c['rating']) ?>/10</span>
                            <span style="float:right;color:#888;"><?= htmlspecialchars($c['created_at']) ?></span>
                        </div>
                        <div><?= nl2br(htmlspecialchars($c['content'])) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>






        </div>

        <div class="sidebar"></div>
    </div>

</body>