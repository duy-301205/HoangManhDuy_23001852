<?php
require_once 'model/product.php';
include 'view/header.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $product = getProductById($id);

    if ($product) {
        if (deleteProduct($id)) {
            echo "<h2>Thành công</h2>";
            echo "<p style='color:green;'>Đã xóa sản phẩm <b>" . htmlspecialchars($product['name']) . "</b> thành công!</p>";
        } else {
            echo "<h2>Lỗi</h2>";
            echo "<p style='color:red;'>Lỗi khi thực hiện xóa sản phẩm!</p>";
        }
    } else {
        echo "<h2>Cảnh báo</h2>";
        echo "<p style='color:red;'>Sản phẩm không tồn tại hoặc đã bị xóa trước đó!</p>";
    }
} else {
    echo "<h2>Cảnh báo</h2>";
    echo "<p style='color:red;'>Không nhận được thông tin sản phẩm cần xóa!</p>";
}
?>
<br>
<a href="product_list.php"><button>Quay lại danh sách</button></a>
<?php include 'view/footer.php'; ?>