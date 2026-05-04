<div class="page-header">
    <h1><i class="fa fa-clipboard-list"></i> Đơn hàng của tôi</h1>
    <p>Lịch sử mua hàng</p>
</div>

<?php if (empty($orders)): ?>
<div class="card" style="text-align:center;padding:60px">
    <div style="font-size:4rem;margin-bottom:16px">📋</div>
    <h2 style="color: #888;margin-bottom:12px">Chưa có đơn hàng nào</h2>
    <a href="<?= APP_URL ?>/products" class="btn btn-primary">
        <i class="fa fa-shopping-bag"></i> Bắt đầu mua sắm
    </a>
</div>
<?php else: ?>
<div class="card">
    <div style="overflow-x:auto">
    <table class="table">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Phương thức</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order):
                $o = new \Order($order); ?>
            <tr>
                <td><strong>#<?= $order['id'] ?></strong></td>
                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                <td style="font-weight:700;color:var(--primary)"><?= $o->getFormattedTotal() ?></td>
                <td>
                    <?php $s = \OrderService::createStrategy($order['pricing_strategy']); ?>
                    <span style="font-size:.82rem;color:#888"><?= htmlspecialchars($s->getName()) ?></span>
                </td>
                <td><span class="badge badge-<?= $order['status'] ?>"><?= $o->getStatusLabel() ?></span></td>
                <td>
                    <a href="<?= APP_URL ?>/orders/<?= $order['id'] ?>" class="btn btn-dark btn-sm">
                        <i class="fa fa-eye"></i> Xem
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?php endif; ?>
