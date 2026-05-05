<?php
// ============================================================
//  controllers/OrderController.php
// ============================================================

require_once BASE_PATH . '/controllers/BaseController.php';
require_once BASE_PATH . '/services/OrderService.php';
require_once BASE_PATH . '/services/CartService.php';
require_once BASE_PATH . '/services/AuthService.php';
require_once BASE_PATH . '/services/EmailService.php';
require_once BASE_PATH . '/repositories/OrderRepository.php';
require_once BASE_PATH . '/repositories/CartRepository.php';
require_once BASE_PATH . '/repositories/ProductRepository.php';
require_once BASE_PATH . '/repositories/UserRepository.php';

class OrderController extends BaseController
{
    private OrderService $orderService;
    private CartService  $cartService;
    private int $userId;

    public function __construct()
    {
        AuthService::requireLogin();
        $this->userId = (int)AuthService::getCurrentUserId();
        $db = \Database::getInstance()->getConnection();

        $this->orderService = new OrderService(
            new OrderRepository($db),
            new ProductRepository($db),
            new EmailService()
        );
        $this->cartService = new CartService(
            new CartRepository($db),
            new ProductRepository($db),
            $this->userId
        );
    }

    // ── GET /checkout ────────────────────────────────────────
    public function checkout(): void
    {
        $cartData = $this->cartService->getCartData();
        if ($cartData['is_empty']) {
            $this->setFlash('error', 'Giỏ hàng trống, không thể thanh toán.');
            $this->redirect('/cart');
        }

        // Preview 3 chiến lược giá
        $strategies = [];
        foreach (['standard', 'premium', 'flash_sale'] as $s) {
            $strategies[$s] = $this->orderService->previewPricing(
                $cartData['total'], $s
            );
        }

        $this->view('orders/checkout', [
            'cartData'   => $cartData,
            'strategies' => $strategies,
            'flash'      => $this->getFlash(),
            'pageTitle'  => 'Thanh toán',
        ]);
    }

    // ── POST /checkout ───────────────────────────────────────
    public function placeOrder(): void
    {
        $address  = $this->sanitize($this->post('shipping_address', ''));
        $strategy = $this->sanitize($this->post('pricing_strategy', 'standard'));
        $note     = $this->sanitize($this->post('note', ''));

        $cartData  = $this->cartService->getCartData();
        $cartItems = $cartData['items'];

        $result = $this->orderService->placeOrder(
            $this->userId, $cartItems, $address, $strategy, $note
        );

        if ($result['success']) {
            // Xóa giỏ hàng sau khi đặt thành công
            $this->cartService->clearCart();
            $this->setFlash('success', $result['message']);
            $this->redirect('/orders/' . $result['order_id']);
        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('/checkout');
        }
    }

    // ── GET /orders ──────────────────────────────────────────
    public function myOrders(): void
    {
        $orders = $this->orderService->getUserOrders($this->userId);
        $this->view('orders/list', [
            'orders'    => $orders,
            'flash'     => $this->getFlash(),
            'pageTitle' => 'Đơn hàng của tôi',
        ]);
    }

    // ── GET /orders/{id} ─────────────────────────────────────
    public function detail(int $orderId): void
    {
        $order = $this->orderService->getOrder($orderId);
        if (!$order || (int)$order['user_id'] !== $this->userId) {
            $this->setFlash('error', 'Không tìm thấy đơn hàng.');
            $this->redirect('/orders');
        }
        $this->view('orders/detail', [
            'order'     => $order,
            'flash'     => $this->getFlash(),
            'pageTitle' => 'Đơn hàng #' . $orderId,
        ]);
    }

    // ── GET /admin/orders (admin only) ───────────────────────
    public function adminOrders(): void
    {
        $user = AuthService::getCurrentUser();
        if ($user['role'] !== 'admin') {
            $this->redirect('/');
        }
        $orders = $this->orderService->getAllOrders();
        $this->view('orders/admin', [
            'orders'    => $orders,
            'flash'     => $this->getFlash(),
            'pageTitle' => 'Quản lý đơn hàng',
        ]);
    }

    // ── POST /admin/orders/status (admin only, AJAX) ─────────
    public function updateStatus(): void
    {
        $user = AuthService::getCurrentUser();
        if ($user['role'] !== 'admin') {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $db      = \Database::getInstance()->getConnection();
        $repo    = new OrderRepository($db);
        $orderId = (int)$this->post('order_id', 0);
        $status  = $this->sanitize($this->post('status', ''));

        $ok = $repo->updateStatus($orderId, $status);
        $this->json(['success' => $ok, 'message' => $ok ? 'Đã cập nhật trạng thái.' : 'Lỗi cập nhật.']);
    }
}
