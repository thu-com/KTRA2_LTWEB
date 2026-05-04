<div style="max-width:800px;margin:0 auto">
    <div class="page-header" style="display:flex;justify-content:space-between;align-items:center">
        <div>
            <h1><i class="fa fa-receipt"></i> Đơn hàng #<?= $order['id'] ?></h1>
            <p><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
        </div>
        <span class="badge badge-<?= $order['status'] ?>" style="font-size:.9rem;padding:8px 16px">
            <?= (new \Order($order))->getStatusLabel() ?>
        </span>
    </div>

    <!-- Sản phẩm -->
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><i class="fa fa-box"></i> Sản phẩm đã đặt</div>
        <div style="overflow-x:auto">
        <table class="table">
            <thead>
                <tr><th>Sản phẩm</th><th>Đơn giá</th><th>Số lượng</th><th style="text-align:right">Thành tiền</th></tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:40px;height:40px;background:#f8f9fa;border-radius:6px;display:flex;align-items:center;justify-content:center">🛍️</div>
                            <strong><?= htmlspecialchars($item['name']) ?></strong>
                        </div>
                    </td>
                    <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                    <td><?= $item['quantity'] ?></td>
                    <td style="text-align:right;font-weight:600"><?= number_format($item['subtotal'], 0, ',', '.') ?>đ</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
        <!-- Tóm tắt -->
        <div class="card">
            <div class="card-header"><i class="fa fa-calculator"></i> Chi tiết thanh toán</div>
            <div class="card-body">
                <?php
                $o = new \Order($order);
                $strategy = \OrderService::createStrategy($order['pricing_strategy']);
                ?>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border)">
                    <span style="color:#666">Tạm tính</span>
                    <span><?= $o->getFormattedSubtotal() ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border)">
                    <span style="color:#666">VAT (<?= $order['pricing_strategy'] === 'premium' ? '8%' : '10%' ?>)</span>
                    <span><?= $o->getFormattedVAT() ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border)">
                    <span style="color:#666">Phí vận chuyển</span>
                    <span style="color:<?= $order['shipping_fee'] == 0 ? 'var(--success)' : 'inherit' ?>">
                        <?= $order['shipping_fee'] == 0 ? 'Miễn phí' : $o->getFormattedShipping() ?>
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:12px 0;font-weight:700;font-size:1.1rem">
                    <span>TỔNG CỘNG</span>
                    <span style="color:var(--primary)"><?= $o->getFormattedTotal() ?></span>
                </div>
                <div style="background:#f8f9fa;border-radius:8px;padding:10px;font-size:.82rem;color:#888;margin-top:4px">
                    <i class="fa fa-tag"></i>
                    Chiến lược giá: <strong><?= htmlspecialchars($strategy->getDescription()) ?></strong>
                </div>
            </div>
        </div>

        <!-- Thông tin giao hàng -->
        <div class="card">
            <div class="card-header"><i class="fa fa-truck"></i> Thông tin giao hàng</div>
            <div class="card-body">
                <div class="form-group">
                    <label style="color:#888;font-size:.85rem">Địa chỉ nhận hàng</label>
                    <p style="font-weight:600"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
                </div>
                <?php if ($order['note']): ?>
                <div class="form-group" style="margin-bottom:0">
                    <label style="color:#888;font-size:.85rem">Ghi chú</label>
                    <p><?= nl2br(htmlspecialchars($order['note'])) ?></p>
                </div>
                <?php endif; ?>
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                    <label style="color:#888;font-size:.85rem">Trạng thái</label>
                    <p><span class="badge badge-<?= $order['status'] ?>"><?= $o->getStatusLabel() ?></span></p>
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px;margin-top:20px">
        <a href="<?= APP_URL ?>/orders" class="btn btn-dark">
            <i class="fa fa-arrow-left"></i> Danh sách đơn hàng
        </a>
        <a href="<?= APP_URL ?>/products" class="btn btn-primary">
            <i class="fa fa-shopping-bag"></i> Tiếp tục mua sắm
        </a>
    </div>
</div>
