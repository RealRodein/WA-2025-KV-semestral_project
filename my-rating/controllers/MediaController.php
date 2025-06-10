<?php
require_once '../models/Database.php';
require_once '../models/Media.php';

class MediaController {
    private $db;
    private $MediaModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->MediaModel = new Media($this->db);
    }

    public function handleAction() {
        $action = $_GET['action'] ?? 'list';

        switch ($action) {
            case 'create':
                $this->createMedia();
                break;
            case 'list':
                $this->listMedia();
                break;
            case 'detail':
                $this->showDetail();
                break;
            case 'delete':
                $this->deleteMedia();
                break;
            case 'edit':
                $this->editMedia();
                break;
            default:
                $this->alert('Invalid action.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
        }
    }

    private function createMedia() {
        session_start();
        if (!isset($_SESSION['user'])) {
            // nejste prihlaseni
            $this->alert('Nejste přihlášeni.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $genre = isset($_POST['genre']) ? implode(',', $_POST['genre']) : '';
            $type = htmlspecialchars($_POST['type']);
            $year = isset($_POST['year']) ? intval($_POST['year']) : null;
            $image_url = htmlspecialchars($_POST['image_url']);
            $banner_url = htmlspecialchars($_POST['banner_url']);
            $user_id = $_SESSION['user']['id'];
            $related = isset($_POST['related']) ? $_POST['related'] : [];
            $relatedJson = json_encode($related);

            // nove polozky
            $author = isset($_POST['author']) ? htmlspecialchars($_POST['author']) : null;
            $duration = isset($_POST['duration']) ? intval($_POST['duration']) : null;
            $episode_count = isset($_POST['episode_count']) ? intval($_POST['episode_count']) : null;

            if ($this->MediaModel->create(
                $title, $description, $genre, $type, $year, $image_url, $banner_url, $user_id, $relatedJson,
                $author, $duration, $episode_count
            )) {
                // presmerovani na seznam medii po vytvoreni
                header("Location: /WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list");
                exit();
            } else {
                // chyba pri vytvareni media
                $this->alert('Chyba při vytváření média.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
            }
        } else {
            // zobrazit formular pro vytvoreni
            $this->showCreateForm();
        }
    }

    private function showCreateForm() {
        $stmt = $this->db->query("SELECT id, title FROM media ORDER BY title ASC");
        $allMedia = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $relatedIds = [];
        include '../views/media/Create.php';
    }

    private function listMedia() {
        $search = $_GET['search'] ?? '';
        $genre = $_GET['genre'] ?? '';
        $year = $_GET['year'] ?? '';
        $type = $_GET['type'] ?? '';

        $filterOptions = $this->getFilterOptions();
        $where = [];
        $params = [];

        if (!empty($search)) {
            $where[] = 'title LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }
        if (!empty($genre)) {
            $where[] = 'genre LIKE :genre';
            $params[':genre'] = '%' . $genre . '%';
        }
        if (!empty($year)) {
            $where[] = 'year = :year';
            $params[':year'] = $year;
        }
        if (!empty($type)) {
            $where[] = 'type = :type';
            $params[':type'] = $type;
        }

        $sql = "SELECT * FROM media";
        if ($where) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $mediaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($mediaList as &$item) {
            $item['weighted_rating'] = $this->getWeightedRating($item['id']);
        }
        unset($item);

        include '../views/media/List.php';
    }

    private function showDetail() {
        session_start();
        $mediaId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($mediaId > 0) {
            $stmt = $this->db->prepare("SELECT * FROM media WHERE id = :id");
            $stmt->execute([':id' => $mediaId]);
            $media = $stmt->fetch(PDO::FETCH_ASSOC);

            // oboustranne vyhledavani souvisejicich medii
            $relatedIds = [];
            if ($media && !empty($media['related'])) {
                // nejdrive zkusi json decode
                $relatedIds = json_decode($media['related'], true);
                if (!is_array($relatedIds)) {
                    // pokud selze, zkusi csv
                    $relatedIds = array_filter(array_map('trim', explode(',', $media['related'])));
                }
                $relatedIds = array_map('intval', $relatedIds);
            }

            // ziskani medii na ktera toto medium odkazuje (primy vztah)
            $directRelated = [];
            if (count($relatedIds) > 0) {
                $placeholders = implode(',', array_fill(0, count($relatedIds), '?'));
                $stmt4 = $this->db->prepare("SELECT id, title, image_url FROM media WHERE id IN ($placeholders)");
                $stmt4->execute($relatedIds);
                $directRelated = $stmt4->fetchAll(PDO::FETCH_ASSOC);
            }

            // ziskani medii ktera odkazuji na toto medium (reverzni vztah)
            $reverseRelated = [];

            // json reverzni vyhledavani (hleda jak retezec, tak cislo)
            $stmt2 = $this->db->prepare(
                "SELECT id, title, image_url FROM media 
                 WHERE JSON_VALID(related) 
                 AND (JSON_CONTAINS(related, :mid_int) OR JSON_CONTAINS(related, :mid_str))"
            );
            $stmt2->execute([
                ':mid_int' => json_encode((int)$mediaId),
                ':mid_str' => json_encode((string)$mediaId)
            ]);
            $reverseRelated = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            // reverzni vyhledavani pro csv
            $stmt3 = $this->db->prepare("SELECT id, title, image_url, related FROM media WHERE related LIKE :likeid AND (JSON_VALID(related) = 0 OR JSON_VALID(related) IS NULL)");
            $likeid = '%'.($mediaId).'%';
            $stmt3->execute([':likeid' => $likeid]);
            $csvReverse = $stmt3->fetchAll(PDO::FETCH_ASSOC);
            foreach ($csvReverse as $row) {
                $ids = array_map('intval', array_filter(array_map('trim', explode(',', $row['related']))));
                if (in_array($mediaId, $ids)) {
                    $reverseRelated[] = [
                        'id' => $row['id'],
                        'title' => $row['title'],
                        'image_url' => $row['image_url']
                    ];
                }
            }

            // slouceni a odstraneni duplicit (podle id, a ne sebe sama)
            $allRelated = [];
            foreach (array_merge($directRelated, $reverseRelated) as $rel) {
                if ($rel['id'] != $mediaId) {
                    $allRelated[$rel['id']] = $rel;
                }
            }
            $relatedMedia = array_values($allRelated);

            foreach ($relatedMedia as &$rel) {
                $rel['weighted_rating'] = $this->getWeightedRating($rel['id']);
            }
            unset($rel);

            // komentare
            $stmt = $this->db->prepare("
                SELECT c.*, u.username 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.media_id = :media_id 
                ORDER BY c.created_at DESC
            ");
            $stmt->execute([':media_id' => $mediaId]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // vypocet prumerneho hodnoceni
            $ratings = array_column($comments, 'rating');
            $ratings = array_filter($ratings, fn($r) => $r !== null && $r !== '');
            $meanScore = count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 2) : null;

            include '../views/media/Detail.php';
            return;
        }
        $this->alert('Médium nebylo nalezeno.', '../views/media/List.php');
    }

    private function editMedia() {
        session_start();
        if (!isset($_SESSION['user'])) {
            // nejste prihlaseni
            $this->alert('Nejste přihlášeni.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
        }
        $user = $_SESSION['user'];
        $mediaId = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : null);
        if (!$mediaId) {
            // neplatne medium
            $this->alert('Neplatné médium.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
        }
        // nacteni media pro kontrolu vlastnictvi
        $stmt = $this->db->prepare('SELECT * FROM media WHERE id = :id');
        $stmt->execute([':id' => $mediaId]);
        $media = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$media) {
            // medium nebylo nalezeno
            $this->alert('Médium nebylo nalezeno.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
        }
        $isOwner = isset($media['user_id']) && $media['user_id'] == $user['id'];
        $isTrusted = $user['role'] === 'trusted';
        $isAdmin = $user['role'] === 'admin';
        // editace: admin a trusted mohou upravit vse, uzivatel nemuze upravit
        if (!($isAdmin || $isTrusted)) {
            // nemate opravneni k uprave tohoto media
            $this->alert('Nemáte oprávnění k úpravě tohoto média.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // aktualizace media
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $genre = isset($_POST['genre']) ? implode(',', $_POST['genre']) : '';
            $type = htmlspecialchars($_POST['type']);
            $year = isset($_POST['year']) ? intval($_POST['year']) : null;
            $image_url = htmlspecialchars($_POST['image_url']);
            $banner_url = htmlspecialchars($_POST['banner_url']);
            $related = isset($_POST['related']) ? $_POST['related'] : [];
            $relatedJson = json_encode($related);
            $author = isset($_POST['author']) ? htmlspecialchars($_POST['author']) : null;
            $duration = isset($_POST['duration']) ? intval($_POST['duration']) : null;
            $episode_count = isset($_POST['episode_count']) ? intval($_POST['episode_count']) : null;
            $stmt = $this->db->prepare('UPDATE media SET title = :title, description = :description, genre = :genre, type = :type, year = :year, image_url = :image_url, banner_url = :banner_url, related = :related, author = :author, duration = :duration, episode_count = :episode_count WHERE id = :id');
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':genre' => $genre,
                ':type' => $type,
                ':year' => $year,
                ':image_url' => $image_url,
                ':banner_url' => $banner_url,
                ':related' => $relatedJson,
                ':author' => $author,
                ':duration' => $duration,
                ':episode_count' => $episode_count,
                ':id' => $mediaId
            ]);
            // medium bylo uspesne upraveno
            $this->alert('Médium bylo úspěšně upraveno.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
        } else {
            // presmerovani na formular pro editaci (nema by se stat pokud je spravne routovano)
            header('Location: ../views/media/Edit.php?id=' . $mediaId);
            exit();
        }
    }

    private function deleteMedia() {
        session_start();
        if (!isset($_SESSION['user'])) {
            // nejste prihlaseni
            $this->alert('Nejste přihlášeni.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
        }
        $user = $_SESSION['user'];
        $mediaId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($mediaId <= 0) {
            // neplatne medium
            $this->alert('Neplatné médium.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
        }
        // nacteni media pro kontrolu vlastnictvi
        $stmt = $this->db->prepare('SELECT * FROM media WHERE id = :id');
        $stmt->execute([':id' => $mediaId]);
        $media = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$media) {
            // medium nebylo nalezeno
            $this->alert('Médium nebylo nalezeno.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
        }
        $isOwner = isset($media['user_id']) && $media['user_id'] == $user['id'];
        $isTrusted = $user['role'] === 'trusted';
        $isAdmin = $user['role'] === 'admin';
        // mazani: admin muze mazat vse, trusted muze mazat jen sve, uzivatel nemuze mazat
        if (!($isAdmin || ($isTrusted && $isOwner))) {
            // nemate opravneni ke smazani tohoto media
            $this->alert('Nemáte oprávnění ke smazání tohoto média.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
        }
        // smazani media
        $stmt = $this->db->prepare('DELETE FROM media WHERE id = :id');
        $stmt->execute([':id' => $mediaId]);
        // medium bylo uspesne smazano
        $this->alert('Médium bylo úspěšně smazáno.', '/WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=list');
    }

    private function getFilterOptions() {
        $genreRows = $this->db->query("SELECT genre FROM media WHERE genre IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
        $allGenres = [];
        foreach ($genreRows as $row) {
            foreach (explode(',', $row) as $g) {
                $g = trim($g);
                if ($g !== '' && !in_array($g, $allGenres)) {
                    $allGenres[] = $g;
                }
            }
        }
        sort($allGenres);

        $options = [
            'genres' => $allGenres,
            'years' => $this->db->query("SELECT DISTINCT year FROM media WHERE year IS NOT NULL ORDER BY year DESC")->fetchAll(PDO::FETCH_COLUMN),
            'types' => $this->db->query("SELECT DISTINCT type FROM media WHERE type IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN),
        ];
        return $options;
    }

    private function alert($message, $redirectUrl) {
        echo "<script>\n alert('$message');\n window.location.href = '$redirectUrl';\n </script>";
        exit();
    }

    private function getWeightedRating($mediaId) {
        $stmt = $this->db->prepare("SELECT rating FROM comments WHERE media_id = :media_id AND rating IS NOT NULL");
        $stmt->execute([':media_id' => $mediaId]);
        $ratings = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $ratings = array_filter($ratings, fn($r) => $r !== null && $r !== '');
        if (count($ratings) === 0) return null;
        // jednoduche prumerovani, nebo zde muzete pouzit vahovany vzorec
        return round(array_sum($ratings) / count($ratings), 2);
    }
}

$controller = new MediaController();
$controller->handleAction();
?>