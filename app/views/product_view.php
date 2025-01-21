<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cửa hàng Dung Vượng</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>

<body>

    <h1>Danh sách sản phẩm</h1>

    <input type="text" id="search-input" placeholder="Tìm kiếm sản phẩm..." />
    <button onclick="searchProducts()">Tìm kiếm</button>
    <div id="product-list">

    </div>
    
</body>

</html>