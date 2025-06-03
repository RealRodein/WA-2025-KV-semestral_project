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
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            if ($action === 'create') {
                $this->CreateMedia();
            } elseif ($action === 'list') {
                $this->ListMedia();
            } else {
                $this->alert('Invalid action.', '../views/media/List.php');
            }
        } else {
            // Default to list if no action is set
            $this->ListMedia();
        }
    }

    private function CreateMedia() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $genre = isset($_POST['genre']) ? implode(',', $_POST['genre']) : '';
            $type = htmlspecialchars($_POST['type']);
            $year = isset($_POST['year']) ? intval($_POST['year']) : null;
            $image_url = htmlspecialchars($_POST['image_url']);
            $banner_url = htmlspecialchars($_POST['banner_url']);
            $user_id = htmlspecialchars($_POST['user_id']);

            if ($this->MediaModel->create($title, $description, $genre, $type, $year, $image_url, $banner_url, $user_id)) {
                header("Location: ../views/media/List.php");
                exit();
            } else {
                $this->alert('Chyba při vytváření média.', '../views/media/Create.php');
            }
        } else {
            $this->alert('Neplatný požadavek.', '../views/media/Create.php');
        }
    }

    private function getFilterOptions() {
        // Get all genre strings from the database
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

    public function ListMedia() {
        $search = $_GET['search'] ?? '';
        $genre = $_GET['genre'] ?? '';
        $year = $_GET['year'] ?? '';
        $type = $_GET['type'] ?? '';

        $filterOptions = $this->getFilterOptions();
        $where = [];
        $params = [];

        if (!empty($_GET['search'])) {
            $where[] = 'title LIKE :search';
            $params[':search'] = '%' . $_GET['search'] . '%';
        }
        if (!empty($_GET['genre'])) {
            $where[] = 'genre LIKE :genre';
            $params[':genre'] = '%' . $_GET['genre'] . '%';
        }
        if (!empty($_GET['year'])) {
            $where[] = 'year = :year';
            $params[':year'] = $_GET['year'];
        }
        if (!empty($_GET['type'])) {
            $where[] = 'type = :type';
            $params[':type'] = $_GET['type'];
        }

        $sql = "SELECT * FROM media";
        if ($where) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $mediaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include '../views/media/List.php';
    }

    private function alert($message, $redirectUrl) {
        echo "<script>
            alert('$message');
            window.location.href = '$redirectUrl';
        </script>";
        exit();
    }
}

// Instantiate the controller and handle the action
$controller = new MediaController();
$controller->handleAction();
?>