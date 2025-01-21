
<?php

class ProductController {
    public function apiGetAll() {

        $products = Product::getAllProducts();
        echo json_encode($products);
    }

    public function apiGetById($id) {
        $product = Product::getProductById($id);
        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(['error' => 'Sản phẩm không tìm thấy']);
        }
    }

    public function apiCreate() {
        $data = json_decode(file_get_contents("php://input"), true);
        $product = Product::createProduct($data['name'], $data['price'], $data['description']);
        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(['error' => 'Không thể tạo sản phẩm']);
        }
    }

    public function apiUpdate($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $product = Product::updateProduct($id, $data['name'], $data['price'], $data['description']);
        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(['error' => 'Không thể cập nhật sản phẩm']);
        }
    }

    public function apiDelete($id) {
        $result = Product::deleteProduct($id);
        if ($result) {
            echo json_encode(['success' => 'Sản phẩm đã được xóa']);
        } else {
            echo json_encode(['error' => 'Không thể xóa sản phẩm']);
        }
    }
    public function apiSearch() {
        header('Content-Type: application/json');
        $name = isset($_GET['name']) ? $_GET['name'] : '';
        if (!empty($name)) {
            $products = Product::searchProducts($name); 
            echo json_encode($products);
        } else {
            echo json_encode(['error' => 'Không có từ khóa tìm kiếm']);
        }
    }
}
?>
