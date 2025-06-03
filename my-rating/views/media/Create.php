<?php
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
            <h2 class="mb-4 text-center">Přidat Médium</h2>
            <form action="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=create" method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Název</label>
                    <input type="text" id="title" name="title" class="form-control bg-dark text-light border-secondary" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Popis</label>
                    <textarea id="description" name="description" class="form-control bg-dark text-light border-secondary" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Typ</label>
                    <select id="type" name="type" class="form-select bg-dark text-light border-secondary" required>
                        <option value="film">Film</option>
                        <option value="series">Seriál</option>
                        <option value="anime">Anime</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="year" class="form-label">Rok vydání</label>
                    <input type="number" id="year" name="year" class="form-control bg-dark text-light border-secondary" min="1900" max="2100">
                </div>
                <div class="mb-3">
                    <label for="image_url" class="form-label">URL obrázku (plakát)</label>
                    <input type="url" id="image_url" name="image_url" class="form-control bg-dark text-light border-secondary">
                </div>
                <div class="mb-3">
                    <label for="banner_url" class="form-label">URL banneru</label>
                    <input type="url" id="banner_url" name="banner_url" class="form-control bg-dark text-light border-secondary">
                </div>
                <div class="mb-3">
                    <label for="user_id" class="form-label">ID uživatele (dočasně)</label>
                    <input type="number" id="user_id" name="user_id" class="form-control bg-dark text-light border-secondary" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="genre" class="form-label">Žánr</label>
                    <select id="genre" name="genre[]" class="form-select bg-dark text-light border-secondary" multiple required>
                        <option value="Action">Akce</option>
                        <option value="Adventure">Dobrodružný</option>
                        <option value="Comedy">Komedie</option>
                        <option value="Drama">Drama</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Romance">Romantika</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <!-- Add more genres as needed -->
                    </select>
                    <small class="text-secondary">Podržte Ctrl (nebo Cmd) pro výběr více žánrů.</small>
                </div>
                <button type="submit" class="btn btn-success w-100">Uložit</button>
            </form>
        </div>
        <div class="sidebar"></div>
    </div>
</body>
</html>