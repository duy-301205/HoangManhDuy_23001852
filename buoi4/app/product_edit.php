<?php
require_once 'model/product.php';

if (!isset($_GET['id'])) {
    die("Không tìm thấy mã sản phẩm!");
}
$id = intval($_GET['id']);
$product = getProductById($id);

if (!$product) {
    die("Sản phẩm không tồn tại!");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);

    if (empty($name)) {
        $error = "Tên sản phẩm không được rỗng.";
    } elseif ($price <= 0) {
        $error = "Giá sản phẩm phải lớn hơn 0.";
    } elseif ($quantity < 0) {
        $error = "Số lượng phải lớn hơn hoặc bằng 0.";
    } else {
        if (updateProduct($id, $name, $price, $quantity)) {
            $success = "Cập nhật sản phẩm thành công!";
            $product = getProductById($id); 
        } else {
            $error = "Có lỗi xảy ra, vui lòng thử lại.";
        }
    }
}
include 'view/header.php';
?>
<h2>Sửa sản phẩm</h2>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>

<form action="" method="POST">
    <p>
        <label>Tên sản phẩm:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
    </p>
    <p>
        <label>Giá (VNĐ):</label><br>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
    </p>
    <p>
        <label>Số lượng:</label><br>
        <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" required>
    </p>
    <button type="submit">Cập nhật sản phẩm</button>
</form>
<?php include 'view/footer.php'; ?>