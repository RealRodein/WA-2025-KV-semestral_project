<?php
session_start();
session_regenerate_id(true);

require_once '../models/Database.php';
require_once '../models/User.php';

class UserController {
    private $db;
    private $UserModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->UserModel = new User($this->db);
    }

    public function handleAction() {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            // hlavni routovani akci
            if ($action === 'register') {
                $this->CreateUser();
            } elseif ($action === 'login') {
                $this->LoginUser();
            } elseif ($action === 'logout') {
                $this->LogoutUser();
            } elseif ($action === 'media') {
                $this->listUserMedia();
            } elseif ($action === 'comments') {
                $this->listUserComments();
            } elseif ($action === 'admin_update') {
                $this->adminUpdateUser();
            } elseif ($action === 'admin_delete') {
                $this->adminDeleteUser();
            } else {
                echo "Invalid action.";
            }
        }
    }

    private function CreateUser() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = trim(htmlspecialchars($_POST['username']));
            $email = trim(htmlspecialchars($_POST['email']));
            if ($email === '') {
                $email = null;
            }
            $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $role = 'user'; // vychozi role

            if ($this->UserModel->create($username, $email, $password_hash, $role)) {
                header("Location: ../views/auth/Login.php");
                exit();
            } else {
                echo "<script>alert('Username or e-mail is already in use.'); window.location.href='../views/auth/Register.php';</script>";
            }
        } else {
            echo "Invalid request.";
        }
    }

    private function LoginUser() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = trim(htmlspecialchars($_POST['username']));
            $password = $_POST['password'];

            $user = $this->UserModel->getByUsername($username);

            if ($user && password_verify($password, $user['password_hash'])) {
                session_start();
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                ];
                header("Location: ../controllers/MediaController.php");
                exit();
            } else {
                $this->UserModel->alert('Invalid username or password.', '../views/auth/Login.php');
            }
        } else {
            $this->UserModel->alert('Invalid request.', '../views/auth/Login.php');
        }
    }

    private function LogoutUser() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../controllers/MediaController.php");
        exit();
    }

    private function listUserMedia() {
        if (!isset($_GET['user_id'])) {
            echo "User ID not specified.";
            return;
        }
        $user_id = intval($_GET['user_id']);
        require_once '../models/Media.php';
        $mediaModel = new Media($this->db);
        $mediaList = $mediaModel->getByUserId($user_id);

        include '../views/user/UserMedia.php';
    }

    private function listUserComments() {
        if (!isset($_GET['user_id'])) {
            echo "User ID not specified.";
            return;
        }
        $user_id = intval($_GET['user_id']);
        require_once '../models/Comment.php';
        $commentModel = new Comment($this->db);
        $commentList = $commentModel->getByUserId($user_id);

        include '../views/user/UserComments.php';
    }

    // admin: uprava emailu a role
    public function adminUpdateUser() {
        $id = $_POST['id'] ?? null;
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? '';
        if (!$id || !$email || !$role) {
            header('Location: /WA-2025-KV-semestral_project/my-rating/views/user/Users.php?error=Email+a+role+jsou+povinne.&id=' . urlencode($id) . '&email=' . urlencode($email) . '&role=' . urlencode($role));
            exit();
        }
        $db = $this->db;
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
        $stmt->execute([$email, $id]);
        if ($stmt->fetch()) {
            header('Location: /WA-2025-KV-semestral_project/my-rating/views/user/Users.php?error=Email+je+jiz+pouzity+jinde.&id=' . urlencode($id) . '&email=' . urlencode($email) . '&role=' . urlencode($role));
            exit();
        }
        try {
            $stmt = $db->prepare('UPDATE users SET email = ?, role = ? WHERE id = ?');
            $stmt->execute([$email, $role, $id]);
            header('Location: /WA-2025-KV-semestral_project/my-rating/views/user/Users.php?message=uzivatel+upraven.');
            exit();
        } catch (PDOException $e) {
            header('Location: /WA-2025-KV-semestral_project/my-rating/views/user/Users.php?error=Chyba+pri+ukladani:+'.urlencode($e->getMessage()).'&id=' . urlencode($id) . '&email=' . urlencode($email) . '&role=' . urlencode($role));
            exit();
        }
    }

    // admin: smazani uzivatele, presun media, mazani komentaru
    private function adminDeleteUser() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: ../views/media/List.php');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            // mazani komentaru uzivatele
            $stmt = $this->db->prepare('DELETE FROM comments WHERE user_id = :id');
            $stmt->execute([':id' => $id]);

            // najdi noveho vlastnika (trusted/admin)
            $stmt = $this->db->prepare('SELECT id FROM users WHERE (role = "admin" OR role = "trusted") AND id != :id ORDER BY id ASC LIMIT 1');
            $stmt->execute([':id' => $id]);
            $newOwner = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($newOwner) {
                $newOwnerId = $newOwner['id'];
                // presun media na noveho vlastnika
                $stmt = $this->db->prepare('UPDATE media SET created_by = :newOwnerId WHERE created_by = :id');
                $stmt->execute([':newOwnerId' => $newOwnerId, ':id' => $id]);
            }
            // smazani uzivatele
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute([':id' => $id]);
            header('Location: ../views/user/Users.php?message=uzivatel+smazan');
            exit();
        }
        header('Location: ../views/user/Users.php?error=neplatny+pozadavek');
        exit();
    }
}

// spusteni controlleru
$controller = new UserController();
$controller->handleAction();