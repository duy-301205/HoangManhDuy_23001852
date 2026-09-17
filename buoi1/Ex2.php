<?php

require_once "data/students.php";

function calculateAverageScore($students) {
    $totalScore = 0;

    foreach ($students as $student) {
        $totalScore += $student["score"];
    }

    return $totalScore / count($students);
}

function getRank($score) {
    if ($score >= 8) {
        return "Giỏi";
    } elseif ($score >= 6.5) {
        return "Khá";
    } elseif ($score >= 5) {
        return "Trung bình";
    } else {
        return "Yếu";
    }
}

function displayStudent($student) {
    echo "Họ tên: " . $student["name"] . "<br>";
    echo "Tuổi: " . $student["age"] . "<br>";
    echo "Điểm: " . $student["score"] . "<br>";
    echo "Xếp loại: " . getRank($student["score"]) . "<br>";
    echo "<br>";
}

echo "<h2>Danh sách sinh viên</h2>";

foreach ($students as $student) {
    displayStudent($student);
}

$averageScore = calculateAverageScore($students);

echo "<b>Điểm trung bình của lớp: " . $averageScore . "</b>"

?>