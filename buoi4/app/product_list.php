<?php
require_once 'model/product.php';
$products = getAllProducts();
include 'view/header.php';
?>
<h2>Danh sách sản phẩm</h2>
<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Chức năng</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?php echo $p['id']; ?></td>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td><?php echo number_format($p['price'], 0, ',', '.'); ?> đ</td>
            <td><?php echo $p['quantity']; ?></td>
            <td>
                <a href="product_edit.php?id=<?php echo $p['id']; ?>">Sửa<a> |
                <a href="product_delete.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($products)): ?>
        <tr><td colspan="5" style="text-align:center;">Không có sản phẩm nào.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php include 'view/footer.php'; ?>