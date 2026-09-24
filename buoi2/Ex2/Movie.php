<?php

class Movie {
    
    private $id;
    private $title;
    private $price;
    private $totalSeats;
    private $availableSeats;

    public function __construct($id, $title, $price, $totalSeats) {
         if ($id <= 0) {
            throw new Exception("Mã phim phải lớn hơn 0.");
        }

        if (empty(trim($title))) {
            throw new Exception("Tên phim không được để trống.");
        }

        if ($price <= 0) {
            throw new Exception("Giá vé phải lớn hơn 0.");
        }

        if ($totalSeats <= 0) {
            throw new Exception("Tổng số ghế phải lớn hơn 0.");
        }

        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->totalSeats = $totalSeats;

        $this->availableSeats = $totalSeats;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getTotalSeats()
    {
        return $this->totalSeats;
    }

    public function getAvailableSeats()
    {
        return $this->availableSeats;
    }

    public function bookTicket($quantity) {

        if($quantity <= 0) {
            throw new Exception("Không thể đặt vé: Số lượng vé phải lớn hơn 0.");
        }

        if($quantity > $this->availableSeats) {
            throw new Exception("Không thể đặt vé: Số lượng vé vượt quá số ghế còn lại.");
        }

        $this->availableSeats -= $quantity;

        echo "Đã đặt " . $quantity . " vé phim "
            . $this->title . ".<br>";
    }

    public function cancelTicket($quantity) {
        
        if($quantity <= 0) {
            throw new Exception("Không thể hủy vé: Số lượng vé phải lớn hơn 0.");
        }

        $soldSeats = $this->getSoldSeats();

        if($quantity > $soldSeats) {
            throw new Exception("Không thể hủy vé: Số lượng vé hủy vượt quá số vé đã bán.");
        }

        $this->availableSeats += $quantity;

        echo "Đã hủy " . $quantity . " vé phim " . $this->title . ".<br>";
    }

    public function getSoldSeats()
    {
        return $this->totalSeats - $this->availableSeats;
    }

    public function getRevenue()
    {
        return $this->getSoldSeats() * $this->price;
    }

    public function displayInfo()
    {
        echo "<tr>";
        echo "<td>" . $this->id . "</td>";
        echo "<td>" . $this->title . "</td>";
        echo "<td>" . number_format($this->price) . " VNĐ</td>";
        echo "<td>" . $this->totalSeats . "</td>";
        echo "<td>" . $this->availableSeats . "</td>";
        echo "<td>" . $this->getSoldSeats() . "</td>";
        echo "<td>" . number_format($this->getRevenue()) . " VNĐ</td>";
        echo "</tr>";
    }
}
?>