# 🛒 MOW Shop – Bài Tập Lập Trình Hướng Đối Tượng

## Giỏ Hàng & Đơn Đặt Hàng với Strategy Pattern

---

## 📐 Kiến trúc & Design Patterns sử dụng

| Pattern | Áp dụng ở |
|---|---|
| **Strategy Pattern** | `PricingStrategyInterface` + 3 chiến lược giá (Standard, Premium, FlashSale) |
| **Singleton Pattern** | `Database` – kết nối DB duy nhất |
| **Repository Pattern** | `ProductRepository`, `CartRepository`, `OrderRepository`, `UserRepository` |
| **MVC** | `controllers/`, `models/`, `views/` |
| **Dependency Injection** | Tất cả services nhận repo qua constructor |

---

## 📂 Cấu trúc thư mục

```
shop/
├── config/
│   ├── config.php           # Cấu hình app, DB, email
│   └── database.php         # Database Singleton (PDO)
├── interfaces/
│   ├── PricingStrategyInterface.php  # Interface Strategy Pattern
│   └── RepositoryInterface.php
├── strategies/              # Concrete Strategy classes
│   ├── StandardPricingStrategy.php  # VAT 10%, ship 35k (miễn ≥500k)
│   ├── PremiumPricingStrategy.php   # VIP: VAT 8%, miễn ship hoàn toàn
│   └── FlashSalePricingStrategy.php # Flash Sale: ship cố định 15k
├── models/
│   ├── Product.php          # Entity sản phẩm
│   ├── CartItem.php         # Item trong giỏ hàng
│   ├── ShoppingCart.php     # Domain object giỏ hàng
│   ├── Order.php            # Entity đơn hàng
│   └── User.php             # Entity người dùng
├── repositories/            # Data Access Layer
│   ├── ProductRepository.php
│   ├── CartRepository.php   # Persistent cart (lưu DB)
│   ├── OrderRepository.php
│   └── UserRepository.php
├── services/                # Business Logic Layer
│   ├── AuthService.php
│   ├── CartService.php      # Kết hợp domain + persistence
│   ├── OrderService.php     # Đặt hàng, tính tiền, gửi email
│   └── EmailService.php     # Gửi email (log ra file dev)
├── controllers/             # MVC Controllers
│   ├── BaseController.php
│   ├── AuthController.php
│   ├── ProductController.php
│   ├── CartController.php
│   └── OrderController.php
├── views/                   # MVC Views (PHP templates)
│   ├── layouts/
│   │   ├── header.php
│   │   └── footer.php
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── products/
│   │   ├── index.php
│   │   └── detail.php
│   ├── cart/
│   │   └── index.php
│   └── orders/
│       ├── checkout.php
│       ├── detail.php
│       ├── list.php
│       └── admin.php
├── database/
│   └── schema.sql           # Schema + seed data (15 sản phẩm, 3 tài khoản)
├── logs/                    # Log email tự động tạo
├── public/
│   ├── index.php            # Front Controller (Entry Point)
│   └── .htaccess
└── .htaccess
```

---

## ⚙️ Cài đặt

### Yêu cầu
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Apache với `mod_rewrite` bật

### Bước 1: Copy thư mục vào web server

**XAMPP (Windows):**
```
Sao chép thư mục shop/ vào: C:\xampp\htdocs\shop\
```

**WAMP:**
```
Sao chép vào: C:\wamp64\www\shop\
```

**Linux/Mac (LAMP):**
```bash
cp -r shop/ /var/www/html/shop/
```

---

### Bước 2: Import database

1. Mở **phpMyAdmin** tại `http://localhost/phpmyadmin`
2. Click **"Import"**
3. Chọn file: `shop/database/schema.sql`
4. Click **"Go"**

Hoặc dùng command line:
```bash
mysql -u root -p < shop/database/schema.sql
```

---

### Bước 3: Cấu hình kết nối DB

Mở file `shop/config/config.php`, chỉnh:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'shop_db');
define('DB_USER', 'root');   // username MySQL của bạn
define('DB_PASS', '');       // password MySQL của bạn
```

---

### Bước 4: Bật mod_rewrite (Apache)

**XAMPP:** Mở `httpd.conf`, tìm và bỏ dấu `#` trước:
```
LoadModule rewrite_module modules/mod_rewrite.so
```

Tìm `AllowOverride None` → đổi thành `AllowOverride All`

---

### Bước 5: Truy cập

```
http://localhost/shop/public/
```

---

## 🔑 Tài khoản demo

| Vai trò | Email | Mật khẩu |
|---|---|---|
| Admin | admin@shop.com | 123456 |
| User | user@shop.com | 123456 |
| User | binh@shop.com | 123456 |

---

## ✨ Tính năng đầy đủ

### Yêu cầu cơ bản ✅
- ✅ Giỏ hàng chỉ dành cho người dùng đã đăng nhập
- ✅ Thêm sản phẩm vào giỏ (AJAX + fallback)
- ✅ Xóa sản phẩm khỏi giỏ hàng (AJAX)
- ✅ Cập nhật số lượng (AJAX realtime)
- ✅ Tạo đơn hàng với tính tổng tiền, VAT, phí vận chuyển
- ✅ 3 chiến lược giá (Strategy Pattern)

### Yêu cầu nâng cao ✅
- ✅ **Persistent Cart** – Giỏ hàng lưu vào DB, không mất khi tắt trình duyệt
- ✅ **Email tự động** – Ghi log email vào `logs/emails.log` sau khi đặt hàng
- ✅ **Cập nhật tồn kho** – Stock tự động giảm khi đặt hàng thành công
- ✅ **Admin panel** – Quản lý & cập nhật trạng thái đơn hàng

### OOP ✅
- ✅ `class Product`, `CartItem`, `ShoppingCart`, `Order`, `User`
- ✅ `interface PricingStrategyInterface`, `RepositoryInterface`
- ✅ `abstract class` qua `BaseController`
- ✅ Strategy Pattern với 3 concrete classes
- ✅ Singleton Pattern (Database)
- ✅ Repository Pattern (Data Access)
- ✅ Dependency Injection (Service constructors)
- ✅ MVC Architecture

---

## 📧 Kiểm tra email log

Sau khi đặt hàng thành công, xem file:
```
shop/logs/emails.log
```

---

## 🛒 Strategy Pattern – Các chiến lược giá

| Chiến lược | VAT | Phí vận chuyển |
|---|---|---|
| **Standard** (Tiêu chuẩn) | 10% | 35.000đ (miễn nếu ≥ 500.000đ) |
| **Premium** (VIP Member) | 8% | Miễn phí hoàn toàn |
| **Flash Sale** | 10% | Cố định 15.000đ |
