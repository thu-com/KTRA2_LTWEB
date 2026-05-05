<div class="page-header">
    <h1><i class="fa fa-cogs"></i> Quản lý đơn hàng</h1>
    <p>Tổng cộng <?= count($orders) ?> đơn hàng</p>
</div>

<div class="card">
    <div style="overflow-x:auto">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Tạm tính</th>
                <th>VAT</th>
                <th>Ship</th>
                <th>Tổng</th>
                <th>Chiến lược</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order):
                $o = new \Order($order); ?>
            <tr id="order-row-<?= $order['id'] ?>">
                <td><strong>#<?= $order['id'] ?></strong></td>
                <td>
                    <div><?= htmlspecialchars($order['user_name']) ?></div>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                <td><?= $o->getFormattedSubtotal() ?></td>
                <td><?= $o->getFormattedVAT() ?></td>
                <td><?= $order['shipping_fee'] == 0 ? '<span style="color:green">Miễn phí</span>' : $o->getFormattedShipping() ?></td>
                <td style="font-weight:700;color:var(--primary)"><?= $o->getFormattedTotal() ?></td>
                <td><small><?= htmlspecialchars($order['pricing_strategy']) ?></small></td>
                <td>
                    <select class="form-control status-select"
                            data-id="<?= $order['id'] ?>"
                            style="padding:4px 8px;font-size:.82rem;width:auto">
                        <?php foreach (['pending'=>'Chờ xác nhận','confirmed'=>'Đã xác nhận','shipped'=>'Đang giao','delivered'=>'Đã giao','cancelled'=>'Đã huỷ'] as $val => $lbl): ?>
                        <option value="<?= $val ?>" <?= $order['status'] === $val ? 'selected' : '' ?>>
                            <?= $lbl ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <a href="<?= APP_URL ?>/orders/<?= $order['id'] ?>" class="btn btn-dark btn-sm">
                        <i class="fa fa-eye"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<script>
document.querySelectorAll('.status-select').forEach(sel => {
    sel.addEventListener('change', function() {
        const id = this.dataset.id;
        const status = this.value;
        fetch('<?= APP_URL ?>/admin/orders/status', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
            body: `order_id=${id}&status=${status}`
        })
        .then(r => r.json())
        .then(d => showToast(d.message, d.success ? 'success' : 'error'))
        .catch(() => showToast('Lỗi kết nối','error'));
    });
});
</script>
