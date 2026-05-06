<?php
//  controllers/CartController.php


require_once BASE_PATH . '/controllers/BaseController.php';
require_once BASE_PATH . '/services/CartService.php';
require_once BASE_PATH . '/services/AuthService.php';
require_once BASE_PATH . '/repositories/CartRepository.php';
require_once BASE_PATH . '/repositories/ProductRepository.php';

class CartController extends BaseController
{
    private CartService $cartService;
    private int $userId;

    public function __construct()
    {
        AuthService::requireLogin();
        $this->userId = (int)AuthService::getCurrentUserId();
        $db = \Database::getInstance()->getConnection();
        $this->cartService = new CartService(
            new CartRepository($db),
            new ProductRepository($db),
            $this->userId
        );
    }

    //GET /cart 
    public function index(): void
    {
        $data = $this->cartService->getCartData();
        $this->view('cart/index', [
            'cartData'  => $data,
            'flash'     => $this->getFlash(),
            'pageTitle' => 'Giỏ hàng',
        ]);
    }

    //POST /cart/add  (AJAX + normal)
    public function add(): void
    {
        $productId = (int)$this->post('product_id', 0);
        $qty       = max(1, (int)$this->post('quantity', 1));

        $result = $this->cartService->addItem($productId, $qty);

        if ($this->isAjax()) {
            $this->json($result);
        }
        $this->setFlash($result['success'] ? 'success' : 'error', $result['message']);
        $this->redirect('/cart');
    }

    // POST /cart/update  (AJAX)
    public function update(): void
    {
        $productId = (int)$this->post('product_id', 0);
        $qty       = (int)$this->post('quantity', 0);

        $result = $this->cartService->updateQuantity($productId, $qty);

        if ($this->isAjax()) {
            // Trả về kèm subtotal mới
            $cartData = $this->cartService->getCartData();
            $result['cartData'] = $cartData;
            $this->json($result);
        }
        $this->setFlash($result['success'] ? 'success' : 'error', $result['message']);
        $this->redirect('/cart');
    }

    //POST /cart/remove  (AJAX + normal)
    public function remove(): void
    {
        $productId = (int)$this->post('product_id', 0);
        $result    = $this->cartService->removeItem($productId);

        if ($this->isAjax()) {
            $cartData = $this->cartService->getCartData();
            $result['cartData'] = $cartData;
            $this->json($result);
        }
        $this->setFlash('success', $result['message']);
        $this->redirect('/cart');
    }

    //GET /cart/count  (badge AJAX)
    public function count(): void
    {
        $data = $this->cartService->getCartData();
        $this->json(['count' => $data['total_quantity']]);
    }
}
