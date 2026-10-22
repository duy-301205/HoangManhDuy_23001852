<?php
$prices = [150, 300, 450, 200, 500, 750];

function calculateTotal($arrPrices) {
    if (!is_array($arrPrices) || count($arrPrices) === 0) {
        return "Lỗi: Mảng sản phẩm rỗng!";
    }

    $total = 0;
    foreach ($arrPrices as $price) {
        if (!is_numeric($price) || $price < 0) {
            return "Lỗi: Giá sản phẩm không hợp lệ!";
        }
        $total += $price;
    }
    return $total;
}

function findMaxPrice($arrPrices) {
    if (!is_array($arrPrices) || count($arrPrices) === 0) {
        return "Lỗi: Mảng sản phẩm rỗng!";
    }

    $maxPrice = $arrPrices[0];
    foreach ($arrPrices as $price) {
        if (!is_numeric($price) || $price < 0) {
            return "Lỗi: Giá sản phẩm không hợp lệ!";
        }
        if ($price > $maxPrice) {
            $maxPrice = $price;
        }
    }
    return $maxPrice;
}

if (!is_array($prices)) {
    echo "Lỗi: Dữ liệu truyền vào không phải là mảng.<br>";
} else {
    $totalValue = calculateTotal($prices);
    $highestPrice = findMaxPrice($prices);

    echo "Mảng giá sản phẩm: " . implode(", ", $prices) . "<br>";
    echo "Tổng giá trị của tất cả sản phẩm: " . $totalValue . "<br>";
    echo "Giá sản phẩm cao nhất: " . $highestPrice . "<br>";
}
?>