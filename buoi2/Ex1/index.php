<?php

require_once 'ShoppingCart.php';

try {
    $cart = new ShoppingCart();

    $item1 = new CartItem("Laptop", 15000000, 1);
    $item2 = new CartItem("Chuột không dây", 350000, 2);
    $item3 = new CartItem("Bàn phím cơ", 1200000, 1);
    $item4 = new CartItem("Tai nghe", 800000, 2);

    $cart->addItem($item1);
    $cart->addItem($item2);
    $cart->addItem($item3);
    $cart->addItem($item4);

    echo "<h2>GIỎ HÀNG BAN ĐẦU</h2>";
    $cart->displayCart();
    
    echo "<h3>Tổng tiền: "
        . number_format($cart->calculateTotal())
        . " VNĐ</h3>";

    echo "<h2>XÓA SẢN PHẨM</h2>";
    $cart->removeItem("Bàn phím cơ");

    echo "<h2>GIỎ HÀNG SAU KHI XÓA</h2>";
    $cart->displayCart();

    echo "<h3>Tổng tiền sau khi xóa: "
        . number_format($cart->calculateTotal())
        . " VNĐ</h3>";

}  catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>