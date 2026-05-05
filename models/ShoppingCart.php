<?php
// ============================================================
//  models/ShoppingCart.php
// ============================================================

require_once BASE_PATH . '/models/CartItem.php';

class ShoppingCart
{
    /** @var CartItem[] key = product_id */
    private array $items = [];

    // ── Thêm sản phẩm ───────────────────────────────────────
    public function addItem(Product $product, int $qty = 1): void
    {
        if (!$product->isAvailable($qty)) {
            throw new \RuntimeException(
                "Sản phẩm '{$product->getName()}' không đủ số lượng trong kho."
            );
        }

        $pid = $product->getId();

        if (isset($this->items[$pid])) {
            // Đã có → tăng số lượng
            $newQty = $this->items[$pid]->getQuantity() + $qty;
            if (!$product->isAvailable($newQty)) {
                throw new \RuntimeException(
                    "Chỉ còn {$product->getStock()} sản phẩm '{$product->getName()}' trong kho."
                );
            }
            $this->items[$pid]->setQuantity($newQty);
        } else {
            $this->items[$pid] = new CartItem($product, $qty);
        }
    }

    // ── Xóa sản phẩm ────────────────────────────────────────
    public function removeItem(int $productId): void
    {
        unset($this->items[$productId]);
    }

    // ── Cập nhật số lượng ───────────────────────────────────
    public function updateQuantity(int $productId, int $qty): void
    {
        if (!isset($this->items[$productId])) {
            throw new \RuntimeException('Sản phẩm không có trong giỏ hàng.');
        }
        if ($qty <= 0) {
            $this->removeItem($productId);
            return;
        }
        $this->items[$productId]->setQuantity($qty);
    }

    // ── Tổng tiền hàng (chưa VAT, chưa ship) ───────────────
    public function getTotal(): float
    {
        return array_reduce(
            $this->items,
            fn(float $carry, CartItem $item) => $carry + $item->getSubtotal(),
            0.0
        );
    }

    // ── Số lượng mặt hàng trong giỏ ────────────────────────
    public function getItemCount(): int
    {
        return count($this->items);
    }

    // ── Tổng số sản phẩm (tính cả quantity) ────────────────
    public function getTotalQuantity(): int
    {
        return array_reduce(
            $this->items,
            fn(int $carry, CartItem $item) => $carry + $item->getQuantity(),
            0
        );
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function hasProduct(int $productId): bool
    {
        return isset($this->items[$productId]);
    }

    /** @return CartItem[] */
    public function getItems(): array
    {
        return $this->items;
    }

    // ── Tải items từ mảng dữ liệu DB ────────────────────────
    public function loadFromArray(array $rows, callable $productFetcher): void
    {
        foreach ($rows as $row) {
            $product = $productFetcher((int)$row['product_id']);
            if ($product) {
                $qty = (int)$row['quantity'];
                $pid = $product->getId();
                $this->items[$pid] = new CartItem($product, $qty);
            }
        }
    }

    public function toArray(): array
    {
        return array_map(fn(CartItem $item) => $item->toArray(), $this->items);
    }
}
