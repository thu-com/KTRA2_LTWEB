<?php
// views/admin/index.php
// Được include bởi AdminController sau khi đã include header.php
// Biến cần truyền từ controller: $orders (array), $stats (array)
?>

<div class="page-header">
    <h1>
        <i class="fa fa-gauge" style="color: var(--primary);"></i>
        HIỆU SUẤT CỬA HÀNG
    </h1>
    <p>Tổng quan hoạt động cửa hàng MOW Garden</p>
</div>

<!-- ══ THỐNG KÊ NHANH ══ -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 32px;">

    <?php
    $statCards = [
        [
            'icon'  => 'fa-sack-dollar',
            'label' => 'Doanh thu tháng',
            'value' => number_format($stats['revenue'] ?? 12500000, 0, ',', '.') . 'đ',
            'delta' => '↑ 12% so với tháng trước',
            'color' => '#3a6b45',
            'bg'    => '#e8f3ec',
        ],
        [
            'icon'  => 'fa-box',
            'label' => 'Đơn hàng mới',
            'value' => $stats['new_orders'] ?? 24,
            'delta' => '↑ 8% so với tháng trước',
            'color' => '#1a6a7c',
            'bg'    => '#e0f4f8',
        ],
        [
            'icon'  => 'fa-users',
            'label' => 'Khách hàng',
            'value' => $stats['total_users'] ?? 148,
            'delta' => '5 mới trong tuần này',
            'color' => '#7a5c2a',
            'bg'    => '#faf3e0',
        ],
        [
            'icon'  => 'fa-seedling',
            'label' => 'Sản phẩm',
            'value' => $stats['total_products'] ?? 63,
            'delta' => '3 sắp hết hàng',
            'color' => '#8b2020',
            'bg'    => '#fdecea',
        ],
    ];
    foreach ($statCards as $s): ?>
    <div class="card" style="border-left: 4px solid <?= $s['color'] ?>;">
        <div class="card-body" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: .78rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); font-weight: 600; margin-bottom: 8px;">
                        <?= $s['label'] ?>
                    </div>
                    <div style="font-size: 1.7rem; font-weight: 700; color: var(--dark); line-height: 1;">
                        <?= $s['value'] ?>
                    </div>
                    <div style="font-size: .78rem; color: var(--muted); margin-top: 6px;">
                        <?= $s['delta'] ?>
                    </div>
                </div>
                <div style="
                    background: <?= $s['bg'] ?>; color: <?= $s['color'] ?>;
                    width: 44px; height: 44px; border-radius: 12px;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 1.2rem;
                ">
                    <i class="fa <?= $s['icon'] ?>"></i>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ══ HAI CỘT: Đơn gần đây + Sản phẩm bán chạy ══ -->
