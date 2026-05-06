<?php
//  models/Product.php


class Product
{
    private int    $id;
    private string $name;
    private string $description;
    private float  $price;
    private int    $stock;
    private string $image;
    private ?int   $categoryId;
    private string $createdAt;

    public function __construct(array $data)
    {
        $this->id          = (int)   $data['id'];
        $this->name        = (string)$data['name'];
        $this->description = (string)($data['description'] ?? '');
        $this->price       = (float) $data['price'];
        $this->stock       = (int)   $data['stock'];
        $this->image       = (string)($data['image'] ?? 'default.jpg');
        $this->categoryId  = isset($data['category_id']) ? (int)$data['category_id'] : null;
        $this->createdAt   = (string)($data['created_at'] ?? '');
    }

    // Kiểm tra tồn kho
    public function isAvailable(int $qty = 1): bool
    {
        return $this->stock >= $qty;
    }

    //Getters
    public function getId(): int         { return $this->id; }
    public function getName(): string    { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getPrice(): float    { return $this->price; }
    public function getStock(): int      { return $this->stock; }
    public function getImage(): string   { return $this->image; }
    public function getCategoryId(): ?int { return $this->categoryId; }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 0, ',', '.') . 'đ';
    }

    // Setters
    public function setStock(int $stock): void
    {
        if ($stock < 0) throw new \InvalidArgumentException('Tồn kho không thể âm');
        $this->stock = $stock;
    }

    public function decreaseStock(int $qty): void
    {
        if (!$this->isAvailable($qty)) {
            throw new \RuntimeException("Sản phẩm '{$this->name}' không đủ số lượng trong kho.");
        }
        $this->stock -= $qty;
    }

    /** Chuyển thành mảng (để lưu DB, truyền view…) */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,
            'image'       => $this->image,
            'category_id' => $this->categoryId,
        ];
    }
}
