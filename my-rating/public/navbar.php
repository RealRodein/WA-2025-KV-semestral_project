<?php
// kontrola session
if (session_status() === PHP_SESSION_NONE) session_start();
$mediaId = $media['id'] ?? null;
$canEdit = false;
$canDelete = false;
// zjisteni prav editace a mazani podle role a vlastnictvi
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    $userId = $_SESSION['user']['id'];
    // editovat muze jen admin nebo trusted nebo vlastnik
    $canEdit = $role === 'admin' || $role === 'trusted' || (isset($media['user_id']) && $userId == $media['user_id']);
    // mazat muze admin vse, trusted jen vlastni (created_by)
    if ($role === 'admin') {
        $canDelete = true;
    } elseif ($role === 'trusted' && isset($media['created_by']) && $userId == $media['created_by']) {
        $canDelete = true;
    } else {
        $canDelete = false;
    }
} else if (isset($_SESSION['user'])) {
    // pokud neni user_id, admin a trusted mohou editovat, admin muze mazat
    $role = $_SESSION['user']['role'];
    $canEdit = $role === 'admin' || $role === 'trusted';
    $canDelete = $role === 'admin';
}
$navbarContext = $navbarContext ?? null;
$cancelTo = $cancelTo ?? '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php';

// zjisteni jestli jsme na strance uzivatelu
$isUsersPage = false;
if (isset($_SERVER['SCRIPT_NAME'])) {
    $isUsersPage = strpos($_SERVER['SCRIPT_NAME'], '/views/user/Users.php') !== false;
}
// zjisteni jestli jsme na profilu
$isProfilePage = false;
if (isset($_SERVER['SCRIPT_NAME'])) {
    $isProfilePage = strpos($_SERVER['SCRIPT_NAME'], '/views/user/Profile.php') !== false;
}

// pomocna funkce pro uzivatelske odkazy
function renderUserLinks($isBurger = false) {
    $user = $_SESSION['user'] ?? null;
    if ($user) {
        $username = htmlspecialchars($user['username']);
        $profileClass = $isBurger ? 'burger-username' : 'user';
        echo "<a class=\"$profileClass\" href=\"/WA-2025-KV-semestral_project/my-rating/views/user/Profile.php\"><strong>$username</strong></a>\n";
    } else {
        echo '<a class="login-btn" href="/WA-2025-KV-semestral_project/my-rating/views/auth/Login.php">Přihlášení</a>';
        echo '<a class="register-btn" href="/WA-2025-KV-semestral_project/my-rating/views/auth/Register.php">Registrace</a>';
    }
}
// menu pro seznam medii
function renderListMenu($isBurger = false) {
    global $isProfilePage;
    $user = $_SESSION['user'] ?? null;
    if ($isProfilePage) return;
    if ($user && ($user['role'] === 'admin' || $user['role'] === 'trusted')) {
        echo '<a href="/WA-2025-KV-semestral_project/my-rating/views/media/Create.php">Přidat</a>';
        $editId = $isBurger ? 'edit-mode-btn-burger' : 'edit-mode-btn';
        $deleteId = $isBurger ? 'delete-mode-btn-burger' : 'delete-mode-btn';
        echo "<a href=\"#\" id=\"$editId\">Upravit</a>";
        echo "<a href=\"#\" id=\"$deleteId\">Smazat</a>";
        if ($user['role'] === 'admin') {
            echo '<a href="/WA-2025-KV-semestral_project/my-rating/views/user/Users.php">Uživatelé</a>';
        }
    }
}
// menu pro detail media
function renderDetailMenu($canEdit, $canDelete, $mediaId) {
    if ($canEdit && $mediaId) {
        echo '<a href="/WA-2025-KV-semestral_project/my-rating/views/media/Edit.php?id=' . urlencode($mediaId) . '">Upravit</a>';
    }
    if ($canDelete && $mediaId) {
        echo '<a href="#" onclick="if(confirm(\'Opravdu chcete smazat toto médium?\')){window.location.href=\'/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=delete&id=' . urlencode($mediaId) . '\';} return false;">Smazat</a>';
    }
}
?>
<div class="header">
    <a class="logo" href="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php">
        <img src="/WA-2025-KV-semestral_project/my-rating/public/images/icon.svg" alt="Moje-Hodnoceni"
            style="width:32px;height:32px;vertical-align:middle;">
    </a>
    <button class="hamburger d-lg-none" id="hamburger-menu" aria-label="Menu"
        style="background:none;border:none;font-size:2rem;line-height:1.2;cursor:pointer;display:none;">
        &#9776;
    </button>
    <div class="menu">
        <?php
        if (!$isUsersPage && !$isProfilePage && $navbarContext === 'list' && isset($_SESSION['user']) &&
            (($_SESSION['user']['role'] === 'admin') || ($_SESSION['user']['role'] === 'trusted'))
            && $navbarContext !== 'profile'
        ) {
            renderListMenu(false);
        } elseif ($navbarContext === 'create' || $navbarContext === 'edit') {
            echo '<a href="' . htmlspecialchars($cancelTo) . '">Zrušit</a>';
        } elseif ($navbarContext === 'detail' && isset($_SESSION['user'])) {
            renderDetailMenu($canEdit, $canDelete, $mediaId);
        }
        ?>
    </div>
    <div class="user">
        <?php renderUserLinks(false); ?>
        <?php if (isset($_SESSION['user'])) {
            echo '<a class="logout-btn" href="/WA-2025-KV-semestral_project/my-rating/controllers/UserController.php?action=logout">Odhlásit se</a>';
        } ?>
    </div>
    <div class="burger-menu" id="burger-menu-panel">
        <?php
        if ($navbarContext === 'create' || $navbarContext === 'edit') {
            echo '<a href="' . htmlspecialchars($cancelTo) . '">Zrušit</a>';
        } elseif ($navbarContext === 'detail') {
            if (isset($_SESSION['user'])) {
                renderUserLinks(true);
                renderDetailMenu($canEdit, $canDelete, $mediaId);
                echo '<a class="logout-btn" href="/WA-2025-KV-semestral_project/my-rating/controllers/UserController.php?action=logout">Odhlásit se</a>';
            } else {
                echo '<a class="login-btn" href="/WA-2025-KV-semestral_project/my-rating/views/auth/Login.php">Přihlášení</a>';
                echo '<a class="register-btn" href="/WA-2025-KV-semestral_project/my-rating/views/auth/Register.php">Registrace</a>';
            }
        } elseif ($navbarContext === 'list' && !$isUsersPage && $navbarContext !== 'profile') {
            if (isset($_SESSION['user'])) {
                renderUserLinks(true);
                renderListMenu(true);
                echo '<a class="logout-btn" href="/WA-2025-KV-semestral_project/my-rating/controllers/UserController.php?action=logout">Odhlásit se</a>';
            } else {
                echo '<a class="login-btn" href="/WA-2025-KV-semestral_project/my-rating/views/auth/Login.php">Přihlášení</a>';
                echo '<a class="register-btn" href="/WA-2025-KV-semestral_project/my-rating/views/auth/Register.php">Registrace</a>';
            }
        } else {
            renderUserLinks(true);
            if (isset($_SESSION['user'])) {
                echo '<a class="logout-btn" href="/WA-2025-KV-semestral_project/my-rating/controllers/UserController.php?action=logout">Odhlásit se</a>';
            }
        }
        ?>
    </div>
