<?php
// trida pro praci s komentari
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

    // vrati vsechny komentare uzivatele
    public function getByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT c.*, m.title as media_title, m.id as media_id FROM comments c JOIN media m ON c.media_id = m.id WHERE c.user_id = ? ORDER BY c.created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($comment_id, $content, $rating, $user_id) {
        $stmt = $this->db->prepare("UPDATE comments SET content = ?, rating = ? WHERE id = ? AND user_id = ?");
        return $stmt->execute([$content, $rating, $comment_id, $user_id]);
    }

    public function delete($comment_id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        return $stmt->execute([$comment_id, $user_id]);
    }
}