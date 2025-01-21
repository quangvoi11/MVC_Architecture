<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>

<body>
    <div id="product-management">
        <h3>Danh sách sản phẩm</h3>
        <form id="product-form">
            <input type="hidden" id="product-id"> <!-- ID ẩn để sử dụng cho cập nhật -->
            <input type="text" id="product-name" placeholder="Tên sản phẩm" required>
            <textarea id="product-description" placeholder="Mô tả sản phẩm" required></textarea>
            <input type="number" id="product-price" placeholder="Giá sản phẩm" required>
            <input type="file" id="product-image">
            <button type="submit" id="form-submit-button">Thêm sản phẩm</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Ảnh</th>
                    <th>Mô tả</th>
                    <th>Giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="product-table">
                <!-- Sản phẩm sẽ được tải qua API -->
            </tbody>
        </table>
    </div>
</body>

</html>