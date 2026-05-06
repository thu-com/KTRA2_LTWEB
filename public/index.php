<?php

//  public/index.php  –  Front Controller (Entry Point)
// Thêm dòng này vào
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}
if (!defined('BASE_PATH')) define('BASE_PATH', dirname(__DIR__));

// Autoload 
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/database.php';

// Models
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/Product.php';
require_once BASE_PATH . '/models/CartItem.php';
require_once BASE_PATH . '/models/ShoppingCart.php';
require_once BASE_PATH . '/models/Order.php';

// Interfaces
require_once BASE_PATH . '/interfaces/PricingStrategyInterface.php';
require_once BASE_PATH . '/interfaces/RepositoryInterface.php';

// Strategies
require_once BASE_PATH . '/strategies/StandardPricingStrategy.php';
require_once BASE_PATH . '/strategies/PremiumPricingStrategy.php';
require_once BASE_PATH . '/strategies/FlashSalePricingStrategy.php';

// Repositories
require_once BASE_PATH . '/repositories/UserRepository.php';
require_once BASE_PATH . '/repositories/ProductRepository.php';
require_once BASE_PATH . '/repositories/CartRepository.php';
require_once BASE_PATH . '/repositories/OrderRepository.php';

// Services
require_once BASE_PATH . '/services/AuthService.php';
require_once BASE_PATH . '/services/EmailService.php';
require_once BASE_PATH . '/services/CartService.php';
require_once BASE_PATH . '/services/OrderService.php';

// Controllers
require_once BASE_PATH . '/controllers/BaseController.php';
require_once BASE_PATH . '/controllers/AuthController.php';
require_once BASE_PATH . '/controllers/ProductController.php';
require_once BASE_PATH . '/controllers/CartController.php';
require_once BASE_PATH . '/controllers/OrderController.php';

//Session
ini_set('session.name', SESSION_NAME);
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
session_start();

//Router 
$requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir     = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$path          = '/' . trim(str_replace($scriptDir, '', $requestUri), '/');
$method        = $_SERVER['REQUEST_METHOD'];

// Route matching
try {
    //  Auth
    if ($path === '/auth/login'  && $method === 'GET')  { (new AuthController())->loginForm(); }
    elseif ($path === '/auth/login'  && $method === 'POST') { (new AuthController())->login(); }
    elseif ($path === '/auth/register' && $method === 'GET')  { (new AuthController())->registerForm(); }
    elseif ($path === '/auth/register' && $method === 'POST') { (new AuthController())->register(); }
    elseif ($path === '/auth/logout') { (new AuthController())->logout(); }

    // Products 
    elseif ($path === '/' || $path === '/products') { (new ProductController())->index(); }
    elseif (preg_match('#^/products/(\d+)$#', $path, $m)) {
        (new ProductController())->detail((int)$m[1]);
    }

    //Cart 
    elseif ($path === '/cart' && $method === 'GET')  { (new CartController())->index(); }
    elseif ($path === '/cart/add')                    { (new CartController())->add(); }
    elseif ($path === '/cart/update')                 { (new CartController())->update(); }
    elseif ($path === '/cart/remove')                 { (new CartController())->remove(); }
    elseif ($path === '/cart/count')                  { (new CartController())->count(); }
    elseif ($path === '/cart/clear') {
        // Clear cart shortcut
        AuthService::requireLogin();
        $db = Database::getInstance()->getConnection();
        (new CartRepository($db))->clearCart((int)AuthService::getCurrentUserId());
        header('Location: ' . APP_URL . '/cart');
        exit;
    }

    //Checkout / Orders 
    elseif ($path === '/checkout' && $method === 'GET')  { (new OrderController())->checkout(); }
    elseif ($path === '/checkout' && $method === 'POST') { (new OrderController())->placeOrder(); }
    elseif ($path === '/orders')                          { (new OrderController())->myOrders(); }
    elseif (preg_match('#^/orders/(\d+)$#', $path, $m))  { (new OrderController())->detail((int)$m[1]); }

    // Admin 
    elseif ($path === '/admin/orders')                     { (new OrderController())->adminOrders(); }
    elseif ($path === '/admin/orders/status' && $method === 'POST') { (new OrderController())->updateStatus(); }

    //404
    else {
        http_response_code(404);
        echo '<div style="text-align:center;padding:80px;font-family:sans-serif">
                <h1 style="font-size:4rem;color:#e67e22">404</h1>
                <p style="color:#888;margin-bottom:20px">Trang không tồn tại.</p>
                <a href="' . APP_URL . '/" style="color:#e67e22">← Về trang chủ</a>
              </div>';
    }
} catch (Throwable $e) {
    http_response_code(500);
    $msg = htmlspecialchars($e->getMessage());
    echo "<div style='padding:40px;font-family:sans-serif;background:#fff3f3;border:1px solid #f5c6cb;margin:20px;border-radius:8px'>
            <h2 style='color:#e74c3c'>⚠️ Lỗi hệ thống</h2>
            <p>{$msg}</p>
            <small style='color:#888'>{$e->getFile()} : {$e->getLine()}</small>
          </div>";
}
