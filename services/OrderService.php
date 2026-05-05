<?php
// ============================================================
//  services/OrderService.php  –  Business logic đặt hàng
// ============================================================

require_once BASE_PATH . '/models/Order.php';
require_once BASE_PATH . '/repositories/OrderRepository.php';
require_once BASE_PATH . '/repositories/ProductRepository.php';
require_once BASE_PATH . '/services/EmailService.php';
require_once BASE_PATH . '/interfaces/PricingStrategyInterface.php';
require_once BASE_PATH . '/strategies/StandardPricingStrategy.php';
require_once BASE_PATH . '/strategies/PremiumPricingStrategy.php';
require_once BASE_PATH . '/strategies/FlashSalePricingStrategy.php';

class OrderService
{
    public function __construct(
        private OrderRepository   $orderRepo,
        private ProductRepository $productRepo,
        private EmailService      $emailService
    ) {}

    // ── Tạo chiến lược giá theo tên ─────────────────────────
    public static function createStrategy(string $name): PricingStrategyInterface
    {
        return match ($name) {
            'premium'    => new PremiumPricingStrategy(),
            'flash_sale' => new FlashSalePricingStrategy(),
            default      => new StandardPricingStrategy(),
        };
    }

    // ── Đặt hàng ────────────────────────────────────────────
    public function placeOrder(
        int    $userId,
        array  $cartItems,        // từ ShoppingCart::toArray()
        string $shippingAddress,
        string $strategyName  = 'standard',
        string $note          = ''
    ): array {
        if (empty($cartItems)) {
            return ['success' => false, 'message' => 'Giỏ hàng trống.'];
        }
        if (empty(trim($shippingAddress))) {
            return ['success' => false, 'message' => 'Vui lòng nhập địa chỉ giao hàng.'];
        }

        // ── Tính tiền ─────────────────────────────────────
        $strategy = self::createStrategy($strategyName);
        $subtotal = array_sum(array_column($cartItems, 'subtotal'));
        $vat      = $strategy->calculateVAT($subtotal);
        $shipping = $strategy->calculateShipping($subtotal);
        $total    = $subtotal + $vat + $shipping;

        // ── Xây dựng Order object ─────────────────────────
        $order = new Order([
            'user_id'          => $userId,
            'subtotal'         => $subtotal,
            'vat_amount'       => $vat,
            'shipping_fee'     => $shipping,
            'total'            => $total,
            'pricing_strategy' => $strategy->getName(),
            'status'           => 'pending',
            'shipping_address' => $shippingAddress,
            'note'             => $note,
            'items'            => $cartItems,
        ]);

        try {
            // ── Lưu DB (transaction bên trong repo) ──────
            $orderId = $this->orderRepo->createOrder($order, $cartItems);
            $order->setId($orderId);

            // ── Cập nhật tồn kho ─────────────────────────
            foreach ($cartItems as $item) {
                $ok = $this->productRepo->decreaseStock(
                    (int)$item['product_id'],
                    (int)$item['quantity']
                );
                if (!$ok) {
                    // rollback nếu không đủ hàng
                    $this->orderRepo->updateStatus($orderId, 'cancelled');
                    return [
                        'success' => false,
                        'message' => "Sản phẩm '{$item['name']}' không đủ tồn kho khi đặt hàng."
                    ];
                }
            }

            // ── Gửi email xác nhận ────────────────────────
            $userRepo = new \UserRepository(
                \Database::getInstance()->getConnection()
            );
            $user = $userRepo->findById($userId);
            if ($user) {
                $orderArr = $order->toArray();
                $this->emailService->sendOrderConfirmation(
                    $user->toArray(),
                    $orderArr
                );
            }

            return [
                'success'  => true,
                'message'  => 'Đặt hàng thành công! Email xác nhận đã được gửi.',
                'order_id' => $orderId,
                'order'    => $order->toArray(),
            ];

        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    // ── Lấy chi tiết đơn hàng ───────────────────────────────
    public function getOrder(int $orderId): ?array
    {
        return $this->orderRepo->findById($orderId);
    }

    // ── Đơn hàng của user ───────────────────────────────────
    public function getUserOrders(int $userId): array
    {
        return $this->orderRepo->findByUser($userId);
    }

    // ── Tất cả đơn (admin) ──────────────────────────────────
    public function getAllOrders(int $limit = 50, int $offset = 0): array
    {
        return $this->orderRepo->findAll($limit, $offset);
    }

    // ── Preview tính tiền (không lưu DB) ────────────────────
    public function previewPricing(float $subtotal, string $strategyName): array
    {
        $strategy = self::createStrategy($strategyName);
        $vat      = $strategy->calculateVAT($subtotal);
        $shipping = $strategy->calculateShipping($subtotal);
        return [
            'subtotal'     => $subtotal,
            'vat'          => $vat,
            'shipping'     => $shipping,
            'total'        => $subtotal + $vat + $shipping,
            'strategy'     => $strategy->getName(),
            'description'  => $strategy->getDescription(),
        ];
    }
}
