<?php
// trida pro praci s databazi
class Database {
    private $host = "localhost";
    private $db_name = "wa_vojtech_kupec";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        // vytvori nove pripojeni k databazi
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Chyba pripojeni: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Pro otestovani pripojeni staci tento soubor spustit
// Muzete tento kod zakomentovat po overeni
//$database = new Database();
//$database->getConnection();