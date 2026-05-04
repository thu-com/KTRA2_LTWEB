<?php
// ============================================================
//  strategies/PremiumPricingStrategy.php
//  Thành viên VIP: VAT 8%, miễn phí vận chuyển hoàn toàn
// ============================================================

require_once BASE_PATH . '/interfaces/PricingStrategyInterface.php';

class PremiumPricingStrategy implements PricingStrategyInterface
{
    private float $vatRate = 0.08;  // Ưu đãi VAT 8%

    public function calculateShipping(float $subtotal): float
    {
        return 0.0;  // Miễn phí vận chuyển hoàn toàn
    }

    public function calculateVAT(float $subtotal): float
    {
        return round($subtotal * $this->vatRate, 2);
    }

    public function getName(): string
    {
        return 'premium';
    }

    public function getDescription(): string
    {
        return 'VIP Member: VAT 8% | Miễn phí vận chuyển mọi đơn hàng';
    }
}
