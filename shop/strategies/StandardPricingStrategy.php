<?php
// ============================================================
//  strategies/StandardPricingStrategy.php
//  Chiến lược giá tiêu chuẩn: VAT 10%, ship 35k (miễn nếu ≥ 500k)
// ============================================================

require_once BASE_PATH . '/interfaces/PricingStrategyInterface.php';

class StandardPricingStrategy implements PricingStrategyInterface
{
    private float $vatRate;
    private float $shippingFee;
    private float $freeShipThreshold;

    public function __construct(
        float $vatRate          = DEFAULT_VAT_RATE,
        float $shippingFee      = DEFAULT_SHIPPING_FEE,
        float $freeShipThreshold = FREE_SHIP_THRESHOLD
    ) {
        $this->vatRate           = $vatRate;
        $this->shippingFee       = $shippingFee;
        $this->freeShipThreshold = $freeShipThreshold;
    }

    public function calculateShipping(float $subtotal): float
    {
        return $subtotal >= $this->freeShipThreshold ? 0.0 : $this->shippingFee;
    }

    public function calculateVAT(float $subtotal): float
    {
        return round($subtotal * $this->vatRate, 2);
    }

    public function getName(): string
    {
        return 'standard';
    }

    public function getDescription(): string
    {
        $thresh = number_format($this->freeShipThreshold, 0, ',', '.');
        $ship   = number_format($this->shippingFee, 0, ',', '.');
        return "VAT " . ($this->vatRate * 100) . "% | Ship {$ship}đ (Miễn ship đơn ≥ {$thresh}đ)";
    }
}
