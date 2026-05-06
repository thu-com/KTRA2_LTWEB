<?php
//  strategies/FlashSalePricingStrategy.php
//  Flash Sale: VAT 10%, phí ship cố định 15k

require_once BASE_PATH . '/interfaces/PricingStrategyInterface.php';

class FlashSalePricingStrategy implements PricingStrategyInterface
{
    private float $vatRate      = 0.10; 
    private float $shippingFee  = 15000;

    public function calculateShipping(float $subtotal): float
    {
        return $this->shippingFee;
    }

    public function calculateVAT(float $subtotal): float
    {
        return round($subtotal * $this->vatRate, 2);
    }

    public function getName(): string
    {
        return 'flash_sale';
    }

    public function getDescription(): string
    {
        return '⚡ Flash Sale: VAT 10% | Ship chỉ 15.000đ mọi đơn';
    }
}