</div>
<script>
// ovladani hamburger menu
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
// logika pro vyber editace/mazani v seznamu
(function() {
    if (typeof window.setSelectMode !== 'undefined') return;
    let selectMode = null;
    const editBtn = document.getElementById('edit-mode-btn');
    const deleteBtn = document.getElementById('delete-mode-btn');
    const editBurger = document.getElementById('edit-mode-btn-burger');
    const deleteBurger = document.getElementById('delete-mode-btn-burger');

    function handleCardClick(e) {
        if (!selectMode) return;
        e.preventDefault();
        e.stopImmediatePropagation();
        const link = e.currentTarget;
        const url = new URL(link.href, window.location.origin);
        const id = url.searchParams.get('id');
        if (selectMode === 'edit' && id) {
            window.location.href = '/WA-2025-KV-semestral_project/my-rating/views/media/Edit.php?id=' + encodeURIComponent(id);
        } else if (selectMode === 'delete' && id) {
            if (confirm('opravdu chcete smazat toto medium?')) {
                window.location.href = '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=delete&id=' + encodeURIComponent(id);
            }
        }
        setSelectMode(null);
    }

    function attachCardHandlers() {
        document.querySelectorAll('.media-card-link').forEach(link => {
            link.removeEventListener('click', handleCardClick, false);
            if (selectMode === 'edit' || selectMode === 'delete') {
                link.addEventListener('click', handleCardClick, false);
            }
        });
    }

    window.setSelectMode = function(mode) {
        selectMode = mode;
        document.body.classList.toggle('select-edit-mode', mode === 'edit');
        document.body.classList.toggle('select-delete-mode', mode === 'delete');
        attachCardHandlers();
    };

    function handleEdit(e) {
        e.preventDefault();
        setSelectMode('edit');
        if (typeof burgerMenuPanel !== 'undefined' && burgerMenuPanel) burgerMenuPanel.classList.remove('open');
    }
    function handleDelete(e) {
        e.preventDefault();
        setSelectMode('delete');
        if (typeof burgerMenuPanel !== 'undefined' && burgerMenuPanel) burgerMenuPanel.classList.remove('open');
    }
    if (editBtn) editBtn.onclick = handleEdit;
    if (deleteBtn) deleteBtn.onclick = handleDelete;
    if (editBurger) editBurger.onclick = handleEdit;
    if (deleteBurger) deleteBurger.onclick = handleDelete;

    document.addEventListener('DOMContentLoaded', function() {
        attachCardHandlers();
    });
})();
</script>

