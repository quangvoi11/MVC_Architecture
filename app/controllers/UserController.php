<?php
class UserController
{
    // Lấy tất cả người dùng
    public function apiGetAll()
    {
        $users = User::getAllUsers();
        echo json_encode($users);
    }

    // Lấy người dùng theo ID
    public function apiGetById($id)
    {
        $user = User::getUserById($id);
        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(["message" => "User not found"]);
        }
    }

    // Đăng nhập người dùng
    public function apiLogin()
    {
        // session_start();
        $data = json_decode(file_get_contents("php://input"), true);
        $user = User::login($data['username'], $data['password']);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            echo json_encode(["message" => "Login successful", "user" => $user]);
        } else {
            echo json_encode(["message" => "Invalid credentials"]);
        }
    }

    // Đăng ký người dùng với role tùy chọn
    public function apiRegister()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $role = isset($data['role']) ? $data['role'] : 'user'; // Mặc định là 'user' nếu không có role
        $user = User::register($data['username'], $data['password'], $data['email'], $role);

        if ($user) {
            echo json_encode(["message" => "User registered successfully", "user" => $user]);
        } else {
            echo json_encode(["message" => "Registration failed"]);
        }
    }

    // Cập nhật người dùng
    public function apiUpdate($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $user = User::getUserById($id);

        if ($user) {
            $updatedUser = User::updateUser($id, $data['username'], $data['password'], $data['email'], $data['role']);
            echo json_encode(["message" => "User updated successfully", "user" => $updatedUser]);
        } else {
            echo json_encode(["message" => "User not found"]);
        }
    }

    // Xóa người dùng
    public function apiDelete($id)
    {
        $user = User::getUserById($id);

        if ($user) {
            $isDeleted = User::deleteUser($id);
            if ($isDeleted) {
                echo json_encode(["message" => "User deleted successfully"]);
            } else {
                echo json_encode(["message" => "Failed to delete user"]);
            }
        } else {
            echo json_encode(["message" => "User not found"]);
        }
    }
}
