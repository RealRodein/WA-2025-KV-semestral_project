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
            } else {
                echo "Invalid action.";
            }
        }
    }

    private function CreateUser() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = trim(htmlspecialchars($_POST['username']));
            $email = trim(htmlspecialchars($_POST['email']));
            $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $role = 'user'; // Default role

            // Only pass fields that exist in your DB
            if ($this->UserModel->create($username, $email, $password_hash, $role)) {
                header("Location: ../views/auth/Login.php");
                exit();
            } else {
                echo "<script>alert('Username is already in use.');</script>";
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
}

// Instantiate the controller and handle the action
$controller = new UserController();
$controller->handleAction();