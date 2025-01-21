<?php

class User
{
    // Lấy tất cả người dùng
    public static function getAllUsers()
    {
        $db = Db::getConnection();
        $stmt = $db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy người dùng theo ID
    public static function getUserById($id)
    {
        $db = Db::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Đăng nhập người dùng (không mã hóa mật khẩu)
    public static function login($username, $password)
    {
        $db = Db::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['password']) {
            return $user;
        }
        return null;
    }

    // Thêm người dùng mới với role mặc định là 'user' nếu không được cung cấp
    public static function register($username, $password, $email, $role = 'user')
    {
        $db = Db::getConnection();
        $stmt = $db->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $password, $email, $role]);
        return self::getUserById($db->lastInsertId());
    }

    // Cập nhật thông tin người dùng, cho phép cập nhật cả mật khẩu và role
    public static function updateUser($id, $username, $password, $email, $role)
    {
        $db = Db::getConnection();
        $stmt = $db->prepare("UPDATE users SET username = ?, password = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $password, $email, $role, $id]);
        return self::getUserById($id);
    }

    // Xóa người dùng
    public static function deleteUser($id)
    {
        $db = Db::getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
