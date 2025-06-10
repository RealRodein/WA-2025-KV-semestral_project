<?php
// ocekava promenne: $media, $relatedMedia, $comments, $mediaId
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
    <?php
    $navbarContext = 'detail';
    $media = $media ?? null;
    include __DIR__ . '/../../public/navbar.php';
    ?>

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
                            <?php
                            $rounded = number_format($weightedRating, 1);
                            $display = ($rounded == intval($rounded)) ? intval($rounded) : $rounded;
                            ?>
                            <?= $display ?>/10
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (isset($meanScore) && $meanScore !== null): ?>
                <div class="mean-score-overlay">
                    <?php
                    $rounded = number_format($meanScore, 1);
                    $display = ($rounded == intval($rounded)) ? intval($rounded) : $rounded;
                    ?>
                    <?= $display ?>/10
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="sidebar"></div>

        <div class="content">
            <div class="media-info-flex<?php if (empty($relatedMedia)) echo ' center-details'; ?>">
                <div class="media-details-panel<?php if (empty($relatedMedia)) echo ' centered'; ?>" id="media-details-panel">
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
                            // prekladani zanru
                            $genreMap = [
                                'Action' => 'Akce',
                                'Adventure' => 'Dobrodružný',
                                'Comedy' => 'Komedie',
                                'Drama' => 'Drama',
                                'Fantasy' => 'Fantasy',
                                'Romance' => 'Romantika',
                                'Sci-Fi' => 'Sci-Fi',
                                'Thriller' => 'Thriller',
                                'Mystery' => 'Mysteriózní',
                            ];
                            if (!empty($genre)) {
                                $genres = array_map('trim', explode(',', $genre));
                                foreach ($genres as $g) {
                                    $cz = $genreMap[$g] ?? $g;
                                    echo '<div>' . htmlspecialchars($cz) . '</div>';
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

                <div id="related-media-panel" class="<?php if (empty($relatedMedia)) echo 'hidden'; ?>" style="flex:1; margin-left:32px;">
                    <?php if (!empty($relatedMedia)): ?>
                        <div>
                            <h4 style="margin-top:20px">Související média</h4>
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


            <h3 style="text-align: center; margin-top: 20px;">Recenze a komentáře</h3>

            <div class="comments-section">
                <?php
                $isEditing = false;
                $editComment = null;
                $userId = $_SESSION['user']['id'] ?? null;
                $userRole = $_SESSION['user']['role'] ?? null;
                if (isset($_GET['edit_comment_id']) && isset($_SESSION['user'])) {
                    foreach ($comments as $c) {
                        if (intval($_GET['edit_comment_id']) === intval($c['id']) && $userId === $c['user_id']) {
                            $isEditing = true;
                            $editComment = $c;
                            break;
                        }
                    }
                }
                ?>
                <?php if (!$isEditing && isset($_SESSION['user'])): ?>
                    <a id="add-comment-form"></a>
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
                <?php endif; ?>

                <!-- zobrazeni komentaru -->
                <?php foreach ($comments as $c): ?>
                    <div class="comment-card" style="position:relative;">
                        <div class="comment-meta">
                            <a href="/WA-2025-KV-semestral_project/my-rating/views/user/Profile.php?id=<?= urlencode($c['user_id']) ?>" class="comment-username" style="color:#8bc34a;font-weight:bold;text-decoration:none;">
                                <?= htmlspecialchars($c['username']) ?>
                            </a>
                            <span class="comment-rating"><?= htmlspecialchars($c['rating']) ?>/10*</span>
                            <span style="float:right;color:#888;"><?= htmlspecialchars($c['created_at']) ?></span>
                        </div>
                        <?php if ($isEditing && $editComment && $editComment['id'] == $c['id']): ?>
                            <a id="edit-comment-form"></a>
                            <form class="comment-form" method="POST"
                                action="/WA-2025-KV-semestral_project/my-rating/controllers/CommentController.php?action=edit">
                                <input type="hidden" name="comment_id" value="<?= htmlspecialchars($editComment['id']) ?>">
                                <input type="hidden" name="media_id" value="<?= htmlspecialchars($mediaId) ?>">
                                <textarea name="content" required><?= htmlspecialchars($editComment['content']) ?></textarea>
                                <div style="align-items: center; display: flex; justify-content: space-between;">
                                    <label style="color:#fff; margin-bottom: 8px;">Hodnocení:
                                        <select name="rating" required>
                                            <option value="">—</option>
                                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                                <option value="<?= $i ?>"<?= ($editComment['rating'] == $i ? ' selected' : '') ?>><?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </label>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <a href="?id=<?= urlencode($mediaId) ?>" class="comment-action-link">Zrušit</a>
                                        <button type="submit" style="margin-top: 4px;">Uložit</button>
                                    </div>
                                </div>
                            </form>
                        <?php else: ?>
                            <div style="padding-bottom:1.4rem;"><?= nl2br(htmlspecialchars($c['content'])) ?></div>
                            <?php if (isset($_SESSION['user'])): ?>
                                <div class="comment-actions">
                                    <?php if ($userId === $c['user_id']): ?>
                                        <a href="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=detail&id=<?= urlencode($mediaId) ?>&edit_comment_id=<?= urlencode($c['id']) ?>#edit-comment-form" class="comment-action-link">upravit</a>
                                        |
                                        <a href="/WA-2025-KV-semestral_project/my-rating/controllers/CommentController.php?action=delete&comment_id=<?= urlencode($c['id']) ?>&media_id=<?= urlencode($mediaId) ?>" class="comment-action-link" onclick="return confirm('Opravdu chcete smazat tento komentář?');">smazat</a>
                                    <?php elseif ($userRole === 'admin'): ?>
                                        <a href="/WA-2025-KV-semestral_project/my-rating/controllers/CommentController.php?action=delete&comment_id=<?= urlencode($c['id']) ?>&media_id=<?= urlencode($mediaId) ?>" class="comment-action-link" onclick="return confirm('Opravdu chcete smazat tento komentář?');">smazat</a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="sidebar"></div>
    </div>

    <script>
    // hamburger menu
    const hamburger = document.getElementById('hamburger-menu');
    const burgerMenuPanel = document.getElementById('burger-menu-panel');
    if (hamburger && burgerMenuPanel) {
        hamburger.style.display = 'block';
        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            burgerMenuPanel.classList.toggle('open');
        });
        document.addEventListener('click', function(e) {
            if (window.innerWidth > 900) return;
            if (!burgerMenuPanel.contains(e.target) && e.target !== hamburger) {
                burgerMenuPanel.classList.remove('open');
            }
        });
    }
    </script>
</body>

</html>