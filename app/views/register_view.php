<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>
    <h1>Đăng ký</h1>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    <form id="register-form" onsubmit="handleRegister(event)">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" name="username" id="username" required><br>
        <label for="password">Mật khẩu:</label>
        <input type="password" name="password" id="password" required><br>
        <label for="confirm-password">Xác nhận mật khẩu:</label>
        <input type="password" name="confirm-password" id="confirm-password" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <button type="submit">Đăng ký</button>
    </form>
    <p>Đã có tài khoản? <a href="/login">Đăng nhập</a></p>
</body>
</html>
