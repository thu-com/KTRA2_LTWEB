<?php
// ============================================================
//  models/Order.php
// ============================================================

class Order
{
    private int    $id        = 0;
    private int    $userId;
    private float  $subtotal;
    private float  $vatAmount;
    private float  $shippingFee;
    private float  $total;
    private string $pricingStrategy;
    private string $status;
    private string $shippingAddress;
    private string $note;
    private string $createdAt;

    /** @var array  Danh sách sản phẩm trong đơn */
    private array  $items = [];

    public function __construct(array $data)
    {
        $this->id              = (int)   ($data['id']               ?? 0);
        $this->userId          = (int)    $data['user_id'];
        $this->subtotal        = (float) ($data['subtotal']         ?? 0);
        $this->vatAmount       = (float) ($data['vat_amount']       ?? 0);
        $this->shippingFee     = (float) ($data['shipping_fee']     ?? 0);
        $this->total           = (float) ($data['total']            ?? 0);
        $this->pricingStrategy = (string)($data['pricing_strategy'] ?? 'standard');
        $this->status          = (string)($data['status']           ?? 'pending');
        $this->shippingAddress = (string)($data['shipping_address'] ?? '');
        $this->note            = (string)($data['note']             ?? '');
        $this->createdAt       = (string)($data['created_at']       ?? date('Y-m-d H:i:s'));
        $this->items           =          $data['items']            ?? [];
    }

    // ── Getters ─────────────────────────────────────────────
    public function getId(): int              { return $this->id; }
    public function getUserId(): int          { return $this->userId; }
    public function getSubtotal(): float      { return $this->subtotal; }
    public function getVatAmount(): float     { return $this->vatAmount; }
    public function getShippingFee(): float   { return $this->shippingFee; }
    public function getTotal(): float         { return $this->total; }
    public function getStatus(): string       { return $this->status; }
    public function getPricingStrategy(): string { return $this->pricingStrategy; }
    public function getShippingAddress(): string { return $this->shippingAddress; }
    public function getNote(): string         { return $this->note; }
    public function getCreatedAt(): string    { return $this->createdAt; }
    public function getItems(): array         { return $this->items; }

    public function setId(int $id): void      { $this->id = $id; }

    // ── Helpers hiển thị ───────────────────────────────────
    public function getFormattedSubtotal(): string  { return number_format($this->subtotal,    0, ',', '.') . 'đ'; }
    public function getFormattedVAT(): string       { return number_format($this->vatAmount,   0, ',', '.') . 'đ'; }
    public function getFormattedShipping(): string  { return number_format($this->shippingFee, 0, ',', '.') . 'đ'; }
    public function getFormattedTotal(): string     { return number_format($this->total,       0, ',', '.') . 'đ'; }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending'   => '⏳ Chờ xác nhận',
            'confirmed' => '✅ Đã xác nhận',
            'shipped'   => '🚚 Đang giao',
            'delivered' => '🎉 Đã giao',
            'cancelled' => '❌ Đã huỷ',
            default     => $this->status,
        };
    }

    public function toArray(): array
    {
        return [
            'id'               => $this->id,
            'user_id'          => $this->userId,
            'subtotal'         => $this->subtotal,
            'vat_amount'       => $this->vatAmount,
            'shipping_fee'     => $this->shippingFee,
            'total'            => $this->total,
            'pricing_strategy' => $this->pricingStrategy,
            'status'           => $this->status,
            'shipping_address' => $this->shippingAddress,
            'note'             => $this->note,
            'created_at'       => $this->createdAt,
            'items'            => $this->items,
        ];
    }
}
