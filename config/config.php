<?php
//  config/config.php  –  Cấu hình ứng dụng

define('APP_NAME',    'Quản lý bán cây cảnh');
define('APP_URL',     'http://localhost/project/KTRA2_LTWEB/public/');
define('APP_VERSION', '1.0.0');

// ── Cấu hình Database ──────────────────────────────────────
define('DB_HOST',     'localhost');
define('DB_PORT',     '3306');
define('DB_NAME',     'quanlybancaycanh');
define('DB_USER',     'root');       // <-- Đổi theo máy
define('DB_PASS',     '');           // <-- Đổi theo máy
define('DB_CHARSET',  'utf8mb4');

// ── Cấu hình Email ─────────────────────────────────────────
define('MAIL_FROM',    'noreply@shop.com');
define('MAIL_NAME',    'OOP Shop');
define('MAIL_LOG',     false);        // Ghi log email ra file

// ── Thuế & Vận chuyển mặc định ─────────────────────────────
define('DEFAULT_VAT_RATE',       0.10);   // 10 %
define('FREE_SHIP_THRESHOLD',    500000); // ≥ 500k → miễn ship
define('DEFAULT_SHIPPING_FEE',   35000);  // 35k

// ── Session ────────────────────────────────────────────────
define('SESSION_NAME',    'caycanh_sess');
define('SESSION_LIFETIME', 7200);

// ── Path helpers ───────────────────────────────────────────
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}
if (!defined('LOG_PATH')) {
    define('LOG_PATH', BASE_PATH . '/logs');
}

// Tạo thư mục logs nếu chưa có
if (!is_dir(LOG_PATH)) {
    mkdir(LOG_PATH, 0755, true);
}
