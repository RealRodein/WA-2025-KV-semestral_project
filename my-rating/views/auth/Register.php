<?php
session_start();
$form_data = $_SESSION['form_data'] ?? [];
session_regenerate_id(true);
unset($_SESSION['form_data']);
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
    </div>

    <div class="main-wrapper">
        <div class="sidebar"></div>

        <div class="content">
            <h2 class="mb-4 text-center">Registrace</h2>
            <form action="../../controllers/UserController.php?action=register" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Uživatelské jméno</label>
                    <input type="text" id="username" name="username" class="form-control bg-dark text-light border-secondary" value="<?= htmlspecialchars($form_data['username'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control bg-dark text-light border-secondary" value="<?= htmlspecialchars($form_data['email'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Heslo</label>
                    <input type="password" id="password" name="password" class="form-control bg-dark text-light border-secondary" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Registrovat se</button>
            </form>
        </div>
        <div class="sidebar"></div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>