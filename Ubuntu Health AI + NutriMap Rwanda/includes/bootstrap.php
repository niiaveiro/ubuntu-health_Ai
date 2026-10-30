<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    $secureCookie = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $secureCookie,
        'samesite' => 'Lax',
    ]);
    session_start();
}
require_once __DIR__ . '/../config/database.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        if (
            !is_string(DB_HOST) || trim(DB_HOST) === '' ||
            !is_string(DB_NAME) || trim(DB_NAME) === '' ||
            !is_string(DB_USER) || trim(DB_USER) === '' ||
            !is_string(DB_PASS)
        ) {
            error_log('Ubuntu Health AI database configuration is incomplete.');
            throw new RuntimeException('Database configuration is incomplete.');
        }
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $ex) {
            error_log('Ubuntu Health AI database connection failed: ' . $ex->getMessage());
            throw new RuntimeException('Database connection failed.', 0, $ex);
        }
    }
    return $pdo;
}
function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function csrf_token(): string {
    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        exit('Request could not be verified. Please go back and try again.');
    }
}
function post_text(string $key, int $max = 200): ?string {
    $value = $_POST[$key] ?? null;
    if (!is_string($value) || !mb_check_encoding($value, 'UTF-8')) {
        return null;
    }
    return clean_text($value, $max);
}
function clean_text(string $value, int $max = 200): string {
    $value = trim($value);
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
    return mb_substr($value, 0, $max);
}
