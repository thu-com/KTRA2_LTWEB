<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'OOP Shop') ?> – OOP Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #e67e22; --primary-dark: #d35400;
            --dark: #2c3e50; --light: #f8f9fa;
            --success: #27ae60; --danger: #e74c3c; --info: #3498db;
            --border: #dee2e6; --shadow: 0 2px 12px rgba(0,0,0,.08);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; color: #333; min-height: 100vh; }
        a { text-decoration: none; color: inherit; }

        /* ── Navbar ── */
        .navbar {
            background: var(--dark); color: #fff; padding: 0 24px;
            display: flex; align-items: center; gap: 16px;
            height: 60px; position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,.3);
        }
        .navbar .brand { font-size: 1.4rem; font-weight: 700; color: var(--primary); margin-right: auto; }
        .navbar .brand span { color: #fff; }
        .navbar a { color: #ccc; padding: 6px 12px; border-radius: 6px; font-size: .9rem; transition: .2s; }
        .navbar a:hover, .navbar a.active { background: rgba(255,255,255,.1); color: #fff; }
        .cart-badge {
            background: var(--primary); color: #fff; border-radius: 50%;
            font-size: .7rem; padding: 2px 6px; margin-left: -6px; vertical-align: top;
        }
        .nav-user { color: #aaa; font-size: .85rem; }
        .btn-nav {
            background: var(--primary); color: #fff !important; padding: 6px 14px !important;
            border-radius: 6px; font-weight: 600;
        }
        .btn-nav:hover { background: var(--primary-dark) !important; }

        /* ── Container ── */
        .container { max-width: 1200px; margin: 0 auto; padding: 24px 16px; }

        /* ── Flash ── */
        .alert { padding: 12px 18px; border-radius: 8px; margin-bottom: 16px; font-size: .9rem; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info    { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

        /* ── Cards ── */
        .card { background: #fff; border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
        .card-header { background: var(--dark); color: #fff; padding: 14px 20px; font-weight: 600; }
        .card-body { padding: 20px; }

        /* ── Buttons ── */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; border-radius: 8px; border: none; cursor: pointer; font-size: .9rem; font-weight: 600; transition: .2s; }
        .btn-primary  { background: var(--primary); color: #fff; }
        .btn-primary:hover  { background: var(--primary-dark); }
        .btn-success  { background: var(--success); color: #fff; }
        .btn-success:hover  { background: #219150; }
        .btn-danger   { background: var(--danger); color: #fff; }
        .btn-danger:hover   { background: #c0392b; }
        .btn-dark     { background: var(--dark); color: #fff; }
        .btn-dark:hover     { background: #1a252f; }
        .btn-outline  { background: transparent; border: 2px solid var(--primary); color: var(--primary); }
        .btn-outline:hover  { background: var(--primary); color: #fff; }
        .btn-sm       { padding: 5px 12px; font-size: .82rem; }
        .btn-block    { width: 100%; justify-content: center; }

        /* ── Form ── */
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; font-size: .9rem; color: #555; }
        .form-control {
            width: 100%; padding: 10px 14px; border: 1.5px solid var(--border);
            border-radius: 8px; font-size: .95rem; transition: .2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(230,126,34,.15); }

        /* ── Table ── */
        .table { width: 100%; border-collapse: collapse; }
        .table th { background: var(--dark); color: #fff; padding: 12px 14px; text-align: left; font-size: .85rem; }
        .table td { padding: 12px 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        .table tr:hover td { background: #fafafa; }

        /* ── Badge ── */
        .badge { padding: 4px 10px; border-radius: 20px; font-size: .78rem; font-weight: 700; }
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-confirmed { background: #cce5ff; color: #004085; }
        .badge-shipped   { background: #d4edda; color: #155724; }
        .badge-delivered { background: #d4edda; color: #155724; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }

        /* ── Page header ── */
        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-size: 1.6rem; color: var(--dark); }
        .page-header p  { color: #888; margin-top: 4px; }

        /* ── Grid ── */
        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); }
        .grid-4 { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); }

        /* ── Spinner ── */
        .spinner { display: inline-block; width: 18px; height: 18px; border: 3px solid #fff; border-top-color: transparent; border-radius: 50%; animation: spin .6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .navbar { flex-wrap: wrap; height: auto; padding: 10px 16px; gap: 8px; }
            .navbar .brand { flex: 1 0 100%; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="<?= APP_URL ?>/" class="brand">🛒 OOP<span>Shop</span></a>
    <a href="<?= APP_URL ?>/products">Sản phẩm</a>
    <?php if (\AuthService::isLoggedIn()): ?>
        <a href="<?= APP_URL ?>/cart">
            <i class="fa fa-shopping-cart"></i> Giỏ hàng
            <span class="cart-badge" id="cart-badge"><?= $cartCount ?? 0 ?></span>
        </a>
        <a href="<?= APP_URL ?>/orders">Đơn hàng</a>
        <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
            <a href="<?= APP_URL ?>/admin/orders">Admin</a>
        <?php endif; ?>
        <span class="nav-user">Xin chào, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></strong></span>
        <a href="<?= APP_URL ?>/auth/logout" class="btn-nav"><i class="fa fa-sign-out-alt"></i> Đăng xuất</a>
    <?php else: ?>
        <a href="<?= APP_URL ?>/auth/login">Đăng nhập</a>
        <a href="<?= APP_URL ?>/auth/register" class="btn-nav">Đăng ký</a>
    <?php endif; ?>
</nav>

<div class="container">

<?php
// Hiển thị flash message
$flash = $flash ?? null;
if ($flash): ?>
<div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : ($flash['type'] === 'info' ? 'info' : 'success') ?>">
    <i class="fa fa-<?= $flash['type'] === 'error' ? 'times-circle' : 'check-circle' ?>"></i>
    <?= htmlspecialchars($flash['msg']) ?>
</div>
<?php endif; ?>
