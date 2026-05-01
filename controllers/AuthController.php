<?php
//  controllers/AuthController.php

require_once BASE_PATH . '/controllers/BaseController.php';
require_once BASE_PATH . '/services/AuthService.php';
require_once BASE_PATH . '/repositories/UserRepository.php';

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct()
    {
        $db = \Database::getInstance()->getConnection();
        $this->authService = new AuthService(new UserRepository($db));
    }

    //GET /auth/login 
    public function loginForm(): void
    {
        if (AuthService::isLoggedIn()) {
            $this->redirect('/');
        }
        $this->view('auth/login', ['flash' => $this->getFlash()]);
    }

    //POST /auth/login 
    public function login(): void
    {
        $email    = $this->sanitize($this->post('email', ''));
        $password = $this->post('password', '');

        $result = $this->authService->login($email, $password);

        if ($result['success']) {
            $redirect = $_SESSION['redirect_after_login'] ?? (APP_URL . '/');
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirect);
            exit;
        }

        $this->setFlash('error', $result['message']);
        $this->redirect('/auth/login');
    }

    //GET /auth/register
    public function registerForm(): void
    {
        if (AuthService::isLoggedIn()) {
            $this->redirect('/');
        }
        $this->view('auth/register', ['flash' => $this->getFlash()]);
    }

    //POST /auth/register 
    public function register(): void
    {
        $name     = $this->sanitize($this->post('name', ''));
        $email    = $this->sanitize($this->post('email', ''));
        $password = $this->post('password', '');
        $confirm  = $this->post('password_confirm', '');

        if ($password !== $confirm) {
            $this->setFlash('error', 'Mật khẩu xác nhận không khớp.');
            $this->redirect('/auth/register');
        }

        $result = $this->authService->register($name, $email, $password);
        if ($result['success']) {
            $this->setFlash('success', $result['message']);
            $this->redirect('/auth/login');
        } else {
            $this->setFlash('error', $result['message']);
            $this->redirect('/auth/register');
        }
    }

    //GET /auth/logout 
    public function logout(): void
    {
        $this->authService->logout();
        $this->redirect('/');
    }
}
