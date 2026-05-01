<?php
// ============================================================
//  repositories/UserRepository.php
// ============================================================

require_once BASE_PATH . '/models/User.php';

class UserRepository
{
    public function __construct(private PDO $db) {}

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :e LIMIT 1');
        $stmt->execute(['e' => $email]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :e');
        $stmt->execute(['e' => $email]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function create(string $name, string $email, string $hashedPassword): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password) VALUES (:n, :e, :p)'
        );
        $stmt->execute(['n' => $name, 'e' => $email, 'p' => $hashedPassword]);
        return (int)$this->db->lastInsertId();
    }
}
