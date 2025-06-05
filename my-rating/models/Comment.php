<?php
class Comment {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function create($media_id, $user_id, $content, $rating = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO comments (media_id, user_id, content, rating) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$media_id, $user_id, $content, $rating]);
    }

    public function getByMedia($media_id) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.username FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.media_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$media_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}