<?php

require_once "data/students.php";

$totalScore = 0;

foreach ($students as $student) {
    echo "Họ tên: " . $student["name"] . "<br>";
    echo "Tuổi: " . $student["age"] . "<br>";
    echo "Điểm: " . $student["score"] . "<br>";
    echo "<br>";

    $totalScore += $student["score"];
}

$averageScore = $totalScore / count($students);

echo "<b>Điểm trung bình của lớp: " . $averageScore . "</b>";
?>