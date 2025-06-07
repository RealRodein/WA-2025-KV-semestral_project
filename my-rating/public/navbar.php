<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$mediaId = $media['id'] ?? null;
$canEdit = false;
$canDelete = false;
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    $userId = $_SESSION['user']['id'];
    // Edit: admin and trusted can edit all, user can edit own
    $canEdit = $role === 'admin' || $role === 'trusted' || (isset($media['user_id']) && $userId == $media['user_id']);
    // Delete: admin can delete all, trusted can delete own, user can delete own
    $canDelete = $role === 'admin' || ($role === 'trusted' && isset($media['user_id']) && $userId == $media['user_id']) || ($role === 'user' && isset($media['user_id']) && $userId == $media['user_id']);
}
$navbarContext = $navbarContext ?? null;
$cancelTo = $cancelTo ?? '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php';
?>
<div class="header">
    <a class="logo" href="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php">
        <img src="/WA-2025-KV-semestral_project/my-rating/public/images/icon.svg" alt="Moje-Hodnocení"
            style="width:32px;height:32px;vertical-align:middle;">
    </a>
    <button class="hamburger d-lg-none" id="hamburger-menu" aria-label="Menu"
        style="background:none;border:none;font-size:2rem;line-height:1.2;cursor:pointer;display:none;">
        &#9776;
    </button>
    <div class="menu">
        <?php if (
            $navbarContext === 'list' && isset($_SESSION['user']) &&
            (($_SESSION['user']['role'] === 'admin') || ($_SESSION['user']['role'] === 'trusted'))
        ): ?>
            <a href="/WA-2025-KV-semestral_project/my-rating/views/media/Create.php">Přidat</a>
            <a href="#" id="edit-mode-btn">Upravit</a>
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <a href="#" id="delete-mode-btn">Smazat</a>
                <a href="/WA-2025-KV-semestral_project/my-rating/views/user/Users.php">Uživatelé</a>
            <?php elseif ($_SESSION['user']['role'] === 'trusted'): ?>
                <a href="#" id="delete-mode-btn">Smazat</a>
            <?php endif; ?>
        <?php elseif ($navbarContext === 'create' || $navbarContext === 'edit'): ?>
            <a href="<?= htmlspecialchars($cancelTo) ?>">Zrušit</a>
        <?php elseif ($navbarContext === 'detail'): ?>
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($canEdit && $mediaId): ?>
                    <a href="/WA-2025-KV-semestral_project/my-rating/views/media/Edit.php?id=<?= urlencode($mediaId) ?>">Upravit</a>
                <?php endif; ?>
                <?php if ($canDelete && $mediaId): ?>
                    <a href="#" onclick="if(confirm('Opravdu chcete smazat toto médium?')){window.location.href='/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=delete&id=<?= urlencode($mediaId) ?>';} return false;">Smazat</a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
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
    <div class="burger-menu" id="burger-menu-panel">
        <?php if ($navbarContext === 'create' || $navbarContext === 'edit'): ?>
            <a href="<?= htmlspecialchars($cancelTo) ?>">Zrušit</a>
        <?php elseif ($navbarContext === 'detail'): ?>
            <?php if (isset($_SESSION['user'])): ?>
                <span><?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                <?php if ($canEdit && $mediaId): ?>
                    <a href="/WA-2025-KV-semestral_project/my-rating/views/media/Edit.php?id=<?= urlencode($mediaId) ?>">Upravit</a>
                <?php endif; ?>
                <?php if ($canDelete && $mediaId): ?>
                    <a href="#" onclick="if(confirm('Opravdu chcete smazat toto médium?')){window.location.href='/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=delete&id=<?= urlencode($mediaId) ?>';} return false;">Smazat</a>
                <?php endif; ?>
                <a href="/WA-2025-KV-semestral_project/my-rating/controllers/UserController.php?action=logout">Odhlásit se</a>
            <?php else: ?>
                <a href="/WA-2025-KV-semestral_project/my-rating/views/auth/Login.php">Přihlášení</a>
                <a href="/WA-2025-KV-semestral_project/my-rating/views/auth/Register.php">Registrace</a>
            <?php endif; ?>
        <?php elseif ($navbarContext === 'list'): ?>
            <?php if (isset($_SESSION['user'])): ?>
                <span><?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                    <a href="/WA-2025-KV-semestral_project/my-rating/views/media/Create.php">Přidat</a>
                    <a href="#" id="edit-mode-btn-burger">Upravit</a>
                    <a href="#" id="delete-mode-btn-burger">Smazat</a>
                    <a href="/WA-2025-KV-semestral_project/my-rating/views/user/Users.php">Uživatelé</a>
                <?php elseif ($_SESSION['user']['role'] === 'trusted'): ?>
                    <a href="/WA-2025-KV-semestral_project/my-rating/views/media/Create.php">Přidat</a>
                    <a href="#" id="edit-mode-btn-burger">Upravit</a>
                    <a href="#" id="delete-mode-btn-burger">Smazat</a>
                <?php endif; ?>
                <a href="/WA-2025-KV-semestral_project/my-rating/controllers/UserController.php?action=logout">Odhlásit se</a>
            <?php else: ?>
                <a href="/WA-2025-KV-semestral_project/my-rating/views/auth/Login.php">Přihlášení</a>
                <a href="/WA-2025-KV-semestral_project/my-rating/views/auth/Register.php">Registrace</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<script>
// Hamburger menu toggle for navbar
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

// Centralized select mode logic for edit/delete in list view
(function() {
    if (typeof window.setSelectMode !== 'undefined') return; // Prevent double definition
    let selectMode = null;
    const editBtn = document.getElementById('edit-mode-btn');
    const deleteBtn = document.getElementById('delete-mode-btn');
    const editBurger = document.getElementById('edit-mode-btn-burger');
    const deleteBurger = document.getElementById('delete-mode-btn-burger');

    window.setSelectMode = function(mode) {
        selectMode = mode;
        document.body.classList.toggle('select-edit-mode', mode === 'edit');
        document.body.classList.toggle('select-delete-mode', mode === 'delete');
    };

    function handleEdit(e) {
        e.preventDefault();
        setSelectMode('edit');
        if (burgerMenuPanel) burgerMenuPanel.classList.remove('open');
    }
    function handleDelete(e) {
        e.preventDefault();
        setSelectMode('delete');
        if (burgerMenuPanel) burgerMenuPanel.classList.remove('open');
    }
    if (editBtn) editBtn.onclick = handleEdit;
    if (deleteBtn) deleteBtn.onclick = handleDelete;
    if (editBurger) editBurger.onclick = handleEdit;
    if (deleteBurger) deleteBurger.onclick = handleDelete;

    document.addEventListener('click', function(e) {
        if (!selectMode) return;
        const link = e.target.closest('.media-card-link');
        if (!link) return;
        e.preventDefault();
        const url = new URL(link.href, window.location.origin);
        const id = url.searchParams.get('id');
        if (selectMode === 'edit' && id) {
            window.location.href = '/WA-2025-KV-semestral_project/my-rating/views/media/Edit.php?id=' + encodeURIComponent(id);
        } else if (selectMode === 'delete' && id) {
            if (confirm('Opravdu chcete smazat toto médium?')) {
                window.location.href = '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=delete&id=' + encodeURIComponent(id);
            }
        }
        setSelectMode(null);
    });
})();
</script>

