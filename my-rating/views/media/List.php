<?php
session_start();
session_regenerate_id(true);
if (!isset($mediaList) || !isset($filterOptions)) {
    $query = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
    header('Location: ../../controllers/MediaController.php?action=list' . $query);
    exit();
}
$navbarContext = 'list';
include __DIR__ . '/../../public/navbar.php';
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
    <div class="main-wrapper">

        <div class="sidebar"></div>

        <div class="content">
            <!-- Filter Bar -->
            <form method="GET" action="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php"
                class="filter-bar d-flex gap-3 mb-4">
                <input type="hidden" name="action" value="list">
                <input type="text" name="search" placeholder="Hledat" class="form-control"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

                <?php
                $filters = [
                    'genre' => 'Žánr',
                    'year' => 'Rok',
                    'type' => 'Typ'
                ];
                foreach ($filters as $filter => $placeholder): ?>
                    <select name="<?= $filter ?>" class="form-select">
                        <option value=""><?= $placeholder ?></option>
                        <?php foreach ($filterOptions[$filter . 's'] as $option): ?>
                            <option value="<?= htmlspecialchars($option) ?>" <?= (isset($_GET[$filter]) && $_GET[$filter] == $option ? ' selected' : '') ?>>
                                <?= htmlspecialchars($option) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-primary">Filtrovat</button>
            </form>

            <!-- Display Filtered Media -->
            <div class="media-grid">
                <?php if (empty($mediaList)): ?>
                    <div class="w-100 text-center text-secondary py-5" style="grid-column: 1 / -1;">
                        Žádné médium nebylo nalezeno.
                    </div>
                <?php else: ?>
                    <?php foreach ($mediaList as $item): ?>
                        <?php
                            $canDelete = false;
                            // Use created_by for permission check
                            $itemUserId = isset($item['created_by']) ? $item['created_by'] : (isset($item['user_id']) ? $item['user_id'] : null);
                            if (isset($_SESSION['user'])) {
                                $role = $_SESSION['user']['role'];
                                $userId = $_SESSION['user']['id'];
                                if ($role === 'admin') {
                                    $canDelete = true;
                                } elseif ($role === 'trusted' || $role === 'user') {
                                    $canDelete = $itemUserId !== null && $itemUserId == $userId;
                                }
                            }
                        ?>
                        <a href="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=detail&id=<?= urlencode($item['id']) ?>"
                            class="media-card-link" data-can-delete="<?= $canDelete ? '1' : '0' ?>" style="text-decoration:none;color:inherit;">
                            <div class="media-card<?= $canDelete ? ' can-delete' : '' ?>">
                                <div class="media-poster-hoverbox">
                                    <img src="<?= htmlspecialchars($item['image_url']) ?>"
                                        alt="<?= htmlspecialchars($item['title']) ?>">
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

<style>
</style>
<script>
(function() {
    let selectMode = null;
    const editBtn = document.getElementById('edit-mode-btn');
    const deleteBtn = document.getElementById('delete-mode-btn');
    const editBurger = document.getElementById('edit-mode-btn-burger');
    const deleteBurger = document.getElementById('delete-mode-btn-burger');

    window.setSelectMode = function(mode) {
        selectMode = mode;
        document.body.classList.toggle('select-edit-mode', mode === 'edit');
        document.body.classList.toggle('select-delete-mode', mode === 'delete');
        updateDeleteFilter();
    };

    function handleEdit(e) {
        e.preventDefault();
        setSelectMode('edit');
    }
    function handleDelete(e) {
        e.preventDefault();
        setSelectMode('delete');
    }
    if (editBtn) editBtn.onclick = handleEdit;
    if (deleteBtn) deleteBtn.onclick = handleDelete;
    if (editBurger) editBurger.onclick = handleEdit;
    if (deleteBurger) deleteBurger.onclick = handleDelete;

    function updateDeleteFilter() {
        const isDeleteMode = document.body.classList.contains('select-delete-mode');
        document.querySelectorAll('.media-card-link').forEach(link => {
            const canDelete = link.getAttribute('data-can-delete') === '1';
            if (isDeleteMode) {
                link.style.display = canDelete ? '' : 'none';
            } else {
                link.style.display = '';
            }
        });
    }
    document.addEventListener('DOMContentLoaded', updateDeleteFilter);
})();
</script>