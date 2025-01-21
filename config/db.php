<?php

class Db {
    private static $dbInstance = null;

    public static function getConnection() {
        if (self::$dbInstance === null) {
            try {
                // Cố gắng kết nối đến cơ sở dữ liệu
                self::$dbInstance = new PDO('mysql:host=localhost;dbname=dungvuong_store', 'root', '');
                self::$dbInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               
            } catch (PDOException $e) {
                // Nếu có lỗi kết nối, thông báo lỗi chi tiết
                die("Kết nối thất bại: " . $e->getMessage());
            }
        }
        return self::$dbInstance;
    }}
?>