<div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 22px; margin-bottom: 22px;">

    <!-- Đơn hàng gần đây -->
    <div class="card">
        <div class="card-header">
            <i class="fa fa-clock-rotate-left"></i> Đơn hàng gần đây
            <a href="<?= APP_URL ?>/admin/orders"
               style="margin-left: auto; font-size: .82rem; color: #8ecf9e; font-weight: 400;">
                Xem tất cả →
            </a>
        </div>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Khách hàng</th>
                        <th>Tổng</th>
                        <th>Chiến lược</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Dùng $orders từ controller – giống oop_shop admin.php gốc
                    // Fallback placeholder nếu controller chưa truyền
                    $displayOrders = $orders ?? [
                        ['id'=>1024,'user_name'=>'Nguyễn Lan','total'=>350000,'pricing_strategy'=>'Standard','status'=>'pending'],
                        ['id'=>1023,'user_name'=>'Trần Minh', 'total'=>720000,'pricing_strategy'=>'VIP',     'status'=>'confirmed'],
                        ['id'=>1022,'user_name'=>'Lê Hương',  'total'=>210000,'pricing_strategy'=>'Standard','status'=>'shipped'],
                        ['id'=>1021,'user_name'=>'Phạm Việt', 'total'=>880000,'pricing_strategy'=>'Holiday', 'status'=>'delivered'],
                        ['id'=>1020,'user_name'=>'Hoàng An',  'total'=>145000,'pricing_strategy'=>'Standard','status'=>'cancelled'],
                    ];

                    $statusMap = [
                        'pending'   => ['badge-pending',   'Chờ xác nhận'],
                        'confirmed' => ['badge-confirmed', 'Đã xác nhận'],
                        'shipped'   => ['badge-shipped',   'Đang giao'],
                        'delivered' => ['badge-delivered', 'Đã giao'],
                        'cancelled' => ['badge-cancelled', 'Đã huỷ'],
                    ];

                    foreach ($displayOrders as $order):
                        // Nếu có object Order (giống oop_shop): $o = new \Order($order);
                        // Ở đây format tay cho đơn giản
                        $total    = number_format($order['total'] ?? 0, 0, ',', '.') . 'đ';
                        [$bClass, $bLabel] = $statusMap[$order['status']] ?? $statusMap['pending'];
                    ?>
                    <tr>
                        <td><strong style="color: var(--muted);">#<?= $order['id'] ?></strong></td>
                        <td><?= htmlspecialchars($order['user_name']) ?></td>
                        <td style="font-weight: 600; color: var(--primary);"><?= $total ?></td>
                        <td><small style="color: var(--muted);"><?= htmlspecialchars($order['pricing_strategy']) ?></small></td>
                        <td>
                            <!-- Inline select đổi trạng thái – giống oop_shop -->
                            <select class="form-control status-select"
                                    data-id="<?= $order['id'] ?>"
                                    style="padding: 4px 8px; font-size: .8rem; width: auto; border-radius: 6px;">
                                <?php foreach ([
                                    'pending'   => 'Chờ xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'shipped'   => 'Đang giao',
                                    'delivered' => 'Đã giao',
                                    'cancelled' => 'Đã huỷ',
                                ] as $val => $lbl): ?>
                                <option value="<?= $val ?>" <?= ($order['status'] === $val) ? 'selected' : '' ?>>
                                    <?= $lbl ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <a href="<?= APP_URL ?>/orders/<?= $order['id'] ?>"
                               class="btn btn-dark btn-sm">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top sản phẩm bán chạy -->
    <div class="card">
        <div class="card-header">
            <i class="fa fa-fire"></i> Bán chạy nhất
            <a href="<?= APP_URL ?>/admin/products"
               style="margin-left: auto; font-size: .82rem; color: #8ecf9e; font-weight: 400;">
                Quản lý →
            </a>
        </div>
        <?php
        $topProducts = $top_products ?? [
            ['name'=>'Xương rồng mini',      'cat'=>'Cây để bàn',  'revenue'=>'2.400.000đ'],
            ['name'=>'Trầu bà hoàng hậu',   'cat'=>'Cây leo',     'revenue'=>'1.850.000đ'],
            ['name'=>'Sen đá mix màu',       'cat'=>'Cây thịt',   'revenue'=>'1.620.000đ'],
            ['name'=>'Chậu xi măng dáng cao','cat'=>'Chậu cây',   'revenue'=>'1.200.000đ'],
            ['name'=>'Đất trồng cao cấp',    'cat'=>'Phụ kiện',   'revenue'=>'980.000đ'],
        ];
        foreach ($topProducts as $p): ?>
        <div style="
            display: flex; align-items: center; gap: 14px;
            padding: 13px 20px;
            border-bottom: 1px solid var(--cream-dark);
            font-size: .88rem;
        ">
            <div style="
                width: 42px; height: 42px; border-radius: 10px;
                background: var(--primary-light);
                display: flex; align-items: center; justify-content: center;
                font-size: 1.3rem; flex-shrink: 0;
            "><?= $p['emoji'] ?></div>
            <div style="flex: 1;">
                <div style="font-weight: 600; color: var(--dark);"><?= htmlspecialchars($p['name']) ?></div>
                <div style="font-size: .78rem; color: var(--muted);"><?= htmlspecialchars($p['cat']) ?></div>
            </div>
            <div style="font-weight: 700; color: var(--primary); white-space: nowrap;">
                <?= $p['revenue'] ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ══ THAO TÁC NHANH ══ -->
<div class="card">
    <div class="card-header">
        <i class="fa fa-bolt"></i> Thao tác nhanh
    </div>
    <div class="card-body" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="<?= APP_URL ?>/admin/products/create" class="btn btn-primary">
            <i class="fa fa-plus"></i> Thêm sản phẩm
        </a>
        <a href="<?= APP_URL ?>/admin/orders?status=pending" class="btn btn-outline">
            <i class="fa fa-list-check"></i> Xử lý đơn chờ (<?= $stats['pending_orders'] ?? 5 ?>)
        </a>
        <a href="<?= APP_URL ?>/admin/products?low_stock=1" class="btn btn-danger">
            <i class="fa fa-triangle-exclamation"></i> Sản phẩm sắp hết hàng
        </a>
        <a href="<?= APP_URL ?>/" class="btn btn-dark" target="_blank">
            <i class="fa fa-arrow-up-right-from-square"></i> Xem cửa hàng
        </a>
    </div>
</div>

<!-- Script đổi trạng thái đơn hàng – giống y oop_shop gốc -->
<script>
document.querySelectorAll('.status-select').forEach(sel => {
    sel.addEventListener('change', function () {
        const id     = this.dataset.id;
        const status = this.value;
        fetch('<?= APP_URL ?>/admin/orders/status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `order_id=${id}&status=${status}`
        })
        .then(r => r.json())
        .then(d => showToast(d.message, d.success ? 'success' : 'error'))
        .catch(() => showToast('Lỗi kết nối', 'error'));
    });
});
</script>