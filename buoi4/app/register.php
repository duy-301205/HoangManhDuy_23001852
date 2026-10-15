
<?php
session_start();
require_once "common/dbConnect.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || trim($password) === "") {
        $message = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $message = "Tên đăng nhập đã tồn tại!";
        } else {
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = "INSERT INTO users (username, password)
                    VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$username, $hashedPassword]);

            // 5. Thông báo và chuyển đến đăng nhập
            $_SESSION["register_success"] =
                "Đăng ký thành công! Hãy đăng nhập.";

            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
</head>
<body>
    <h2>Đăng ký tài khoản</h2>

    <?php if ($message !== ""): ?>
        <p style="color: red;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <p>
            <label>Tên đăng nhập:</label><br>
            <input type="text" name="username" required>
        </p>

        <p>
            <label>Mật khẩu:</label><br>
            <input type="password" name="password" required>
        </p>

        <button type="submit">Đăng ký</button>
    </form>

    <p>
        Đã có tài khoản?
        <a href="login.php">Đăng nhập</a>
    </p>
</body>
</html>
