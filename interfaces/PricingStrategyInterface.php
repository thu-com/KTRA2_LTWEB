<?php
// ============================================================
//  interfaces/PricingStrategyInterface.php  –  Strategy Pattern
// ============================================================

interface PricingStrategyInterface
{
    /**
     * Tính phí vận chuyển dựa trên tổng đơn hàng.
     *
     * @param float $subtotal Tổng tiền hàng (chưa VAT)
     * @return float Phí vận chuyển
     */
    public function calculateShipping(float $subtotal): float;

    /**
     * Tính số tiền VAT.
     *
     * @param float $subtotal Tổng tiền hàng
     * @return float Số tiền VAT
     */
    public function calculateVAT(float $subtotal): float;

    /**
     * Tên chiến lược (hiển thị cho người dùng).
     */
    public function getName(): string;

    /**
     * Mô tả chiến lược.
     */
    public function getDescription(): string;
}
