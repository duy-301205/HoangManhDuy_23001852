<?php
header('Content-Type: application/json; charset=utf-8');

$host = '127.0.0.1';
$dbname = 'shop_db';
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


function validateProductInput($input): array
{
    if (!is_array($input)) {
        return [
            'valid' => false,
            'message' => 'Dữ liệu gửi lên phải là JSON hợp lệ.'
        ];
    }

    if (!array_key_exists('name', $input) || !is_string($input['name'])) {
        return [
            'valid' => false,
            'message' => 'Tên sản phẩm không hợp lệ.'
        ];
    }

    $name = trim($input['name']);
    $length = function_exists('mb_strlen') ? mb_strlen($name, 'UTF-8') : strlen($name);
    if ($length < 1 || $length > 100) {
        return [
            'valid' => false,
            'message' => 'Tên sản phẩm sau khi bỏ khoảng trắng hai đầu phải có từ 1 đến 100 ký tự.'
        ];
    }

    if (!array_key_exists('price', $input)) {
        return [
            'valid' => false,
            'message' => 'Vui lòng nhập đơn giá.'
        ];
    }
    $price = parseStrictInt($input['price']);
    if ($price === null || $price <= 0) {
        return [
            'valid' => false,
            'message' => 'Đơn giá phải là số nguyên lớn hơn 0.'
        ];
    }

    if (!array_key_exists('stock', $input)) {
        return [
            'valid' => false,
            'message' => 'Vui lòng nhập số lượng tồn.'
        ];
    }
    $stock = parseStrictInt($input['stock']);
    if ($stock === null || $stock < 0) {
        return [
            'valid' => false,
            'message' => 'Số lượng tồn phải là số nguyên không âm (>= 0).'
        ];
    }

    return [
        'valid' => true,
        'data' => [
            'name' => $name,
            'price' => $price,
            'stock' => $stock,
        ]
    ];
}

function getIdFromQuery(): int
{
    $id = parseStrictInt($_GET['id'] ?? null);
    if ($id === null || $id <= 0) {
        sendJson(400, [
            'success' => false,
            'message' => 'Tham số id trên URL không hợp lệ.'
        ]);
    }
    return $id;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->query('SELECT id, name, price, stock FROM products ORDER BY id ASC');
        $rows = $stmt->fetchAll();

        $products = array_map(static function (array $row): array {
            return [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'price' => (int) $row['price'],
                'stock' => (int) $row['stock'],
            ];
        }, $rows);

        sendJson(200, [
            'success' => true,
            'data' => $products
        ]);
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $validation = validateProductInput($input);

        if (!$validation['valid']) {
            sendJson(400, [
                'success' => false,
                'message' => $validation['message']
            ]);
        }

        $data = $validation['data'];
        $stmt = $pdo->prepare(
            'INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)'
        );
        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
        ]);

        sendJson(201, [
            'success' => true,
            'message' => 'Thêm sản phẩm thành công.',
            'data' => [
                'id' => (int) $pdo->lastInsertId(),
                'name' => $data['name'],
                'price' => $data['price'],
                'stock' => $data['stock'],
            ]
        ]);
        break;

    case 'PUT':
        $id = getIdFromQuery();

        $input = json_decode(file_get_contents('php://input'), true);
        $validation = validateProductInput($input);

        if (!$validation['valid']) {
            sendJson(400, [
                'success' => false,
                'message' => $validation['message']
            ]);
        }

        // Kiểm tra sản phẩm có tồn tại trong MySQL không
        $checkStmt = $pdo->prepare('SELECT id FROM products WHERE id = :id');
        $checkStmt->execute([':id' => $id]);
        if (!$checkStmt->fetch()) {
            sendJson(404, [
                'success' => false,
                'message' => "Không tìm thấy sản phẩm có id = {$id}."
            ]);
        }

        $data = $validation['data'];
        $stmt = $pdo->prepare(
            'UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id'
        );
        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
            ':id' => $id,
        ]);

        sendJson(200, [
            'success' => true,
            'message' => 'Cập nhật sản phẩm thành công.'
        ]);
        break;

    case 'DELETE':
        $id = getIdFromQuery();

        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
            sendJson(404, [
                'success' => false,
                'message' => "Không tìm thấy sản phẩm có id = {$id} để xóa."
            ]);
        }

        sendJson(200, [
            'success' => true,
            'message' => 'Xóa sản phẩm thành công.'
        ]);
        break;

    default:
        sendJson(405, [
            'success' => false,
            'message' => 'Phương thức HTTP không được hỗ trợ.'
        ]);
}