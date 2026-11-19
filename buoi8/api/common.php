<?php
header('Content-Type: application/json; charset=utf-8');

$host = '127.0.0.1';
$dbname = getenv('SHOP_DB_NAME') ?: 'shop_db';
$username = 'root';
$password = '';

function sendJson(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    sendJson(500, [
        'success' => false,
        'message' => 'Không thể kết nối cơ sở dữ liệu MySQL.'
    ]);
}


function parseStrictInt($value): ?int
{
    if (is_int($value)) {
        return $value;
    }
    if (is_string($value) && trim($value) !== '' && filter_var($value, FILTER_VALIDATE_INT) !== false) {
        return (int) $value;
    }
    return null;
}


