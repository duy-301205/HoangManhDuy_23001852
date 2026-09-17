<?php

require_once "data/students.php";

function findBestStudent($students) {
    
    $bestStudent = $students[0];

    foreach ($students as $student) {
        if ($student["score"] > $bestStudent["score"]) {
            $bestStudent = $student;
        }
    }

    return $bestStudent;
}

function findWorstStudent($students) {
    $worstStudent = $students[0];

    foreach ($students as $student) {
        if ($student["score"] < $worstStudent["score"]) {
            $worstStudent = $student;
        }
    }

    return $worstStudent;
}

function countPassedStudents($students) {
    $count = 0;

    foreach ($students as $student) {
        if ($student["score"] >= 5) {
            $count++;
        }
    }

    return $count;
}

function findStudentByName($students, $name) {
    foreach ($students as $student) {
        if ($student["name"] == $name) {
            return $student;
        }
    }

    return null;
}

function displayStudent($student) {
    echo "Họ tên: " . $student["name"] . "<br>";
    echo "Tuổi: " . $student["age"] . "<br>";
    echo "Điểm: " . $student["score"] . "<br>";
}

// Code chính

echo "<h2>Sinh viên có điểm cao nhất</h2>";

$bestStudent = findBestStudent($students);
displayStudent($bestStudent);


echo "<h2>Sinh viên có điểm thấp nhất</h2>";

$worstStudent = findWorstStudent($students);
displayStudent($worstStudent);


echo "<h2>Số sinh viên đạt</h2>";

echo countPassedStudents($students) . " sinh viên";


echo "<h2>Tìm sinh viên theo tên</h2>";

$foundStudent = findStudentByName(
    $students,
    "Tran Thi Binh"
);

if ($foundStudent != null) {
    displayStudent($foundStudent);
} else {
    echo "Không tìm thấy sinh viên";
}

?>