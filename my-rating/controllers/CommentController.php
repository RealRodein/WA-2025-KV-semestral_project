<?php
session_start();
session_regenerate_id(true);
require_once '../models/Database.php';
require_once '../models/Comment.php';

class CommentController {
    private $db;
    private $commentModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->commentModel = new Comment($this->db);
    }

    public function handleAction() {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            switch ($action) {
                case 'add':
                    $this->add();
                    break;
                case 'edit':
                    $this->edit();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                default:
                    echo "Invalid action.";
            }
        }
    }

    private function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $media_id = intval($_POST['media_id']);
            $user_id = $_SESSION['user']['id'];
            $content = trim($_POST['content']);
            $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
            if ($content) {
                $this->commentModel->create($media_id, $user_id, $content, $rating);
            }
            header("Location: /WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=detail&id=" . urlencode($media_id));
            exit;
        }
    }

    private function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $comment_id = intval($_POST['comment_id']);
            $content = trim($_POST['content']);
            $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
            if ($content) {
                $this->commentModel->update($comment_id, $content, $rating, $_SESSION['user']['id']);
            }
            // presmerovani zpet na detail media nebo kamkoli je potreba
            header("Location: /WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=detail&id=" . urlencode($_POST['media_id']));
            exit;
        }
    }

    private function delete() {
        if (isset($_GET['comment_id']) && isset($_SESSION['user'])) {
            $comment_id = intval($_GET['comment_id']);
            $media_id = isset($_GET['media_id']) ? intval($_GET['media_id']) : 0;
            if ($comment_id > 0 && $media_id > 0) {
                $this->commentModel->delete($comment_id, $_SESSION['user']['id']);
                header("Location: /WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php?action=detail&id=" . urlencode($media_id));
                exit;
            } else {
                // neplatne id, bezpecne presmerovani
                header("Location: /WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php");
                exit;
            }
        } else {
            // chybi parametry nebo uzivatel neni prihlasen
            header("Location: /WA-2025-KV-semestral_project/my-rating/controllers/MediaController.php");
            exit;
        }
    }

}

// vytvoreni controlleru a zpracovani akce
$controller = new CommentController();
$controller->handleAction();