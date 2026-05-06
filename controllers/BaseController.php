<?php
//  controllers/BaseController.php


abstract class BaseController
{
    protected function view(string $template, array $data = []): void
    {
        extract($data);
        $currentUser = \AuthService::getCurrentUser();
        $cartCount   = 0;
        if ($currentUser) {
            $cartCount = (new \CartRepository(
                \Database::getInstance()->getConnection()
            ))->countItems($currentUser['id']);
        }
        require BASE_PATH . '/views/layouts/header.php';
        require BASE_PATH . '/views/' . $template . '.php';
        require BASE_PATH . '/views/layouts/footer.php';
    }

    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . APP_URL . $path);
        exit;
    }

    protected function setFlash(string $type, string $msg): void
    {
        $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
    }

    protected function getFlash(): ?array
    {
        $f = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $f;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    protected function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    protected function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
