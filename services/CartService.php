<?php

//  services/CartService.php  –  Business logic cho giỏ hàng
//  Kết hợp ShoppingCart (domain) + CartRepository (persistence)

require_once BASE_PATH . '/models/ShoppingCart.php';
require_once BASE_PATH . '/repositories/CartRepository.php';
require_once BASE_PATH . '/repositories/ProductRepository.php';

class CartService
{
    private ShoppingCart $cart;

    public function __construct(
        private CartRepository    $cartRepo,
        private ProductRepository $productRepo,
        private int               $userId
    ) {
        $this->cart = new ShoppingCart();
        $this->loadFromDatabase();
    }

    //Tải giỏ hàng từ DB vào domain object 
    private function loadFromDatabase(): void
    {
        $rows = $this->cartRepo->getByUser($this->userId);
        foreach ($rows as $row) {
            $product = $this->productRepo->findProductById((int)$row['product_id']);
            if ($product) {
                try {
                    $this->cart->addItem($product, (int)$row['quantity']);
                } catch (\RuntimeException) {
                    // Tồn kho thay đổi, bỏ qua item này
                }
            }
        }
    }

    //Thêm sản phẩm 
    public function addItem(int $productId, int $qty = 1): array
    {
        $product = $this->productRepo->findProductById($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'Sản phẩm không tồn tại.'];
        }

        // Kiểm tra tổng qty (trong giỏ + thêm mới)
        $currentQty = 0;
        if ($this->cart->hasProduct($productId)) {
            $currentQty = $this->cart->getItems()[$productId]->getQuantity();
        }
        $newQty = $currentQty + $qty;

        if (!$product->isAvailable($newQty)) {
            return [
                'success' => false,
                'message' => "Chỉ còn {$product->getStock()} sản phẩm trong kho."
            ];
        }

        try {
            $this->cart->addItem($product, $qty);
            $this->cartRepo->upsert($this->userId, $productId, $newQty);
            return [
                'success'    => true,
                'message'    => "Đã thêm '{$product->getName()}' vào giỏ hàng.",
                'cartCount'  => $this->cart->getTotalQuantity(),
            ];
        } catch (\RuntimeException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    //Xóa sản phẩm 
    public function removeItem(int $productId): array
    {
        $this->cart->removeItem($productId);
        $this->cartRepo->removeItem($this->userId, $productId);
        return ['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.'];
    }

    //Cập nhật số lượng 
    public function updateQuantity(int $productId, int $qty): array
    {
        if ($qty <= 0) {
            return $this->removeItem($productId);
        }

        $product = $this->productRepo->findProductById($productId);
        if (!$product || !$product->isAvailable($qty)) {
            return [
                'success' => false,
                'message' => 'Số lượng không hợp lệ hoặc không đủ hàng.'
            ];
        }

        try {
            $this->cart->updateQuantity($productId, $qty);
            $this->cartRepo->upsert($this->userId, $productId, $qty);
            return ['success' => true, 'message' => 'Đã cập nhật số lượng.'];
        } catch (\RuntimeException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Lấy thông tin giỏ hàng
    public function getCartData(): array
    {
        return [
            'items'          => $this->cart->toArray(),
            'total'          => $this->cart->getTotal(),
            'total_formatted'=> number_format($this->cart->getTotal(), 0, ',', '.') . 'đ',
            'item_count'     => $this->cart->getItemCount(),
            'total_quantity' => $this->cart->getTotalQuantity(),
            'is_empty'       => $this->cart->isEmpty(),
        ];
    }

    public function getCart(): ShoppingCart
    {
        return $this->cart;
    }

    //Xóa toàn bộ giỏ hàng 
    public function clearCart(): void
    {
        $this->cart->clear();
        $this->cartRepo->clearCart($this->userId);
    }
}
