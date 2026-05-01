<?php
//  services/AuthService.php

require_once BASE_PATH . '/repositories/UserRepository.php';
require_once BASE_PATH . '/models/User.php';

class AuthService
{
    public function __construct(private UserRepository $userRepo) {}

    public function login(string $email, string $password): array
    {
        $user = $this->userRepo->findByEmail($email);
        if (!$user || !$user->verifyPassword($password)) {
            return ['success' => false, 'message' => 'Email hoặc mật khẩu không đúng.'];
        }
        // Lưu session
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user->getId();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['user_email']= $user->getEmail();
        $_SESSION['user_role'] = $user->getRole();
        return ['success' => true, 'user' => $user->toArray()];
    }

    public function register(string $name, string $email, string $password): array
    {
        if (strlen($name) < 2) {
            return ['success' => false, 'message' => 'Họ tên phải có ít nhất 2 ký tự.'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email không hợp lệ.'];
        }
        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự.'];
        }
        if ($this->userRepo->emailExists($email)) {
            return ['success' => false, 'message' => 'Email đã được sử dụng.'];
        }
        $id = $this->userRepo->create($name, $email, User::hashPassword($password));
        return ['success' => true, 'message' => 'Đăng ký thành công! Vui lòng đăng nhập.', 'id' => $id];
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function getCurrentUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function getCurrentUser(): ?array
    {
        if (!self::isLoggedIn()) return null;
        return [
            'id'    => $_SESSION['user_id'],
            'name'  => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role'  => $_SESSION['user_role'],
        ];
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
    }
}
