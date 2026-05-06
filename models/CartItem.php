<?php

//  models/CartItem.php

require_once BASE_PATH . '/models/Product.php';

class CartItem
{
    public function __construct(
        private Product $product,
        private int     $quantity
    ) {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Số lượng phải ≥ 1');
        }
    }

    //Tính thành tiền
    public function getSubtotal(): float
    {
        return $this->product->getPrice() * $this->quantity;
    }

    public function getFormattedSubtotal(): string
    {
        return number_format($this->getSubtotal(), 0, ',', '.') . 'đ';
    }

    //Cập nhật số lượng 
    public function setQuantity(int $qty): void
    {
        if ($qty < 1) throw new \InvalidArgumentException('Số lượng phải ≥ 1');
        if (!$this->product->isAvailable($qty)) {
            throw new \RuntimeException(
                "Chỉ còn {$this->product->getStock()} sản phẩm '{$this->product->getName()}' trong kho."
            );
        }
        $this->quantity = $qty;
    }

    public function increaseQuantity(int $by = 1): void
    {
        $this->setQuantity($this->quantity + $by);
    }

    //Getters
    public function getProduct(): Product { return $this->product; }
    public function getQuantity(): int    { return $this->quantity; }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product->getId(),
            'name'       => $this->product->getName(),
            'price'      => $this->product->getPrice(),
            'quantity'   => $this->quantity,
            'subtotal'   => $this->getSubtotal(),
            'image'      => $this->product->getImage(),
            'stock'      => $this->product->getStock(),
        ];
    }
}
