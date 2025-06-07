<?php 
// trida pro praci s medii
class Media {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // vytvori nove medium
    public function create($title, $description, $genre, $type, $year, $image_url, $banner_url, $user_id = null, $related = null, $author = null, $duration = null, $episode_count = null) {
        $sql = "INSERT INTO media (title, description, genre, type, year, image_url, banner_url, created_by, related, author, duration, episode_count)
                VALUES (:title, :description, :genre, :type, :year, :image_url, :banner_url, :created_by, :related, :author, :duration, :episode_count)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':genre' => $genre,
            ':type' => $type,
            ':year' => $year,
            ':image_url' => $image_url,
            ':banner_url' => $banner_url,
            ':created_by' => $user_id,
            ':related' => $related,
            ':author' => $author,
            ':duration' => $duration,
            ':episode_count' => $episode_count
        ]);
    }

    // vrati vsechna media
    public function getAll() {
        $sql = "SELECT * FROM media ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // vrati medium podle id
    public function getById($id) {
        $sql = "SELECT * FROM media WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // upravi medium
    public function update($id, $title, $description, $type, $year, $image_url, $banner_url) {
        $sql = "UPDATE media SET title = :title, description = :description, type = :type, year = :year, image_url = :image_url, banner_url = :banner_url WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':description' => $description,
            ':type' => $type,
            ':year' => $year,
            ':image_url' => $image_url,
            ':banner_url' => $banner_url
        ]);
    }

    // smaze medium
    public function delete($id) {
        $sql = "DELETE FROM media WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // vrati vsechna media uzivatele
    public function getByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM media WHERE created_by = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}