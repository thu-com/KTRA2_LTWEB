<?php
session_start();
define('ROOT_PATH', dirname(__DIR__));
define('APP_URL',   '/KTRA2_LTWEB/public');

require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/core/database.php';
require_once ROOT_PATH . '/models/User.php';
require_once ROOT_PATH . '/controllers/AuthController.php';

$pageTitle = 'MOW Garden';
$cartCount = 0;

$uri        = trim(str_replace('/KTRA2_LTWEB/public', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/');
$segments   = $uri ? explode('/', $uri) : [];
$controller = $segments[0] ?? 'home';
$action     = $segments[1] ?? 'index';
$param      = $segments[2] ?? null;

switch ($controller) {

    // Trang chủ – test header/footer
    case '':
    case 'home':
        include ROOT_PATH . '/views/layouts/header.php';
        include ROOT_PATH . '/views/layouts/footer.php';
        break;

    // Auth – để Hạnh tích hợp sau
    case 'auth':
        $auth = new AuthController();
        switch ($action) {
            case 'login':      $auth->login();      break;
            case 'doLogin':    $auth->doLogin();    break;
            case 'register':   $auth->register();   break;
            case 'doRegister': $auth->doRegister(); break;
            case 'logout':     $auth->logout();     break;
            default:           $auth->login();
        }
        break;

    // Admin – phần của Huyền
    case 'admin':
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
        $pageTitle = 'Quản trị – MOW Garden';
        include ROOT_PATH . '/views/layouts/header.php';
        include ROOT_PATH . '/views/admin/index.php';
        include ROOT_PATH . '/views/layouts/footer.php';
        break;

    default:
        header('Location: ' . APP_URL . '/');
        exit;
}