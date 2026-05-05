<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <h1><i class="fa fa-box-open"></i> Sản phẩm</h1>
        <p><?= number_format($total) ?> sản phẩm tìm thấy</p>
    </div>
    <a href="<?= APP_URL ?>/cart" class="btn btn-primary">
        <i class="fa fa-shopping-cart"></i> Xem giỏ hàng
    </a>
</div>

<!-- ── Tìm kiếm & Lọc ─────────────────────────────────── -->
<div class="card" style="margin-bottom:24px">
    <div class="card-body">
        <form method="GET" action="<?= APP_URL ?>/products" style="display:flex;gap:10px;flex-wrap:wrap">
            <input type="text" name="search" class="form-control" style="flex:1;min-width:180px"
                   placeholder="🔍 Tìm sản phẩm..." value="<?= htmlspecialchars($search) ?>">
            <select name="cat" class="form-control" style="width:180px">
                <option value="0">Tất cả danh mục</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $catId == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-dark"><i class="fa fa-search"></i> Tìm</button>
            <?php if ($search || $catId): ?>
                <a href="<?= APP_URL ?>/products" class="btn btn-outline">
                    <i class="fa fa-times"></i> Xoá lọc
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- ── Danh sách sản phẩm ──────────────────────────────── -->
<?php if (empty($products)): ?>
    <div style="text-align:center;padding:60px;color:#888">
        <i class="fa fa-search" style="font-size:3rem;margin-bottom:16px;opacity:.3"></i>
        <p style="font-size:1.1rem">Không tìm thấy sản phẩm nào.</p>
        <a href="<?= APP_URL ?>/products" class="btn btn-primary" style="margin-top:12px">Xem tất cả</a>
    </div>
<?php else: ?>
<div class="grid grid-4" style="margin-bottom:32px">
    <?php foreach ($products as $product): ?>
    <div class="card" style="display:flex;flex-direction:column;transition:.2s" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
        <a href="<?= APP_URL ?>/products/<?= $product->getId() ?>">
            <div style="height:180px;overflow:hidden;position:relative">
    <img src="<?= APP_URL ?>/assets/images/products/<?= htmlspecialchars($product->getImage()) ?>"
         alt="<?= htmlspecialchars($product->getName()) ?>"
         onerror="this.src='<?= APP_URL ?>/assets/images/products/default.jpg'"
         style="width:100%;height:100%;object-fit:cover">
                <?php if ($product->getStock() === 0): ?>
                    <span style="position:absolute;top:8px;right:8px;background:var(--danger);color:#fff;font-size:.7rem;padding:3px 8px;border-radius:4px;font-weight:700">HẾT HÀNG</span>
                <?php elseif ($product->getStock() < 5): ?>
                    <span style="position:absolute;top:8px;right:8px;background:#f39c12;color:#fff;font-size:.7rem;padding:3px 8px;border-radius:4px;font-weight:700">SẮP HẾT</span>
                <?php endif; ?>
            </div>
        </a>
        <div class="card-body" style="flex:1;display:flex;flex-direction:column">
            <p style="font-size:.75rem;color:var(--primary);margin-bottom:4px"><?= htmlspecialchars($product->getCategoryId() ? '' : '') ?></p>
            <h3 style="font-size:.95rem;margin-bottom:8px;flex:1;color:var(--dark)">
                <a href="<?= APP_URL ?>/products/<?= $product->getId() ?>" style="color:inherit">
                    <?= htmlspecialchars($product->getName()) ?>
                </a>
            </h3>
            <div style="font-size:1.1rem;font-weight:700;color:var(--primary);margin-bottom:10px">
                <?= $product->getFormattedPrice() ?>
            </div>
            <div style="font-size:.8rem;color:#888;margin-bottom:12px">
                Còn lại: <?= $product->getStock() ?> sản phẩm
            </div>
            <?php if ($product->isAvailable()): ?>
            <?php if (\AuthService::isLoggedIn()): ?>
            <button class="btn btn-primary btn-sm btn-add-cart"
                    data-id="<?= $product->getId() ?>"
                    data-name="<?= htmlspecialchars($product->getName()) ?>">
                <i class="fa fa-cart-plus"></i> Thêm vào giỏ
            </button>
            <?php else: ?>
            <a href="<?= APP_URL ?>/auth/login" class="btn btn-outline btn-sm">
                <i class="fa fa-sign-in-alt"></i> Đăng nhập để mua
            </a>
            <?php endif; ?>
            <?php else: ?>
            <button class="btn btn-sm" style="background:#ccc;color:#666;cursor:not-allowed" disabled>
                Hết hàng
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ── Phân trang ──────────────────────────────────────── -->
<?php if ($pages > 1): ?>
<div style="display:flex;justify-content:center;gap:8px;flex-wrap:wrap;margin-top:8px">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&cat=<?= $catId ?>"
           style="padding:8px 14px;border-radius:8px;background:<?= $i == $page ? 'var(--primary)' : '#fff' ?>;
                  color:<?= $i == $page ? '#fff' : 'var(--dark)' ?>;
                  font-weight:600;box-shadow:var(--shadow)">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>
<?php endif; ?>
<?php endif; ?>

<script>
document.querySelectorAll('.btn-add-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const id   = this.dataset.id;
        const name = this.dataset.name;
        const orig = this.innerHTML;
        this.innerHTML = '<span class="spinner"></span>';
        this.disabled = true;

        fetch('<?= APP_URL ?>/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `product_id=${id}&quantity=1`
        })
        .then(r => r.json())
        .then(d => {
            showToast(d.message, d.success ? 'success' : 'error');
            if (d.success) {
                const badge = document.getElementById('cart-badge');
                if (badge && d.cartCount !== undefined) badge.textContent = d.cartCount;
            }
        })
        .catch(() => showToast('Lỗi kết nối', 'error'))
        .finally(() => {
            this.innerHTML = orig;
            this.disabled = false;
        });
    });
});
</script>
