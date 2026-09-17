<?php

require_once "classes/Student.php";

function findBestStudent($students) {
    $bestStudent = $students[0];

    foreach ($students as $student) {
        if($student->getScore() > $bestStudent->getScore()) {
            $bestStudent = $student;
        }
    }

    return $bestStudent;
}

function countPassedStudents($students) {
    $countPass = 0;

    foreach ($students as $student) {
        if($student->isPassed()) {
            $countPass++;
        }
    }

    return $countPass;
}

function calculateAverageScore($students) {
    $totalScore = 0;

    foreach ($students as $student) {
        $totalScore += $student->getScore();
    }

    return $totalScore / count($students);
}

// Tạo Object student
$student1 = new Student("Nguyen Van An", 20, 8.5);
$student2 = new Student("Tran Thi Binh", 21, 6.5);
$student3 = new Student("Le Van Cuong", 19, 4.5);
$student4 = new Student("Pham Thi Dung", 20, 7.5);


$students = [
    $student1,
    $student2,
    $student3,
    $student4
];

echo "<h2>Danh sách sinh viên</h2>";

foreach ($students as $student) {
    $student->display();
}


echo "<h2>Sinh viên có điểm cao nhất</h2>";

$bestStudent = findBestStudent($students);
$bestStudent->display();


echo "<h2>Số sinh viên đạt</h2>";

echo countPassedStudents($students) . " sinh viên";


echo "<h2>Điểm trung bình của lớp</h2>";

echo calculateAverageScore($students);
?>