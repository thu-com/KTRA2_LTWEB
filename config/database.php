<?php
// ============================================================
//  config/database.php  –  Kết nối CSDL (Singleton Pattern)
// ============================================================

require_once __DIR__ . '/config.php';

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    /** Constructor private – ngăn tạo instance trực tiếp */
    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die(json_encode([
                'error' => 'Không thể kết nối cơ sở dữ liệu: ' . $e->getMessage()
            ]));
        }
    }

    /** Lấy instance duy nhất (Singleton) */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /** Trả về đối tượng PDO */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    // Ngăn clone & unserialize
    private function __clone() {}
    public function __wakeup(): void
    {
        throw new \RuntimeException('Cannot unserialize singleton');
    }
}
