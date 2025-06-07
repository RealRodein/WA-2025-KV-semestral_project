<?php
session_start();
require_once '../../models/Database.php';
require_once '../../models/Media.php';

// Get user and media ID from GET or POST
$user = $_SESSION['user'] ?? null;
$mediaId = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : null);

// Permission check function
function canEditMedia($user, $mediaId, $mediaModel) {
    if (!$user || !$mediaId) return false;
    $media = $mediaModel->getById($mediaId);
    if (!$media) return false;
    return $user['role'] === 'admin' || $user['role'] === 'trusted' || $media['user_id'] == $user['id'];
}

$db = (new Database())->getConnection();
$mediaModel = new Media($db);


// Fetch media and all media for related select
$media = $mediaModel->getById($mediaId);
$allMedia = $mediaModel->getAll();
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
<?php
$navbarContext = 'edit';
$media = $media ?? null;
$cancelTo = isset($media['id']) ? '/WA-2025-KV-semestral_project/my-rating/views/media/Detail.php?id=' . urlencode($media['id']) : '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php';
include __DIR__ . '/../../public/navbar.php';
?>

    <div class="main-wrapper">
        <div class="sidebar"></div>

        <div class="content">
            <h2 class="mb-4 text-center">Upravit Médium</h2>
            <form action="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=edit&id=<?= htmlspecialchars($mediaId) ?>" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($mediaId) ?>">
                <div class="mb-3">
                    <label for="title" class="form-label">Název</label>
                    <input type="text" id="title" name="title"
                        class="form-control bg-dark text-light border-secondary" required
                        value="<?= htmlspecialchars($media['title'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Popis</label>
                    <textarea id="description" name="description"
                        class="form-control bg-dark text-light border-secondary" rows="3"><?= htmlspecialchars($media['description'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Typ</label>
                    <select id="type" name="type"
                        class="form-select bg-dark text-light border-secondary" required>
                        <option value="film" <?= ($media['type'] ?? '') === 'film' ? 'selected' : '' ?>>Film</option>
                        <option value="series" <?= ($media['type'] ?? '') === 'series' ? 'selected' : '' ?>>Seriál</option>
                        <option value="anime" <?= ($media['type'] ?? '') === 'anime' ? 'selected' : '' ?>>Anime</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="year" class="form-label">Rok vydání</label>
                    <input type="number" id="year" name="year"
                        class="form-control bg-dark text-light border-secondary" min="1900" max="2100"
                        value="<?= htmlspecialchars($media['year'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="image_url" class="form-label">URL obrázku (plakát)</label>
                    <input type="url" id="image_url" name="image_url"
                        class="form-control bg-dark text-light border-secondary"
                        value="<?= htmlspecialchars($media['image_url'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="banner_url" class="form-label">URL banneru</label>
                    <input type="url" id="banner_url" name="banner_url"
                        class="form-control bg-dark text-light border-secondary"
                        value="<?= htmlspecialchars($media['banner_url'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="genre" class="form-label">Žánr</label>
                    <?php
                    $selectedGenres = isset($media['genre']) ? array_map('trim', explode(',', $media['genre'])) : [];
                    ?>
                    <select id="genre" name="genre[]"
                        class="form-select bg-dark text-light border-secondary" multiple required>
                        <option value="Action" <?= in_array('Action', $selectedGenres) ? 'selected' : '' ?>>Akce</option>
                        <option value="Adventure" <?= in_array('Adventure', $selectedGenres) ? 'selected' : '' ?>>Dobrodružný</option>
                        <option value="Comedy" <?= in_array('Comedy', $selectedGenres) ? 'selected' : '' ?>>Komedie</option>
                        <option value="Drama" <?= in_array('Drama', $selectedGenres) ? 'selected' : '' ?>>Drama</option>
                        <option value="Fantasy" <?= in_array('Fantasy', $selectedGenres) ? 'selected' : '' ?>>Fantasy</option>
                        <option value="Romance" <?= in_array('Romance', $selectedGenres) ? 'selected' : '' ?>>Romantika</option>
                        <option value="Sci-Fi" <?= in_array('Sci-Fi', $selectedGenres) ? 'selected' : '' ?>>Sci-Fi</option>
                    </select>
                    <small class="text-secondary">Pro výběr vícera podržte Ctrl.</small>
                </div>

                <div class="mb-3">
                    <label for="related" class="form-label">Související média</label>
                    <select id="related" name="related[]"
                        class="form-select bg-dark text-light border-secondary" multiple>
                        <?php if (!empty($allMedia)): foreach ($allMedia as $item): ?>
                        <option value="<?= $item['id'] ?>"
                            <?= isset($media['related']) && in_array($item['id'], array_map('intval', json_decode($media['related'], true) ?? [])) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($item['title']) ?>
                        </option>
                        <?php endforeach;
                        endif; ?>
                    </select>
                    <small class="text-secondary">Pro výběr vícera podržte Ctrl.</small>
                </div>

                <button type="submit" class="btn btn-success w-100">Uložit</button>
            </form>
        </div>
        <div class="sidebar"></div>
    </div>
</body>

</html>