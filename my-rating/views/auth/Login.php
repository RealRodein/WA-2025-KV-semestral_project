<?php
session_start();
session_regenerate_id(true);
require_once __DIR__ . '/../../public/navbar.php';
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Moje-Hodnocení</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/layout.css">
</head>
<body>
    <div class="main-wrapper">
        <div class="sidebar"></div>

        <div class="content">
            <h2 class="mb-4 text-center">Přihlášení</h2>
            <form action="../../controllers/UserController.php?action=login" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Uživatelské jméno</label>
                    <input type="text" id="username" name="username" class="form-control bg-dark text-light border-secondary" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Heslo</label>
                    <input type="password" id="password" name="password" class="form-control bg-dark text-light border-secondary" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Přihlásit se</button>
            </form>
        </div>

        <div class="sidebar"></div>
    </div>
</body>
</html>