<?php
// ============================================================
//  repositories/CartRepository.php
//  Lưu giỏ hàng persistent vào database
// ============================================================

class CartRepository
{
    public function __construct(private PDO $db) {}

    /** Lấy tất cả items của user */
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT ci.*, p.name, p.price, p.stock, p.image, p.description
             FROM cart_items ci
             JOIN products p ON p.id = ci.product_id
             WHERE ci.user_id = :uid
             ORDER BY ci.added_at DESC'
        );
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    /** Thêm hoặc cập nhật item (UPSERT) */
    public function upsert(int $userId, int $productId, int $quantity): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO cart_items (user_id, product_id, quantity)
             VALUES (:uid, :pid, :qty)
             ON DUPLICATE KEY UPDATE quantity = :qty2, added_at = CURRENT_TIMESTAMP'
        );
        return $stmt->execute([
            'uid'  => $userId,
            'pid'  => $productId,
            'qty'  => $quantity,
            'qty2' => $quantity,
        ]);
    }

    /** Tăng số lượng thêm delta (nếu đã có item) */
    public function increaseQuantity(int $userId, int $productId, int $delta): bool
    {
        $row = $this->findItem($userId, $productId);
        if ($row) {
            return $this->upsert($userId, $productId, $row['quantity'] + $delta);
        }
        return $this->upsert($userId, $productId, $delta);
    }

    /** Tìm một item cụ thể */
    public function findItem(int $userId, int $productId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM cart_items WHERE user_id = :uid AND product_id = :pid LIMIT 1'
        );
        $stmt->execute(['uid' => $userId, 'pid' => $productId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Xóa một item */
    public function removeItem(int $userId, int $productId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM cart_items WHERE user_id = :uid AND product_id = :pid'
        );
        return $stmt->execute(['uid' => $userId, 'pid' => $productId]);
    }

    /** Xóa toàn bộ giỏ hàng của user (sau khi đặt hàng thành công) */
    public function clearCart(int $userId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM cart_items WHERE user_id = :uid');
        return $stmt->execute(['uid' => $userId]);
    }

    /** Đếm số items */
    public function countItems(int $userId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COALESCE(SUM(quantity), 0) FROM cart_items WHERE user_id = :uid'
        );
        $stmt->execute(['uid' => $userId]);
        return (int)$stmt->fetchColumn();
    }
}
