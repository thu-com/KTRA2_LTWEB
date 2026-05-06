<?php

//  repositories/OrderRepository.php

require_once BASE_PATH . '/models/Order.php';

class OrderRepository
{
    public function __construct(private PDO $db) {}

    /** Tạo đơn hàng + items trong một transaction */
    public function createOrder(Order $order, array $items): int
    {
        $this->db->beginTransaction();
        try {
            // 1) Insert vào orders
            $stmt = $this->db->prepare(
                'INSERT INTO orders
                    (user_id, subtotal, vat_amount, shipping_fee, total,
                     pricing_strategy, status, shipping_address, note)
                 VALUES
                    (:uid, :sub, :vat, :ship, :total,
                     :strategy, :status, :addr, :note)'
            );
            $stmt->execute([
                'uid'      => $order->getUserId(),
                'sub'      => $order->getSubtotal(),
                'vat'      => $order->getVatAmount(),
                'ship'     => $order->getShippingFee(),
                'total'    => $order->getTotal(),
                'strategy' => $order->getPricingStrategy(),
                'status'   => $order->getStatus(),
                'addr'     => $order->getShippingAddress(),
                'note'     => $order->getNote(),
            ]);
            $orderId = (int)$this->db->lastInsertId();

            // 2) Insert order_items
            $itemStmt = $this->db->prepare(
                'INSERT INTO order_items (order_id, product_id, name, price, quantity, subtotal)
                 VALUES (:oid, :pid, :name, :price, :qty, :sub)'
            );
            foreach ($items as $item) {
                $itemStmt->execute([
                    'oid'   => $orderId,
                    'pid'   => $item['product_id'],
                    'name'  => $item['name'],
                    'price' => $item['price'],
                    'qty'   => $item['quantity'],
                    'sub'   => $item['subtotal'],
                ]);
            }

            $this->db->commit();
            return $orderId;

        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** Lấy đơn hàng theo ID kèm items */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT o.*, u.name AS user_name, u.email AS user_email
             FROM orders o
             JOIN users u ON u.id = o.user_id
             WHERE o.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();
        if (!$order) return null;

        $order['items'] = $this->getOrderItems($id);
        return $order;
    }

    /** Lấy danh sách đơn hàng của user */
    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC'
        );
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    /** Lấy tất cả đơn (admin) */
    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            'SELECT o.*, u.name AS user_name
             FROM orders o JOIN users u ON u.id = o.user_id
             ORDER BY o.created_at DESC
             LIMIT :lim OFFSET :off'
        );
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Cập nhật trạng thái đơn */
    public function updateStatus(int $orderId, string $status): bool
    {
        return $this->db->prepare(
            'UPDATE orders SET status = :s WHERE id = :id'
        )->execute(['s' => $status, 'id' => $orderId]);
    }

    private function getOrderItems(int $orderId): array
    {
        $stmt = $this->db->prepare(
            'SELECT oi.*, p.image
             FROM order_items oi
             LEFT JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = :oid'
        );
        $stmt->execute(['oid' => $orderId]);
        return $stmt->fetchAll();
    }
}
