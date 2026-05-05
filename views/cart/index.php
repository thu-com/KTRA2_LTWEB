<div class="page-header">
    <h1><i class="fa fa-shopping-cart"></i> Giỏ hàng của tôi</h1>
    <p><?= $cartData['total_quantity'] ?> sản phẩm</p>
</div>

<?php if ($cartData['is_empty']): ?>
<div class="card" style="text-align:center;padding:60px">
    <div style="font-size:4rem;margin-bottom:16px">🛒</div>
    <h2 style="color:#888;margin-bottom:12px">Giỏ hàng trống</h2>
    <p style="color:#aaa;margin-bottom:24px">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục.</p>
    <a href="<?= APP_URL ?>/products" class="btn btn-primary">
        <i class="fa fa-shopping-bag"></i> Tiếp tục mua sắm
    </a>
</div>
<?php else: ?>

<div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start">
    <!-- Danh sách items -->
    <div class="card">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center">
            <span><i class="fa fa-list"></i> Sản phẩm (<?= $cartData['item_count'] ?>)</span>
            <button onclick="clearCartConfirm()" class="btn btn-danger btn-sm">
                <i class="fa fa-trash"></i> Xoá tất cả
            </button>
        </div>
        <div id="cart-items-container">
        <?php foreach ($cartData['items'] as $pid => $item): ?>
        <div class="cart-row" id="row-<?= $item['product_id'] ?>"
             style="display:flex;align-items:center;gap:16px;padding:16px 20px;border-bottom:1px solid var(--border)">
            <!-- Icon -->
            <div style="width:64px;height:64px;background:#f8f9fa;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;flex-shrink:0">🛍️</div>
            <!-- Info -->
            <div style="flex:1;min-width:0">
                <a href="<?= APP_URL ?>/products/<?= $item['product_id'] ?>"
                   style="font-weight:600;color:var(--dark)">
                    <?= htmlspecialchars($item['name']) ?>
                </a>
                <div style="color:var(--primary);font-weight:700;margin-top:4px">
                    <?= number_format($item['price'], 0, ',', '.') ?>đ / sản phẩm
                </div>
                <div style="font-size:.8rem;color:#888">Còn <?= $item['stock'] ?> trong kho</div>
            </div>
            <!-- Số lượng -->
            <div style="display:flex;align-items:center;gap:8px">
                <button class="qty-btn btn btn-sm btn-outline"
                        data-id="<?= $item['product_id'] ?>"
                        data-action="dec"
                        style="padding:4px 10px;font-size:1rem">−</button>
                <input type="number" class="qty-input form-control"
                       data-id="<?= $item['product_id'] ?>"
                       value="<?= $item['quantity'] ?>"
                       min="1" max="<?= $item['stock'] ?>"
                       style="width:60px;text-align:center;padding:5px 8px">
                <button class="qty-btn btn btn-sm btn-outline"
                        data-id="<?= $item['product_id'] ?>"
                        data-action="inc"
                        style="padding:4px 10px;font-size:1rem">+</button>
            </div>
            <!-- Thành tiền -->
            <div style="text-align:right;min-width:110px">
                <div style="font-weight:700;color:var(--dark)" id="sub-<?= $item['product_id'] ?>">
                    <?= number_format($item['subtotal'], 0, ',', '.') ?>đ
                </div>
            </div>
            <!-- Xoá -->
            <button class="btn btn-danger btn-sm btn-remove"
                    data-id="<?= $item['product_id'] ?>"
                    style="flex-shrink:0">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <?php endforeach; ?>
        </div>
    </div>

    <!-- Tóm tắt -->
    <div class="card" style="position:sticky;top:80px">
        <div class="card-header"><i class="fa fa-receipt"></i> Tóm tắt đơn hàng</div>
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;margin-bottom:10px;font-size:.95rem">
                <span style="color:#666">Tạm tính (<?= $cartData['total_quantity'] ?> sp)</span>
                <span id="cart-total" style="font-weight:600"><?= $cartData['total_formatted'] ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:10px;font-size:.9rem;color:#888">
                <span>Phí vận chuyển</span>
                <span>Tính khi thanh toán</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:10px;font-size:.9rem;color:#888">
                <span>VAT</span>
                <span>Tính khi thanh toán</span>
            </div>
            <hr style="margin:14px 0">
            <a href="<?= APP_URL ?>/checkout" class="btn btn-success btn-block" style="font-size:1rem;padding:12px">
                <i class="fa fa-credit-card"></i> Thanh toán ngay
            </a>
            <a href="<?= APP_URL ?>/products" class="btn btn-outline btn-block" style="margin-top:10px">
                <i class="fa fa-arrow-left"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>

<?php endif; ?>

<script>
const BASE = '<?= APP_URL ?>';

function refreshCartTotal(cartData) {
    const el = document.getElementById('cart-total');
    if (el && cartData) el.textContent = cartData.total_formatted;
    const badge = document.getElementById('cart-badge');
    if (badge && cartData) badge.textContent = cartData.total_quantity;
}

function ajaxCart(url, body, onSuccess) {
    fetch(BASE + url, {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
        body
    })
    .then(r => r.json())
    .then(d => {
        showToast(d.message, d.success ? 'success' : 'error');
        if (d.success) onSuccess(d);
    })
    .catch(() => showToast('Lỗi kết nối','error'));
}

// Nút +/-
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id    = this.dataset.id;
        const input = document.querySelector(`.qty-input[data-id="${id}"]`);
        let qty = parseInt(input.value);
        if (this.dataset.action === 'inc') qty++;
        else qty = Math.max(1, qty - 1);
        input.value = qty;
        updateItem(id, qty);
    });
});

// Input trực tiếp
document.querySelectorAll('.qty-input').forEach(inp => {
    inp.addEventListener('change', function() {
        const id  = this.dataset.id;
        const qty = Math.max(1, parseInt(this.value) || 1);
        this.value = qty;
        updateItem(id, qty);
    });
});

function updateItem(id, qty) {
    ajaxCart('/cart/update', `product_id=${id}&quantity=${qty}`, d => {
        const sub = document.getElementById('sub-' + id);
        if (sub && d.cartData?.items) {
            const item = Object.values(d.cartData.items).find(i => i.product_id == id);
            if (item) sub.textContent = Number(item.subtotal).toLocaleString('vi-VN') + 'đ';
        }
        refreshCartTotal(d.cartData);
    });
}

// Xoá item
document.querySelectorAll('.btn-remove').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        if (!confirm('Xóa sản phẩm này khỏi giỏ hàng?')) return;
        ajaxCart('/cart/remove', `product_id=${id}`, d => {
            document.getElementById('row-' + id)?.remove();
            refreshCartTotal(d.cartData);
            if (d.cartData?.is_empty) location.reload();
        });
    });
});

function clearCartConfirm() {
    if (confirm('Xóa toàn bộ giỏ hàng?')) location.href = BASE + '/cart/clear';
}
</script>
