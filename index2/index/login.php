<?php
session_start();
@include 'config.php';

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = md5($_POST['pass']); // dùng tạm md5, bạn nên chuyển sang password_hash

    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $password]);

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Lưu thông tin vào session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['name'] = $row['name'];

        // Phân quyền điều hướng
        if ($row['user_type'] === 'admin') {
            header('Location: ../dashboard.php');
            exit();
        } elseif ($row['user_type'] === 'user') {
            header('Location: home.php');
            exit();
        } else {
            $message[] = 'Loại người dùng không hợp lệ!';
        }

    } else {
        $message[] = 'Sai email hoặc mật khẩu!';
    }
}
?>

<!-- HTML giao diện đăng nhập -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/components.css">
</head>
<body>

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . $msg . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
    }
}
?>

<section class="form-container">
    <form action="" method="POST">
        <h3>Đăng nhập</h3>
        <input type="email" name="email" class="box" placeholder="Nhập email" required>
        <input type="password" name="pass" class="box" placeholder="Nhập mật khẩu" required>
        <input type="submit" name="submit" value="Đăng nhập" class="btn">
        <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
    </form>
</section>

</body>
</html>
