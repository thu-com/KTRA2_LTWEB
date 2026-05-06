<div style="max-width:900px;margin:0 auto">
    <!-- Breadcrumb -->
    <div style="color:#888;font-size:.85rem;margin-bottom:20px">
        <a href="<?= APP_URL ?>/products" style="color:var(--primary)">Sản phẩm</a>
        <span> / <?= htmlspecialchars($product->getName()) ?></span>
    </div>

    <div class="card">
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px">
                <!-- Ảnh -->
                <img src="<?= APP_URL ?>/assets/images/products/<?= htmlspecialchars($product->getImage()) ?>"
     alt="<?= htmlspecialchars($product->getName()) ?>"
     onerror="this.src='<?= APP_URL ?>/assets/images/products/default.jpg'"
     style="width:100%; height:400px; object-fit:cover ; border-radius:10px; border:1px solid #e9ecef;">
                <!-- Thông tin -->
                <div style="display:flex;flex-direction:column;gap:16px">
                    <h1 style="font-size:1.4rem;color:var(--dark)"><?= htmlspecialchars($product->getName()) ?></h1>
                    <div style="font-size:2rem;font-weight:700;color:var(--primary)">
                        <?= $product->getFormattedPrice() ?>
                    </div>
                    <p style="color:#666;line-height:1.7"><?= nl2br(htmlspecialchars($product->getDescription())) ?></p>

                    <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8f9fa;border-radius:8px">
                        <i class="fa fa-warehouse" style="color:var(--primary)"></i>
                        <span style="font-weight:600">Tồn kho:</span>
                        <?php if ($product->getStock() > 0): ?>
                            <span style="color:var(--success);font-weight:600"><?= $product->getStock() ?> sản phẩm</span>
                        <?php else: ?>
                            <span style="color:var(--danger);font-weight:600">Hết hàng</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($product->isAvailable()): ?>
                        <?php if (\AuthService::isLoggedIn()): ?>
                        <div style="display:flex;gap:10px;align-items:center">
                            <input type="number" id="detail-qty" value="1" min="1"
                                   max="<?= $product->getStock() ?>"
                                   style="width:70px;padding:8px;border:1.5px solid var(--border);border-radius:8px;font-size:1rem;text-align:center">
                            <button class="btn btn-primary" id="btn-detail-add"
                                    data-id="<?= $product->getId() ?>">
                                <i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng
                            </button>
                        </div>

                        <?php else: ?>
                        <a href="<?= APP_URL ?>/auth/login" class="btn btn-primary">
                            <i class="fa fa-sign-in-alt"></i> Đăng nhập để mua hàng
                        </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn" style="background:#ccc;cursor:not-allowed" disabled>Hết hàng</button>
                    <?php endif; ?>

                    <a href="<?= APP_URL ?>/products" class="btn btn-outline btn-sm" style="width:fit-content">
                        <i class="fa fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm liên quan -->
<?php if (!empty($related)): ?>
    <div style="margin-top:30px">
        <h2 style="font-size:1.2rem;color:var(--dark);margin-bottom:16px">
            <i class="fa fa-th"></i> Sản phẩm tương tự
        </h2>
        <div class="grid grid-4">
            <?php foreach ($related as $r): ?>
            <div class="card">
                
                <!-- Bắt đầu phần hiển thị ảnh đã sửa -->
                <div style="height:200px; background:#f8f9fa; overflow:hidden;">
                    <img src="<?= APP_URL ?>/assets/images/products/<?= htmlspecialchars($r->getImage()) ?>"
                         alt="<?= htmlspecialchars($r->getName()) ?>"
                         onerror="this.src='<?= APP_URL ?>/assets/images/products/default.jpg'"
                         style="width:100%; height:100%; object-fit:cover;">
                </div>
                <!-- Kết thúc phần hiển thị ảnh -->

                <div class="card-body" style="padding:12px">
                    <a href="<?= APP_URL ?>/products/<?= $r->getId() ?>" style="font-weight:600;font-size:.9rem;color:var(--dark)">
                        <?= htmlspecialchars($r->getName()) ?>
                    </a>
                    <div style="color:var(--primary);font-weight:700;margin-top:6px"><?= $r->getFormattedPrice() ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<script>
const btnAdd = document.getElementById('btn-detail-add');
if (btnAdd) {
    btnAdd.addEventListener('click', function() {
        const qty = parseInt(document.getElementById('detail-qty').value) || 1;
        const id  = this.dataset.id;
        const orig = this.innerHTML;
        this.innerHTML = '<span class="spinner"></span>';
        this.disabled = true;

        fetch('<?= APP_URL ?>/cart/add', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
            body: `product_id=${id}&quantity=${qty}`
        })
        .then(r => r.json())
        .then(d => {
            showToast(d.message, d.success ? 'success' : 'error');
            if (d.success) {
                const badge = document.getElementById('cart-badge');
                if (badge && d.cartCount !== undefined) badge.textContent = d.cartCount;
            }
        })
        .catch(() => showToast('Lỗi kết nối','error'))
        .finally(() => { this.innerHTML = orig; this.disabled = false; });
    });
}

</script>

