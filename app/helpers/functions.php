<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiah(float $amount): string
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    ob_start();
    require __DIR__ . '/../views/' . $view . '.php';
    $content = (string) ob_get_clean();
    require __DIR__ . '/../views/layouts/main.php';
}

function redirect(string $path = ''): never
{
    header('Location: ' . url($path));
    exit;
}

function url(string $path = ''): string
{
    return APP_URL . ($path === '' ? '/' : '/' . ltrim($path, '/'));
}

function csrf_token(): string
{
    $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(419);
        exit('Sesi formulir tidak valid. Silakan muat ulang halaman.');
    }
}

function require_auth(): void
{
    if (empty($_SESSION['user'])) redirect('login');
}

function flash(string $message = null): ?string
{
    if ($message !== null) { $_SESSION['flash'] = $message; return null; }
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $message;
}
