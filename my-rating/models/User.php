<?php 
// trida pro praci s uzivateli
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

    // vytvori noveho uzivatele
    public function create($username, $email, $password_hash, $role = 'user') {
        if ($this->checkUniqueUsername($username)) {
            $sql = "INSERT INTO users (username, email, password_hash, role, created_at)
                VALUES (:username, :email, :password_hash, :role, NOW())";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $password_hash,
                ':role' => $role,
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

    // true pokud je username unikatni, false pokud existuje
    public function checkUniqueUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) === false; // true pokud je unikatni, false pokud existuje
    }

    // vrati uzivatele podle username nebo false pokud nenalezen
    public function getByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // vrati data uzivatele nebo false pokud nenalezen
    }

    // vrati vsechny uzivatele
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