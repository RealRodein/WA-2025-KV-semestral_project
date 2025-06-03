<?php 
class Media {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new media entry
    public function create($title, $description, $genre, $type, $year, $image_url, $banner_url, $user_id = null) {
        $sql = "INSERT INTO media (title, description, genre, type, year, image_url, banner_url, created_by)
                VALUES (:title, :description, :genre, :type, :year, :image_url, :banner_url, :created_by)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':genre' => $genre,
            ':type' => $type,
            ':year' => $year,
            ':image_url' => $image_url,
            ':banner_url' => $banner_url,
            ':created_by' => null // always NULL for now
        ]);
    }

    // Get all media
    public function getAll() {
        $sql = "SELECT * FROM media ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get media by ID
    public function getById($id) {
        $sql = "SELECT * FROM media WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update media
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

    // Delete media
    public function delete($id) {
        $sql = "DELETE FROM media WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}