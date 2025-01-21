<?php
session_start(); 

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="../public/assets/css/style.css">
</head>

<body>
    <div id="user-management">
        <h3>Danh sách người dùng</h3>
        <form id="user-form">
            <input type="hidden" id="user-id">
            <input type="text" id="user-name" placeholder="Tài khoản" required>
            <input type="text" id="user-password" placeholder="Mật khẩu" required>
            <input type="email" id="user-email" placeholder="Email" required>
            <select id="user-role" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <button type="submit">Lưu</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tài khoản</th>
                    <th>Mật khẩu</th>
                    <th>Email</th>
                    <th>Quyền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="user-table">
                <!-- Dữ liệu sẽ được load từ API -->
            </tbody>
        </table>
    </div>

</body>

</html>
