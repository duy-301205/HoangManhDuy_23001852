<?php
$name = "Hoàng Mạnh Duy";
$score = 7.5;

if (empty($name)) {
    echo "Lỗi: Tên học sinh không được để trống.<br>";
} elseif (!is_numeric($score) || $score < 0 || $score > 10) {
    echo "Lỗi: Điểm số không hợp lệ (Điểm phải là số từ 0 đến 10).<br>";
} else {

    if ($score >= 8) {
        $rank = "Giỏi";
    } elseif ($score >= 6.5) {
        $rank = "Khá";
    } elseif ($score >= 5) {
        $rank = "Trung bình";
    } else {
        $rank = "Yếu";
    }

    echo "Học sinh: " . htmlspecialchars($name) . "<br>";
    echo "Điểm số: " . $score . "<br>";
    echo "Xếp loại: " . $rank . "<br>";
}
?>