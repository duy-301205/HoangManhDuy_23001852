
<?php
session_start();
require_once "common/dbConnect.php";

if (isset($_SESSION["user_id"])) {
    header("Location: product_list.php");
    exit();
}

$message = "";
$messageType = "error";

if (isset($_SESSION["register_success"])) {
    $message = $_SESSION["register_success"];
    $messageType = "success";
    unset($_SESSION["register_success"]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || trim($password) === "") {
        $message = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!";
    } else {
        $sql = "SELECT id, username, password
                FROM users
                WHERE username = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: product_list.php");
            exit();
        } else {
            $message = "Tên đăng nhập hoặc mật khẩu không chính xác!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập hệ thống</h2>

    <?php if ($message !== ""): ?>
        <p style="color: <?= $messageType === 'success' ? 'green' : 'red' ?>">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <p>
            <label>Tên đăng nhập:</label><br>
            <input type="text" name="username" required>
        </p>

        <p>
            <label>Mật khẩu:</label><br>
            <input type="password" name="password" required>
        </p>

        <button type="submit">Đăng nhập</button>
    </form>

    <p>
        Chưa có tài khoản?
        <a href="register.php">Đăng ký ngay</a>
    </p>
</body>
</html>
