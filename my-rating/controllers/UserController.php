<?php
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
            $role = 'user'; // Default role

            // Only pass fields that exist in your DB
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

            // Fetch user from the database using the username
            $user = $this->UserModel->getByUsername($username);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Start session and store user info
                session_start();
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                ];
                header("Location: ../controllers/MediaController.php"); // Redirect to dashboard or home page
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
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session
        header("Location: ../controllers/MediaController.php"); // Redirect to login page
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

        // You can include a view here to display the media
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

        // You can include a view here to display the comments
        include '../views/user/UserComments.php';
    }

    // Admin: update user email and role
    public function adminUpdateUser() {
        $id = $_POST['id'] ?? null;
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? '';
        if (!$id || !$email || !$role) {
            header('Location: /WA-2025-KV-semestral_project/my-rating/views/user/Users.php?error=Email+a+role+jsou+povinné.&id=' . urlencode($id) . '&email=' . urlencode($email) . '&role=' . urlencode($role));
            exit();
        }
        $db = $this->db;
        // Check for duplicate email (other than this user)
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
        $stmt->execute([$email, $id]);
        if ($stmt->fetch()) {
            header('Location: /WA-2025-KV-semestral_project/my-rating/views/user/Users.php?error=Email+je+již+použit+jinde.&id=' . urlencode($id) . '&email=' . urlencode($email) . '&role=' . urlencode($role));
            exit();
        }
        try {
            $stmt = $db->prepare('UPDATE users SET email = ?, role = ? WHERE id = ?');
            $stmt->execute([$email, $role, $id]);
            header('Location: /WA-2025-KV-semestral_project/my-rating/views/user/Users.php?message=Uživatel+upraven.');
            exit();
        } catch (PDOException $e) {
            header('Location: /WA-2025-KV-semestral_project/my-rating/views/user/Users.php?error=Chyba+při+ukládání:+'.urlencode($e->getMessage()).'&id=' . urlencode($id) . '&email=' . urlencode($email) . '&role=' . urlencode($role));
            exit();
        }
    }

    // Admin: delete user
    private function adminDeleteUser() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: ../views/media/List.php');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute([':id' => $id]);
            header('Location: ../views/user/Users.php?message=Uživatel smazán');
            exit();
        }
        header('Location: ../views/user/Users.php?error=Neplatný požadavek');
        exit();
    }
}

// Instantiate the controller and handle the action
$controller = new UserController();
$controller->handleAction();