<?php 
class User { 
    private $db;
    public string $username;
    public string $email;
    public string $password_hash;	
    public string $role;
    public string $name;
    public string $surname;

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new user
    public function create($username, $email, $password_hash, $role = 'user', $profile_picture = null) {
        if ($this->checkUniqueUsername($username)) {
            $sql = "INSERT INTO users (username, email, password_hash, role, created_at, profile_picture)
                VALUES (:username, :email, :password_hash, :role, NOW(), :profile_picture)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $password_hash,
                ':role' => $role,
                ':profile_picture' => $profile_picture,
            ]);
        } else {
            session_start();
            $_SESSION['form_data'] = [
                'username' => $username,
                'email' => $email,
            ];
            $this->alert('Username is already in use.', '../views/UserRegister.php');
        }
    }

    public function checkUniqueUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) === false; // true if unique, false if exists
    }

    public function getByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return the user data or false if not found
    }

    // Get all users
    public function getAll() {
        $sql = "SELECT * FROM users ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function alert($message, $redirectUrl) {
        echo "<script>
            alert('$message');
            window.location.href = '$redirectUrl';
        </script>";
        exit(); // Ensure no further code is executed
    }
}