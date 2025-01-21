<?php
session_start(); // Khởi tạo session

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>
    <h1>Đăng nhập</h1>
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    <form id="login-form" onsubmit="handleLogin(event)">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" name="username" id="username" required><br>
        <label for="password">Mật khẩu:</label>
        <input type="password" name="password" id="password" required><br>
        <button type="submit">Đăng nhập</button>
    </form>
    <p>Chưa có tài khoản? <a href="/register">Đăng ký</a></p>
    
</body>
</html>
