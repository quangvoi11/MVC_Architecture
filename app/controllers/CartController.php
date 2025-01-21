<?php
session_start();
class CartController {
    // Lấy giỏ hàng
    public function apiGetCart() {
        $cart = Cart::getCart($_SESSION['user_id']); // Gọi phương thức từ model Cart
        echo json_encode($cart); // Trả về giỏ hàng dưới dạng JSON
    }

    // Thêm sản phẩm vào giỏ hàng
    public function apiAddToCart() {
        $data = json_decode(file_get_contents("php://input"), true); // Nhận và giải mã dữ liệu
        $cartItem = new Cart('', ''); // Tạo đối tượng Cart mới
        $productId = $data['productId']; // Lấy ID sản phẩm từ dữ liệu
        $userId = $_SESSION['user_id']; // Lấy ID người dùng từ session
    
        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $existingItem = $cartItem->check($productId, $userId);
        if ($existingItem==false) {
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới

             $cartItem->add($userId, $productId, 1);
             echo json_encode(["message" => "Cart add"]);

           // Mặc định số lượng là 1
        } else {
            // Nếu sản phẩm đã có trong giỏ, bạn có thể quyết định cập nhật số lượng, ví dụ:
            $newQuantity = $existingItem['quantity'] + 1;
            $cartItem1 = new Cart( $existingItem['id'], $newQuantity);
             $cartItem1->update();
             echo json_encode(["message" => "Cart updated"]);
        }
    }
    

    // Cập nhật giỏ hàng
    public function apiUpdateCart($Id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $cartItem = new Cart($Id, $data['quantity']);
        $cartItem->update(); // Cập nhật số lượng sản phẩm
        echo json_encode(["message" => "Cart updated"]);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function apiRemoveFromCart($Id) {
        $cartItem = new Cart($Id,'0');
        $cartItem->remove();
        echo json_encode(["message" => "Product removed from cart"]);
    }
}
