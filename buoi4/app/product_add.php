<?php
require_once 'model/product.php';
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
        if (addProduct($name, $price, $quantity)) {
            $success = "Thêm sản phẩm thành công!";
        } else {
            $error = "Có lỗi xảy ra, vui lòng thử lại.";
        }
    }
}
include 'view/header.php';
?>
<h2>Thêm sản phẩm mới</h2>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>

<form action="" method="POST">
    <p>
        <label>Tên sản phẩm:</label><br>
        <input type="text" name="name" required>
    </p>
    <p>
        <label>Giá (VNĐ):</label><br>
        <input type="number" step="0.01" name="price" required>
    </p>
    <p>
        <label>Số lượng:</label><br>
        <input type="number" name="quantity" required>
    </p>
    <button type="submit">Thêm sản phẩm</button>
</form>
<?php include 'view/footer.php'; ?>