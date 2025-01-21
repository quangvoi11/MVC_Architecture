<?php
session_start(); // Khởi tạo session
$isLoggedIn = isset($_SESSION['username']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cửa hàng Dung Vượng</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>

<body>
    <h1>Giỏ hàng của bạn</h1>
    
    <table style="width: 100%; border: 1px solid #ccc; padding: 10px;">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng cộng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        
        <tbody id="cart-items">

        </tbody>
    </table>
    <p id="cart-message" style="color: red; text-align: justify;"></p>
    <div id="cart-actions" style="margin-top: 20px;">
        <a href="index.php">Tiếp tục mua sắm</a> |
        <a href="checkout.php">Thanh toán</a>
    </div>

</body>

</html>




<!-- // const mainContent = document.getElementById('main-content');
            //         mainContent.innerHTML = `
            //     <h1>Giỏ hàng của bạn</h1>
            //     <p id="cart-message" style="color: red; text-align: justify;"></p>
            //     <table style="width: 100%; border: 1px solid #ccc; padding: 10px;">
            //         <thead>
            //             <tr>
            //                 <th>Tên sản phẩm</th>
            //                 <th>Giá</th>
            //                 <th>Số lượng</th>
            //                 <th>Tổng cộng</th>
            //                 <th>Thao tác</th>
            //             </tr>
            //         </thead>
            //         <tbody id="cart-items"></tbody>
            //     </table>
            //     <div id="cart-actions" style="margin-top: 20px;">
            //         <a href="index.php">Tiếp tục mua sắm</a> | 
            //         <a href="checkout.php">Thanh toán</a>
            //     </div>
            // `; -->