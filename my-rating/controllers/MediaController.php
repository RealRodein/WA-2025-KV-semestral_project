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
                $this->alert('Invalid action.', '../views/media/List.php');
        }
    }

    private function createMedia() {
        session_start();
        if (!isset($_SESSION['user'])) {
            $this->alert('Nejste přihlášeni.', '../views/media/List.php');
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

            // New fields
            $author = isset($_POST['author']) ? htmlspecialchars($_POST['author']) : null;
            $duration = isset($_POST['duration']) ? intval($_POST['duration']) : null;
            $episode_count = isset($_POST['episode_count']) ? intval($_POST['episode_count']) : null;

            if ($this->MediaModel->create(
                $title, $description, $genre, $type, $year, $image_url, $banner_url, $user_id, $relatedJson,
                $author, $duration, $episode_count
            )) {
                header("Location: ../views/media/List.php");
                exit();
            } else {
                $this->alert('Chyba při vytváření média.', '../views/media/Create.php');
            }
        } else {
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

            // --- Two-way related media fetch ---
            $relatedIds = [];
            if ($media && !empty($media['related'])) {
                // Try JSON decode first
                $relatedIds = json_decode($media['related'], true);
                if (!is_array($relatedIds)) {
                    // Fallback: try CSV
                    $relatedIds = array_filter(array_map('trim', explode(',', $media['related'])));
                }
                $relatedIds = array_map('intval', $relatedIds);
            }

            // Fetch media that this media relates to (direct)
            $directRelated = [];
            if (count($relatedIds) > 0) {
                $placeholders = implode(',', array_fill(0, count($relatedIds), '?'));
                $stmt4 = $this->db->prepare("SELECT id, title, image_url FROM media WHERE id IN ($placeholders)");
                $stmt4->execute($relatedIds);
                $directRelated = $stmt4->fetchAll(PDO::FETCH_ASSOC);
            }

            // Fetch media that are related to this media (reverse)
            $reverseRelated = [];

            // JSON reverse (search for both string and int)
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

            // CSV reverse
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

            // Merge and deduplicate (by id, and not self)
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

            // --- Comments ---
            $stmt = $this->db->prepare("
                SELECT c.*, u.username 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.media_id = :media_id 
                ORDER BY c.created_at DESC
            ");
            $stmt->execute([':media_id' => $mediaId]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate mean score
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
            $this->alert('Nejste přihlášeni.', '../views/media/List.php');
        }
        $user = $_SESSION['user'];
        $mediaId = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : null);
        if (!$mediaId) {
            $this->alert('Neplatné médium.', '../views/media/List.php');
        }
        // Fetch media to check ownership
        $stmt = $this->db->prepare('SELECT * FROM media WHERE id = :id');
        $stmt->execute([':id' => $mediaId]);
        $media = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$media) {
            $this->alert('Médium nebylo nalezeno.', '../views/media/List.php');
        }
        $isOwner = isset($media['user_id']) && $media['user_id'] == $user['id'];
        $isTrusted = $user['role'] === 'trusted';
        $isAdmin = $user['role'] === 'admin';
        // Edit: admin and trusted can edit all, user cannot edit
        if (!($isAdmin || $isTrusted)) {
            $this->alert('Nemáte oprávnění k úpravě tohoto média.', '../views/media/List.php');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update media
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
            $this->alert('Médium bylo úspěšně upraveno.', '../views/media/List.php');
        } else {
            // Redirect to edit form (should not happen if routed correctly)
            header('Location: ../views/media/Edit.php?id=' . $mediaId);
            exit();
        }
    }

    private function deleteMedia() {
        session_start();
        if (!isset($_SESSION['user'])) {
            $this->alert('Nejste přihlášeni.', '../views/media/List.php');
        }
        $user = $_SESSION['user'];
        $mediaId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($mediaId <= 0) {
            $this->alert('Neplatné médium.', '../views/media/List.php');
        }
        // Fetch media to check ownership
        $stmt = $this->db->prepare('SELECT * FROM media WHERE id = :id');
        $stmt->execute([':id' => $mediaId]);
        $media = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$media) {
            $this->alert('Médium nebylo nalezeno.', '../views/media/List.php');
        }
        $isOwner = isset($media['user_id']) && $media['user_id'] == $user['id'];
        $isTrusted = $user['role'] === 'trusted';
        $isAdmin = $user['role'] === 'admin';
        // Delete: admin can delete all, trusted can only delete own, user cannot delete
        if (!($isAdmin || ($isTrusted && $isOwner))) {
            $this->alert('Nemáte oprávnění ke smazání tohoto média.', '../views/media/List.php');
        }
        // Delete media
        $stmt = $this->db->prepare('DELETE FROM media WHERE id = :id');
        $stmt->execute([':id' => $mediaId]);
        $this->alert('Médium bylo úspěšně smazáno.', '../views/media/List.php');
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
        echo "<script>
            alert('$message');
            window.location.href = '$redirectUrl';
        </script>";
        exit();
    }

    private function getWeightedRating($mediaId) {
        $stmt = $this->db->prepare("SELECT rating FROM comments WHERE media_id = :media_id AND rating IS NOT NULL");
        $stmt->execute([':media_id' => $mediaId]);
        $ratings = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $ratings = array_filter($ratings, fn($r) => $r !== null && $r !== '');
        if (count($ratings) === 0) return null;
        // Simple mean, or replace with weighted formula if you want
        return round(array_sum($ratings) / count($ratings), 2);
    }
}

// Instantiate the controller and handle the action
$controller = new MediaController();
$controller->handleAction();
?>