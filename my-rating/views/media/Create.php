<?php
if (!isset($allMedia)) {
    header('Location: /WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=create');
    exit;
}
$navbarContext = 'create';
$cancelTo = '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php';
include __DIR__ . '/../../public/navbar.php';
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Moje-Hodnocení</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/layout.css">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/forms.css">
</head>

<body>
    <div class="main-wrapper">
        <div class="sidebar"></div>

        <div class="content">
            <h2 class="mb-4 text-center">Přidat Médium</h2>
            <form action="/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=create" method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Název</label>
                    <input type="text" id="title" name="title"
                        class="form-control bg-dark text-light border-secondary" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Popis</label>
                    <textarea id="description" name="description"
                        class="form-control bg-dark text-light border-secondary" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Typ</label>
                    <select id="type" name="type"
                        class="form-select bg-dark text-light border-secondary" required>
                        <option value="film">Film</option>
                        <option value="series">Seriál</option>
                        <option value="anime">Anime</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="year" class="form-label">Rok vydání</label>
                    <input type="number" id="year" name="year"
                        class="form-control bg-dark text-light border-secondary" min="1900" max="2100">
                </div>

                <div class="mb-3">
                    <label for="image_url" class="form-label">URL obrázku (plakát)</label>
                    <input type="url" id="image_url" name="image_url"
                        class="form-control bg-dark text-light border-secondary">
                </div>

                <div class="mb-3">
                    <label for="banner_url" class="form-label">URL banneru</label>
                    <input type="url" id="banner_url" name="banner_url"
                        class="form-control bg-dark text-light border-secondary">
                </div>

                <div class="mb-3">
                    <label for="genre" class="form-label">Žánr</label>
                    <select id="genre" name="genre[]"
                        class="form-select bg-dark text-light border-secondary" multiple required>
                        <option value="Action">Akce</option>
                        <option value="Adventure">Dobrodružný</option>
                        <option value="Comedy">Komedie</option>
                        <option value="Drama">Drama</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Romantic">Romantika</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Mystery">Mysteriózní</option>
                        <option value="Thriller">Thriller</option>
                    </select>
                    <small class="text-secondary">Vyberte jeden nebo více žánrů.</small>
                </div>

                <div class="mb-3">
                    <label for="related" class="form-label">Související média</label>
                    <select id="related" name="related[]"
                        class="form-select bg-dark text-light border-secondary" multiple>
                        <?php if (!empty($allMedia)): foreach ($allMedia as $item): ?>
                        <option value="<?= $item['id'] ?>"
                            <?= in_array($item['id'], $relatedIds ?? []) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($item['title']) ?>
                        </option>
                        <?php endforeach;
                        endif; ?>
                    </select>
                    <small class="text-secondary">Vyberte jedno nebo více médií (volitelné).</small>
                </div>

                <div class="mb-3">
                    <label for="author" class="form-label">Autor</label>
                    <input type="text" id="author" name="author"
                        class="form-control bg-dark text-light border-secondary">
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Délka (minuty)</label>
                    <input type="number" id="duration" name="duration"
                        class="form-control bg-dark text-light border-secondary" min="1">
                </div>

                <div class="mb-3">
                    <label for="episode_count" class="form-label">Počet epizod</label>
                    <input type="number" id="episode_count" name="episode_count"
                        class="form-control bg-dark text-light border-secondary" min="1">
                </div>

                <script>
                // nastav episode_count na 1 pokud je typ 'film'
                document.addEventListener('DOMContentLoaded', function() {
                    const typeSelect = document.getElementById('type');
                    const episodeInput = document.getElementById('episode_count');
                    function updateEpisodeCount() {
                        if (typeSelect.value === 'film') {
                            episodeInput.value = 1;
                            episodeInput.readOnly = true;
                        } else {
                            episodeInput.value = '';
                            episodeInput.readOnly = false;
                        }
                    }
                    typeSelect.addEventListener('change', updateEpisodeCount);
                    updateEpisodeCount();
                });
                </script>

                <button type="submit" class="btn btn-success w-100">Uložit</button>
            </form>
        </div>
        <div class="sidebar"></div>
    </div>
</body>

</html>