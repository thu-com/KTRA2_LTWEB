<div class="page-header">
    <h1><i class="fa fa-credit-card"></i> Thanh toán</h1>
    <p>Xem xét đơn hàng và chọn phương thức tính phí</p>
</div>

<form method="POST" action="<?= APP_URL ?>/checkout" id="checkout-form">
<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">

    <!-- LEFT: Form + Chiến lược -->
    <div style="display:flex;flex-direction:column;gap:20px">

        <!-- Địa chỉ giao hàng -->
        <div class="card">
            <div class="card-header"><i class="fa fa-map-marker-alt"></i> Địa chỉ giao hàng</div>
            <div class="card-body">
                <div class="form-group">
                    <label>Họ và tên người nhận</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" readonly style="background:#f8f9fa">
                </div>
                <div class="form-group">
                    <label for="shipping_address">Địa chỉ giao hàng <span style="color:var(--danger)">*</span></label>
                    <textarea id="shipping_address" name="shipping_address" class="form-control"
                              rows="3" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố"
                              required style="resize:vertical"></textarea>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label for="note">Ghi chú đơn hàng</label>
                    <textarea id="note" name="note" class="form-control" rows="2"
                              placeholder="Giao hàng giờ hành chính, gọi trước khi giao..."></textarea>
                </div>
            </div>
        </div>

        <!-- Chiến lược giá – Strategy Pattern -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-tags"></i> Phương thức tính phí
                <small style="font-weight:400;color:#aaa"> (Strategy Pattern)</small>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:12px">
                <?php
                $strategyLabels = ['standard' => '📦 Tiêu chuẩn', 'premium' => '⭐ VIP Member', 'flash_sale' => '⚡ Flash Sale'];
                foreach ($strategies as $key => $data):
                ?>
                <label class="strategy-card" data-key="<?= $key ?>"
                       style="border:2px solid var(--border);border-radius:10px;padding:14px 16px;cursor:pointer;transition:.2s;display:flex;gap:14px;align-items:center">
                    <input type="radio" name="pricing_strategy" value="<?= $key ?>"
                           <?= $key === 'standard' ? 'checked' : '' ?>
                           style="accent-color:var(--primary);width:18px;height:18px">
                    <div style="flex:1">
                        <div style="font-weight:700;font-size:.95rem"><?= $strategyLabels[$key] ?></div>
                        <div style="font-size:.82rem;color:#888;margin-top:2px"><?= htmlspecialchars($data['description']) ?></div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:.8rem;color:#888">Ship</div>
                        <div style="font-weight:700;color:<?= $data['shipping'] == 0 ? 'var(--success)' : 'var(--primary)' ?>">
                            <?= $data['shipping'] == 0 ? 'Miễn phí' : number_format($data['shipping'],0,',','.').'đ' ?>
                        </div>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sản phẩm -->
        <div class="card">
            <div class="card-header"><i class="fa fa-list"></i> Sản phẩm trong đơn</div>
            <div style="overflow-x:auto">
                <table class="table">
                    <thead>
                        <tr><th>Sản phẩm</th><th>Đơn giá</th><th>SL</th><th style="text-align:right">Thành tiền</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartData['items'] as $item): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                            <td><?= $item['quantity'] ?></td>
                            <td style="text-align:right;font-weight:600"><?= number_format($item['subtotal'], 0, ',', '.') ?>đ</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RIGHT: Tổng tiền -->
    <div class="card" style="position:sticky;top:80px">
        <div class="card-header"><i class="fa fa-calculator"></i> Tổng cộng</div>
        <div class="card-body" id="pricing-summary">
            <?php $std = $strategies['standard']; ?>
            <div style="display:flex;justify-content:space-between;margin-bottom:10px">
                <span style="color:#666">Tạm tính</span>
                <span style="font-weight:600" id="sum-sub"><?= number_format($std['subtotal'],0,',','.')?>đ</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:10px">
                <span style="color:#666">VAT</span>
                <span style="font-weight:600" id="sum-vat"><?= number_format($std['vat'],0,',','.')?>đ</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:16px">
                <span style="color:#666">Phí vận chuyển</span>
                <span style="font-weight:600;color:<?= $std['shipping']==0?'var(--success)':'var(--primary)'?>" id="sum-ship">
                    <?= $std['shipping']==0 ? 'Miễn phí' : number_format($std['shipping'],0,',','.').'đ' ?>
                </span>
            </div>
            <hr style="margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;margin-bottom:20px">
                <span style="font-size:1.1rem;font-weight:700">TỔNG CỘNG</span>
                <span style="font-size:1.3rem;font-weight:700;color:var(--primary)" id="sum-total">
                    <?= number_format($std['total'],0,',','.')?>đ
                </span>
            </div>
            <button type="submit" class="btn btn-success btn-block" style="font-size:1.05rem;padding:13px">
                <i class="fa fa-check-circle"></i> Đặt hàng
            </button>
            <a href="<?= APP_URL ?>/cart" class="btn btn-outline btn-block" style="margin-top:10px">
                <i class="fa fa-arrow-left"></i> Quay lại giỏ hàng
            </a>
        </div>
    </div>
</div>
</form>

<script>
const strategies = <?= json_encode($strategies, JSON_UNESCAPED_UNICODE) ?>;

function fmt(n) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(n)) + 'đ';
}

// Làm nổi strategy được chọn
function highlightStrategy(key) {
    document.querySelectorAll('.strategy-card').forEach(el => {
        el.style.borderColor = el.dataset.key === key ? 'var(--primary)' : 'var(--border)';
        el.style.background  = el.dataset.key === key ? '#fff8f3' : '#fff';
    });
}

// Cập nhật tổng khi đổi chiến lược
document.querySelectorAll('input[name="pricing_strategy"]').forEach(r => {
    r.addEventListener('change', function() {
        const d = strategies[this.value];
        document.getElementById('sum-sub').textContent   = fmt(d.subtotal);
        document.getElementById('sum-vat').textContent   = fmt(d.vat);
        const ship = document.getElementById('sum-ship');
        ship.textContent  = d.shipping === 0 ? 'Miễn phí' : fmt(d.shipping);
        ship.style.color  = d.shipping === 0 ? 'var(--success)' : 'var(--primary)';
        document.getElementById('sum-total').textContent = fmt(d.total);
        highlightStrategy(this.value);
    });
});

// Cho click vào toàn bộ card
document.querySelectorAll('.strategy-card').forEach(card => {
    card.addEventListener('click', function() {
        const r = this.querySelector('input[type=radio]');
        r.checked = true; r.dispatchEvent(new Event('change'));
    });
});

highlightStrategy('standard');

// Submit validation
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const addr = document.getElementById('shipping_address').value.trim();
    if (!addr) {
        e.preventDefault();
        showToast('Vui lòng nhập địa chỉ giao hàng!', 'error');
        document.getElementById('shipping_address').focus();
    }
});
</script>
