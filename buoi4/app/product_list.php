<?php
require_once 'model/product.php';
$products = getAllProducts();
include 'view/header.php';
?>
<h2>Danh sách sản phẩm</h2>
<table border="1" cellpadding="12" cellspacing="0" width="100%">
    <thead>
        <tr bgcolor="#e0e0e0">
            <th width="10%">ID</th>
            <th width="35%">Tên sản phẩm</th>
            <th width="20%">Giá</th>
            <th width="15%">Số lượng</th>
            <th width="20%">Chức năng</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
            <td align="center"><?php echo $p['id']; ?></td>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td align="right"><?php echo number_format($p['price'], 0, ',', '.'); ?> đ</td>
            <td align="center"><?php echo $p['quantity']; ?></td>
            <td align="center">
                <a href="product_edit.php?id=<?php echo $p['id']; ?>">Sửa</a> |
                <a href="product_delete.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($products)): ?>
        <tr><td colspan="5" align="center">Không có sản phẩm nào.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php include 'view/footer.php'; ?>