<?php
require __DIR__ . '/common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    sendJson(405, ['success' => false, 'message' => 'API upload chỉ hỗ trợ POST.']);
}

$file = $_FILES['image'] ?? null;
$error = $file['error'] ?? UPLOAD_ERR_NO_FILE;
if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE
    || (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 2 * 1024 * 1024 && empty($_POST) && empty($_FILES)) {
    sendJson(413, ['success' => false, 'message' => 'Ảnh phải có dung lượng tối đa 2 MB.']);
}
if ($error !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
    sendJson(400, ['success' => false, 'message' => 'Vui lòng gửi một tệp ảnh hợp lệ.']);
}

$id = parseStrictInt($_POST['product_id'] ?? null);
if ($id === null || $id < 1) {
    sendJson(400, ['success' => false, 'message' => 'Mã sản phẩm không hợp lệ.']);
}

$size = filesize($file['tmp_name']);
if ($size === false || $size === 0 || $size > 2 * 1024 * 1024) {
    sendJson(413, ['success' => false, 'message' => 'Ảnh phải có dung lượng tối đa 2 MB.']);
}

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$expectedMime = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'][$extension] ?? null;
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
$imageInfo = @getimagesize($file['tmp_name']);
if ($expectedMime === null || $mime !== $expectedMime || ($imageInfo['mime'] ?? null) !== $expectedMime) {
    sendJson(415, ['success' => false, 'message' => 'Chỉ chấp nhận ảnh JPG, JPEG hoặc PNG hợp lệ.']);
}

$root = dirname(__DIR__);
$directory = $root . '/uploads';
$path = 'uploads/' . bin2hex(random_bytes(16)) . '.' . $extension;
$moved = false;

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('SELECT image FROM products WHERE id = :id FOR UPDATE');
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch();
    if (!$product) {
        $pdo->rollBack();
        sendJson(404, ['success' => false, 'message' => 'Không tìm thấy sản phẩm.']);
    }

    if ((!is_dir($directory) && !@mkdir($directory, 0755) && !is_dir($directory))
        || !@move_uploaded_file($file['tmp_name'], $root . '/' . $path)) {
        throw new RuntimeException('Không thể lưu tệp ảnh.');
    }
    $moved = true;

    $stmt = $pdo->prepare('UPDATE products SET image = :image WHERE id = :id');
    $stmt->execute([':image' => $path, ':id' => $id]);
    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    if ($moved) {
        @unlink($root . '/' . $path);
    }
    sendJson(500, ['success' => false, 'message' => 'Không thể lưu ảnh. Vui lòng thử lại.']);
}

if (preg_match('~^uploads/[a-f0-9]{32}\.(?:jpe?g|png)$~D', $product['image'] ?? '')) {
    @unlink($root . '/' . $product['image']);
}

sendJson(200, [
    'success' => true,
    'message' => 'Tải ảnh lên thành công.',
    'data' => ['id' => $id, 'image' => $path]
]);
