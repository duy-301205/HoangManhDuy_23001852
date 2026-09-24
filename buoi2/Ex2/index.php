<?php

require_once 'MovieFunctions.php';

try {
    $movies = [
        new Movie(1, "Avengers", 100000, 100),
        new Movie(2, "Avatar", 120000, 80),
        new Movie(3, "Batman", 90000, 120)
    ];

    // Đặt vé cho Avengers
    $avengers = findMovieById($movies, 1);

    if ($avengers !== null) {
        $avengers->bookTicket(30);
    } else {
        echo "Không tìm thấy phim Avengers.<br>";
    }

    // Đặt vé cho Avatar
    $avatar = findMovieById($movies, 2);

    if ($avatar !== null) {
        $avatar->bookTicket(25);
    } else {
        echo "Không tìm thấy phim Avatar.<br>";
    }

    // Hủy vé
    if ($avengers !== null) {
        $avengers->cancelTicket(6);
    }

    echo "<h2>DANH SÁCH PHIM</h2>";

    echo "<table border='1' cellpadding='8' cellspacing='0'>";

    echo "
        <tr>
            <th>Mã phim</th>
            <th>Tên phim</th>
            <th>Giá vé</th>
            <th>Tổng ghế</th>
            <th>Ghế còn lại</th>
            <th>Vé đã bán</th>
            <th>Doanh thu</th>
        </tr>
    ";

    foreach ($movies as $movie) {
        $movie->displayInfo();
    }

    echo "</table>";

    echo "<h3>Tổng doanh thu: "
        . number_format(getTotalRevenue($movies))
        . " VNĐ</h3>";

    $bestMovie = getBestSellingMovie($movies);

    if ($bestMovie !== null) {
        echo "<h3>Phim bán được nhiều vé nhất: "
            . $bestMovie->getTitle()
            . " (" . $bestMovie->getSoldSeats() . " vé)"
            . "</h3>";
    } else {
        echo "Danh sách phim đang trống.<br>";
    }
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>