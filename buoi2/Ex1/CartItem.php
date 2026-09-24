<?php

class CartItem {
    private $name;
    private $price;
    private $quantity;

    public function __construct($name, $price, $quantity) {
        if (empty(trim($name))) {
            throw new Exception("Tên sản phẩm không được để trống.");
        }

        if (!is_numeric($price) || $price <= 0) {
            throw new Exception("Đơn giá sản phẩm phải lớn hơn 0.");
        }

        if (!is_numeric($quantity) || $quantity <= 0) {
            throw new Exception("Số lượng sản phẩm phải lớn hơn 0.");
        }

        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getTotal() {
        return $this->price * $this->quantity;
    }
}

?>