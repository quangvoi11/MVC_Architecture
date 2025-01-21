<?php

class Cart {
    public $Id;
    public $quantity;

    // Constructor
    public function __construct($Id, $quantity) {
        $this->Id = $Id;
        $this->quantity = $quantity;
    }

    // Lấy giỏ hàng
    public static function getCart($id) {
        $db = Db::getConnection();
        $stmt = $db->query("SELECT * FROM products JOIN cart ON products.id=cart.product_id WHERE cart.user_id='$id'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add($usid,$prid,$quantity) {
        $db = Db::getConnection();
        $stmt = $db->prepare("INSERT INTO cart (user_id,product_id, quantity) VALUES (?, ?,?)");
        return $stmt->execute([$usid,$prid,$quantity]);
    }

    // Cập nhật giỏ hàng
    public function update() {
        $db = Db::getConnection();
        $stmt = $db->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        return $stmt->execute([ $this->quantity,$this->Id]);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove() {
        $db = Db::getConnection();
        $stmt = $db->prepare("DELETE FROM cart WHERE id = ?");
        return $stmt->execute([$this->Id]);
    }

    // Lấy sản phẩm trong giỏ hàng theo product_id
    public function check($Idpr, $Idus) {
        $db = Db::getConnection();
        // Sử dụng tham số động thay vì giá trị cố định
        $stmt = $db->prepare("SELECT id, quantity FROM cart WHERE user_id = ? and product_id = ?");
        $stmt->execute([$Idus, $Idpr]); // Truyền giá trị tham số vào câu truy vấn
        return $stmt->fetch(PDO::FETCH_ASSOC); // Chỉ cần 1 kết quả
    }
    
}






