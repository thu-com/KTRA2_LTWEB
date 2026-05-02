<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'MOW Garden') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Be+Vietnam+Pro:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:       #3a6b45;
            --primary-dark:  #2a4f33;
            --primary-light: #e8f3ec;
            --accent:        #c8a96e;
            --dark:          #1e2d1e;
            --cream:         #f7f3ec;
            --cream-dark:    #ede7da;
            --muted:         #7a8c7a;
            --success:       #27ae60;
            --danger:        #e74c3c;
            --info:          #2980b9;
            --border:        #dde8dd;
            --shadow:        0 2px 16px rgba(30,45,30,.09);
            --radius:        10px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Be Vietnam Pro', 'Segoe UI', sans-serif; background: var(--cream); color: var(--dark); min-height: 100vh; }
        a { text-decoration: none; color: inherit; }
        #site-header {
            position: sticky;
            top: 0;
            z-index: 200;
            transition: transform .35s ease, opacity .35s ease;
        }
        #site-header.header-hidden {
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }
        .topbar {
            background: var(--primary-dark);
            color: #c8dfc8;
            padding: 0 32px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: .78rem;
        }
        .topbar-left a {
            color: var(--accent);
            font-weight: 600;
        }
        .topbar-left a:hover { 
            text-decoration: underline; 
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .topbar-right a {
            color: #c8dfc8;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: .76rem;
            font-weight: 500;
            transition: background .18s, color .18s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .topbar-right a:hover { background: rgba(255,255,255,.1); color: #fff; }
        .topbar-right .btn-register {
            background: var(--accent);
            color: #fff !important;
            font-weight: 600;
        }
        .topbar-right .btn-register:hover { background: #b8945a !important; }
        .topbar-user { color: #a8c8a8; font-size: .76rem; padding: 0 8px; }
        .topbar-user strong { color: #fff; }
        .topbar-divider { width: 1px; height: 14px; background: rgba(255,255,255,.2); margin: 0 4px; }

        .navbar {
            background: #fff;
            border-bottom: 2px solid var(--cream-dark);
            padding: 0 32px;
            height: 68px;
            display: flex;
            align-items: center;
            gap: 0;
            box-shadow: 0 2px 12px rgba(30,45,30,.07);
        }
        .navbar .brand {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
            margin-right: 32px;
            flex-shrink: 0;
        }
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2px;
            flex: 1;
            justify-content: center;
        }
        .nav-menu > .nav-item {
            position: relative;
        }
        .nav-menu > .nav-item > a {
            color: var(--dark);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: .87rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            transition: background .18s, color .18s;
            cursor: pointer;
        }
        .nav-menu > .nav-item > a:hover,
        .nav-menu > .nav-item > a.active {
            background: var(--primary-light);
            color: var(--primary-dark);
        }
        .nav-menu > .nav-item > a .fa-chevron-down {
            font-size: .65rem;
            transition: transform .2s;
        }
        .nav-menu > .nav-item:hover > a .fa-chevron-down {
            transform: rotate(180deg);
        }

        .dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(30,45,30,.15);
            min-width: 220px;
            padding: 10px 0;
            animation: dropIn .2s ease;
            z-index: 300;
        }
        .nav-item:hover .dropdown { display: block; }

        .dropdown::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 12px; height: 12px;
            background: #fff;
            border-left: 1px solid var(--border);
            border-top: 1px solid var(--border);
            transform: translateX(-50%) rotate(45deg);
        }

        .dropdown a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            font-size: .86rem;
            color: var(--dark);
            font-weight: 400;
            transition: background .15s, color .15s;
        }
        .dropdown a:hover { background: var(--primary-light); color: var(--primary-dark); }
        .dropdown a .meta { font-size: .74rem; color: var(--muted); display: flex; flex-direction: column; margin-top: 1px; }
        .dropdown-divider { height: 1px; background: var(--cream-dark); margin: 6px 12px; }

        .dropdown-wide {
            position: absolute;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(30,45,30,.15);
            animation: dropIn .2s ease;
            z-index: 300;
            min-width: 440px;
            display: none;
            grid-template-columns: 1fr 1fr;
            padding: 12px;
            gap: 4px;
        }
        .dropdown-wide::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 50%;
            width: 12px; height: 12px;
            background: #fff;
            border-left: 1px solid var(--border);
            border-top: 1px solid var(--border);
            transform: translateX(-50%) rotate(45deg);
        }
        .nav-item:hover .dropdown-wide { display: grid; }
        .dropdown-wide a { border-radius: 8px; padding: 10px 14px; }
        .dropdown-wide a:hover { background: var(--primary-light); color: var(--primary-dark); }
        .dropdown-wide a .meta { font-size: .74rem; color: var(--muted); display: flex; flex-direction: column; margin-top: 1px; }
        .dropdown-wide-divider { height: 1px; background: var(--cream-dark); margin: 6px 12px; }

        @keyframes dropIn {
            from { opacity: 0; transform: translateX(-50%) translateY(-8px); }
            to   { opacity: 1; transform: translateX(-50%) translateY(0); }
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-left: 32px;
            flex-shrink: 0;
        }
        .nav-icon-btn {
            width: 40px; height: 40px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: #fff;
            color: var(--dark);
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem;
            cursor: pointer;
            transition: background .18s, border-color .18s, color .18s;
            position: relative;
            text-decoration: none;
        }
        .nav-icon-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary-dark);
        }
        .cart-badge {
            position: absolute;
            top: -6px; right: -6px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            font-size: .62rem;
            font-weight: 700;
            width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #fff;
        }

        .search-box {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(30,45,30,.12);
            padding: 10px;
            width: 300px;
            z-index: 300;
        }
        .search-box.open { display: flex; gap: 8px; }
        .search-box input {
            flex: 1;
            border: none;
            outline: none;
            font-family: inherit;
            font-size: .9rem;
            color: var(--dark);
            background: transparent;
        }
        .search-box button {
            background: var(--primary);
            border: none;
            color: #fff;
            border-radius: 8px;
            padding: 6px 14px;
            cursor: pointer;
            font-size: .85rem;
            transition: background .18s;
        }
        .search-box button:hover { background: var(--primary-dark); }
        .search-wrapper { position: relative; }

        .container { max-width: 1240px; margin: 0 auto; padding: 28px 20px; }

        .alert { padding: 12px 18px; border-radius: var(--radius); margin-bottom: 18px; font-size: .9rem; display: flex; align-items: center; gap: 10px; border-left: 4px solid transparent; }
        .alert-success { background: #d8f0de; color: #155724; border-color: var(--success); }
        .alert-error   { background: #fde9e9; color: #721c24; border-color: var(--danger);  }
        .alert-info    { background: #daeeff; color: #0c5460; border-color: var(--info);    }

        .card { background: #fff; border-radius: 14px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border); }
        .card-header { background: var(--primary-dark); color: #fff; padding: 14px 22px; font-weight: 600; font-size: .95rem; display: flex; align-items: center; gap: 10px; }
        .card-body { padding: 22px; }

        .btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 20px; border-radius: var(--radius); border: none; cursor: pointer; font-size: .88rem; font-weight: 600; font-family: inherit; transition: .2s; }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary  { background: var(--primary);  color: #fff; }
        .btn-primary:hover  { background: var(--primary-dark); }
        .btn-success  { background: var(--success);  color: #fff; }
        .btn-success:hover  { background: #1e9950; }
        .btn-danger   { background: var(--danger);   color: #fff; }
        .btn-danger:hover   { background: #c0392b; }
        .btn-dark     { background: var(--dark);     color: #fff; }
        .btn-dark:hover     { background: #111e11; }
        .btn-outline  { background: transparent; border: 2px solid var(--primary); color: var(--primary); }
        .btn-outline:hover  { background: var(--primary); color: #fff; }
        .btn-accent   { background: var(--accent);   color: #fff; }
        .btn-accent:hover   { background: #b8945a; }
        .btn-sm       { padding: 5px 12px; font-size: .8rem; border-radius: 7px; }
        .btn-block    { width: 100%; justify-content: center; }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 7px; font-size: .88rem; color: #4a5e4a; }
        .form-control { width: 100%; padding: 10px 15px; border: 1.5px solid var(--border); border-radius: var(--radius); font-size: .93rem; font-family: inherit; transition: .2s; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(58,107,69,.12); }
        select.form-control { cursor: pointer; }

        .table { width: 100%; border-collapse: collapse; }
        .table th { background: var(--primary-dark); color: #fff; padding: 12px 16px; text-align: left; font-size: .82rem; font-weight: 600; }
        .table td { padding: 13px 16px; border-bottom: 1px solid var(--cream-dark); vertical-align: middle; font-size: .9rem; }
        .table tbody tr:hover td { background: var(--primary-light); }
        .table tbody tr:last-child td { border-bottom: none; }

        .badge { padding: 4px 11px; border-radius: 50px; font-size: .76rem; font-weight: 700; }
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-confirmed { background: #cce5ff; color: #004085; }
        .badge-shipped   { background: #d4edda; color: #155724; }
        .badge-delivered { background: #c3f0ca; color: #0a4d1c; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }

        .page-header { margin-bottom: 26px; }
        .page-header h1 { font-family: 'Playfair Display', serif; font-size: 1.65rem; color: var(--primary-dark); display: flex; align-items: center; gap: 10px; }
        .page-header p { color: var(--muted); margin-top: 5px; font-size: .9rem; }

        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); }
        .grid-4 { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); }

        .spinner { display: inline-block; width: 18px; height: 18px; border: 3px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 900px) {
            .navbar { padding: 0 16px; }
            .topbar { padding: 0 16px; }
            .brand-sub { display: none; }
            .nav-menu { display: none; }
        }
        @media (max-width: 640px) {
            .topbar-left { display: none; }
        }
    </style>
</head>
<body>

<div id="site-header">

    <div class="topbar">
        <div class="topbar-left">
            Miễn phí vận chuyển đơn từ 500.000đ &nbsp;|&nbsp;
            Hotline: <a href="tel:0983484725">0983 484 725</a> (8h – 22h)
        </div>
        <div class="topbar-right">
            <?php if (isset($_SESSION['user'])): ?>
                <span class="topbar-user">
                    Xin chào, <strong><?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?></strong>
                </span>
                <div class="topbar-divider"></div>
                <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
                    <a href="<?= APP_URL ?>/admin">
                        <i class="fa fa-shield-halved"></i> Admin
                    </a>
                    <div class="topbar-divider"></div>
                <?php endif; ?>
                <a href="<?= APP_URL ?>/orders">
                    <i class="fa fa-box"></i> Đơn hàng
                </a>
                <div class="topbar-divider"></div>
                <a href="<?= APP_URL ?>/auth/logout">
                    <i class="fa fa-right-from-bracket"></i> Đăng xuất
                </a>
            <?php else: ?>
                <a href="<?= APP_URL ?>/auth/login">
                    <i class="fa fa-right-to-bracket"></i> Đăng nhập
                </a>
                <div class="topbar-divider"></div>
                <a href="<?= APP_URL ?>/auth/register" class="btn-register">
                    <i class="fa fa-user-plus"></i> Đăng ký
                </a>
            <?php endif; ?>
        </div>
    </div>

    <nav class="navbar">

        <a href="<?= APP_URL ?>/" class="brand">
            <img src="/KTRA2_LTWEB/public/assets/images/logo.png" 
                    alt="MOW Garden" 
                    style="height:72px; width:auto; object-fit:contain;">
        </a>

        <div class="nav-menu">

            <div class="nav-item">
                <a href="<?= APP_URL ?>/products" class="<?= strpos($_SERVER['REQUEST_URI'], '/products') !== false ? 'active' : '' ?>">
                    <i class="fa fa-seedling"></i> CÂY CẢNH
                    <i class="fa fa-chevron-down"></i>
                </a>
                <div class="dropdown-wide">
                    <?php foreach ([
                        ['Cây trong nhà',    'Cây để bàn, góc phòng',     '/products?cat=indoor'],
                        ['Cây ngoài trời',   'Sân vườn, ban công',        '/products?cat=outdoor'],
                        ['Cây phong thủy',   'Tài lộc, may mắn',          '/products?cat=fengshui'],
                        ['Cây văn phòng',    'Không khí trong lành',      '/products?cat=office'],
                        ['Cây hoa',          'Trang trí, làm quà',        '/products?cat=flower'],
                        ['Cây thủy sinh',    'Bể cá, tiểu cảnh',          '/products?cat=aqua'],
                        ['Cây leo',          'Trầu bà, nho rừng',         '/products?cat=vine'],
                        ['Cây thảo dược',   'Rau thơm, cây thuốc',       '/products?cat=herb'],
                    ] as [$name, $desc, $href]): ?>
                    <a href="<?= APP_URL . $href ?>">
                        <span>
                            <?= $name ?>
                            <span class="meta"><?= $desc ?></span>
                        </span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Chậu & Đất – dropdown đơn -->
            <div class="nav-item">
                <a href="<?= APP_URL ?>/products?cat=pot">
                    <i class="fa fa-circle"></i> CHẬU &amp; ĐẤT
                    <i class="fa fa-chevron-down"></i>
                </a>
                <div class="dropdown">
                    <?php foreach ([
                        ['Chậu nhựa',       'Nhẹ, bền, giá tốt',     '/products?cat=pot-plastic'],
                        ['Chậu gốm sứ',     'Sang trọng, thẩm mỹ',   '/products?cat=pot-ceramic'],
                        ['Chậu xi măng',    'Hiện đại, chắc chắn',   '/products?cat=pot-cement'],
                        ['Đất trồng',       'Đất sạch cao cấp',      '/products?cat=soil'],
                        ['Phân bón',        'Hữu cơ & vô cơ',        '/products?cat=fertilizer'],
                    ] as [$name, $desc, $href]): ?>
                    <a href="<?= APP_URL . $href ?>">
                        <span>
                            <?= $name ?>
                            <span class="meta"><?= $desc ?></span>
                        </span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="nav-item">
                <a href="<?= APP_URL ?>/products?cat=tool">
                    <i class="fa fa-toolbox"></i> PHỤ KIỆN
                    <i class="fa fa-chevron-down"></i>
                </a>
                <div class="dropdown">
                    <?php foreach ([
                        ['Dụng cụ làm vườn', 'Kéo, xẻng, bình tưới',   '/products?cat=tool-garden'],
                        ['Đèn trồng cây',    'Đèn LED spectrum',        '/products?cat=tool-light'],
                        ['Bình tưới nước',   'Các loại bình tưới',      '/products?cat=tool-water'],
                        ['Que cắm, giá đỡ',  'Hỗ trợ cây leo',          '/products?cat=tool-support'],
                    ] as [$name, $desc, $href]): ?>
                    <a href="<?= APP_URL . $href ?>">
                        <span>
                            <?= $name ?>
                            <span class="meta"><?= $desc ?></span>
                        </span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Hướng dẫn – không dropdown -->
            <div class="nav-item">
                <a href="#">
                    <i class="fa fa-book-open"></i> HƯỚNG DẪN
                </a>
            </div>

        </div>

        <div class="nav-right">

            <!-- Tìm kiếm -->
            <div class="search-wrapper">
                <button class="nav-icon-btn" id="search-toggle" title="Tìm kiếm">
                    <i class="fa fa-magnifying-glass"></i>
                </button>
                <div class="search-box" id="search-box">
                    <input type="text" placeholder="Tìm cây cảnh, chậu...">
                    <button type="button"><i class="fa fa-magnifying-glass"></i></button>
                </div>
            </div>

            <!-- Giỏ hàng -->
            <a href="<?= APP_URL ?>/cart" class="nav-icon-btn" title="Giỏ hàng">
                <i class="fa fa-basket-shopping"></i>
                <?php $count = $cartCount ?? 0; if ($count > 0): ?>
                <span class="cart-badge" id="cart-badge"><?= $count ?></span>
                <?php else: ?>
                <span class="cart-badge" id="cart-badge" style="display:none;">0</span>
                <?php endif; ?>
            </a>

        </div>
    </nav>

</div>

<div class="container">

<?php
$flash = $flash ?? null;
if ($flash): ?>
<div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : ($flash['type'] === 'info' ? 'info' : 'success') ?>">
    <i class="fa fa-<?= $flash['type'] === 'error' ? 'times-circle' : 'check-circle' ?>"></i>
    <?= htmlspecialchars($flash['msg']) ?>
</div>
<?php endif; ?>

<script>
//Toggle search box 
document.getElementById('search-toggle').addEventListener('click', function(e) {
    e.stopPropagation();
    const box = document.getElementById('search-box');
    box.classList.toggle('open');
    if (box.classList.contains('open')) box.querySelector('input').focus();
});
document.addEventListener('click', function() {
    document.getElementById('search-box').classList.remove('open');
});
document.getElementById('search-box').addEventListener('click', e => e.stopPropagation());

//Scroll: ẩn header khi cuộn đến footer
(function() {
    const header = document.getElementById('site-header');
    const footer = document.querySelector('footer');

    function checkScroll() {
        if (!footer) return;
        const footerTop    = footer.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;
        // Ẩn header khi footer bắt đầu vào viewport
        if (footerTop <= windowHeight) {
            header.classList.add('header-hidden');
        } else {
            header.classList.remove('header-hidden');
        }
    }

    window.addEventListener('scroll', checkScroll, { passive: true });
    checkScroll();
})();
</script>