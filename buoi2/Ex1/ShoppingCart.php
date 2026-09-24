<?php

require_once 'CartItem.php';
class ShoppingCart {

    private $items = [];

    public function addItem($item) {
        if (!($item instanceof CartItem)) {
            throw new Exception("Không thể thêm: Sản phẩm không hợp lệ.<br>");
        }

        if ($item->getPrice() <= 0) {
            throw new Exception("Không thể thêm: Giá sản phẩm phải lớn hơn 0.<br>");
        }

        if ($item->getQuantity() <= 0) {
            throw new Exception("Không thể thêm: Số lượng sản phẩm phải lớn hơn 0.<br>");
        }

        $this->items[] = $item;

        echo "Đã thêm sản phẩm: " . $item->getName() . "<br>";
    }

    public function removeItem($name) {

        if (empty($this->items)) {
            throw new Exception("Không thể xóa: Giỏ hàng đang trống.<br>");
        }

        if (empty(trim($name))) {
            throw new Exception("Không thể xóa: Tên sản phẩm không được để trống.<br>");
        }

        foreach ($this->items as $index => $item) {
            
            if ($item->getName() === $name) {
                
                unset($this->items[$index]);

                $this->items = array_values($this->items);

                echo "Đã xóa sản phẩm: " . $name . "<br>";

                return;
            }
        }

        throw new Exception("Không thể xóa: Không tìm thấy sản phẩm " . $name . ".");
    }

    public function calculateTotal() {
        
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }

    public function displayCart() {
        if (empty($this->items)) {
            echo "Giỏ hàng hiện đang trống.<br>";
            return;
        }

        echo "<table border='1' cellpadding='8' cellspacing='0'>";

        echo "
            <tr>
                <th>Tên sản phẩm</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        ";

        foreach ($this->items as $item) {
            echo "<tr>";
            echo "<td>" . $item->getName() . "</td>";
            echo "<td>" . number_format($item->getPrice()) . " VNĐ</td>";
            echo "<td>" . $item->getQuantity() . "</td>";
            echo "<td>" . number_format($item->getTotal()) . " VNĐ</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
}
?>