<?php
session_start(); // Khởi tạo session
$isLoggedIn = isset($_SESSION['username']); // Kiểm tra xem người dùng đã đăng nhập chưa
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa hàng xe máy Dung Vượng</title>
    <link rel="stylesheet" href="../public/assets/css/style.css">
</head>

<body>
    <header>
        <nav style="display: flex; align-items: center;">
            <button onclick="navigateTo('product')">Trang chủ</button>
            <button onclick="navigateTo('cart')">Giỏ hàng</button>

            <!-- Hiển thị nút đăng nhập, đăng ký hoặc đăng xuất tùy theo trạng thái đăng nhập -->
            <?php if ($isLoggedIn): ?>
                <button onclick="navigateTo('logout')">Đăng xuất</button>
                <p style="margin-left: auto; margin-right: 10px;">Tên đăng nhập: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
            <?php else: ?>
                <button onclick="navigateTo('login')">Đăng nhập</button>
                <button onclick="navigateTo('register')">Đăng ký</button>
            <?php endif; ?>
        </nav>
    </header>
    <main id="main-content">
        <!-- Nội dung sẽ thay đổi dựa trên lựa chọn của người dùng -->
    </main>

    <script>
        function navigateTo(page) {
            const mainContent = document.getElementById('main-content');

            switch (page) {
                case 'product':
                    fetch('./views/product_view.php')
                        .then(response => response.text())
                        .then(html => {
                            mainContent.innerHTML = html;
                            loadProducts();
                        });
                    break;
                case 'cart':
                    fetch('./views/cart_view.php')
                        .then(response => response.text())
                        .then(html => {
                            mainContent.innerHTML = html;
                            loadCart();
                        });
                    break;
                case 'login':
                    fetch('./views/login_view.php')
                        .then(response => response.text())
                        .then(html => {
                            mainContent.innerHTML = html;
                        });
                    break;
                case 'register':
                    fetch('./views/register_view.php')
                        .then(response => response.text())
                        .then(html => {
                            mainContent.innerHTML = html;
                        });
                    break;
                case 'logout':
                    window.location.href = './logout.php'; // Chuyển hướng đến trang đăng xuất
                    break;
                default:
                    mainContent.innerHTML = '<h2>Trang không tìm thấy</h2>';
            }
        }
        //////////////////////////////////////////////////////////////////////////////////////////
        function loadProducts() {
            fetch('http://localhost/DungVuongStore/public/api/products')
                .then(response => response.json())
                .then(products => {
                    const productList = document.getElementById('product-list');
                    products.forEach(product => {
                        const productItem = document.createElement('div');
                        productItem.classList.add('product-item');

                        // Ghép đường dẫn ảnh
                        const imageUrl = `../public/assets/images/${product.image}`;

                        productItem.innerHTML = `
                            <h3>${product.name}</h3>
                            <img src="${imageUrl}" alt="${product.name}" />
                            <p>${product.description}</p>
                            <p>${product.price} VNĐ</p>
                            <button onclick="addToCart(${product.id})">Thêm vào giỏ</button>
                        `;
                        productList.appendChild(productItem);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        /////////////////////////////////////////////////////////////////////////////////////
        function loadCart() {
            fetch('http://localhost/DungVuongStore/public/api/cart')
                .then(response => response.json())
                .then(cartItems => {
                    const cartItemsContainer = document.getElementById('cart-items');
                    const cartMessage = document.getElementById('cart-message');

                    cartItemsContainer.innerHTML = ""; // Xóa nội dung cũ
                    if (cartItems.length === 0) {
                        cartMessage.textContent = "Giỏ hàng của bạn hiện đang trống!";
                    } else {
                        cartMessage.textContent = ""; // Xóa thông báo nếu giỏ hàng có sản phẩm
                        cartItems.forEach(item => {
                            const totalPrice = item.price * item.quantity;
                            const row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${item.name}</td>
                                <td>${Number(item.price).toLocaleString()} VNĐ</td>
                                <td>
                                    <input type="number" min="1" value="${item.quantity}" 
                                        onchange="updateCart(${item.id}, this.value)">
                                </td>
                                <td>${Number(totalPrice).toLocaleString()} VNĐ</td>
                                <td>
                                    <button onclick="removeFromCart(${item.id})">Xóa</button>
                                </td>
                            `;
                            cartItemsContainer.appendChild(row);
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Hàm thêm sản phẩm vào giỏ hàng
        function addToCart(productId) {
            // Kiểm tra xem người dùng đã đăng nhập chưa
            const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;

            if (!isLoggedIn) {
                // Nếu chưa đăng nhập, hiển thị thông báo yêu cầu đăng nhập
                alert("Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.");
                navigateTo('login'); // Chuyển hướng đến trang đăng nhập
                return;
            }
            const quantity = 1;
            const data = {
                productId,
                quantity
            };
            fetch('http://localhost/DungVuongStore/public/api/cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result) {
                        alert("Thêm vào giỏ hàng thành công");
                        loadCart(); // Refresh lại giỏ hàng
                    } else {
                        alert("Thêm vào giỏ hàng thất bại");
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Hàm cập nhật số lượng trong giỏ hàng
        function updateCart(cartItemId, newQuantity) {
            fetch(`http://localhost/DungVuongStore/public/api/cart/${cartItemId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(result => {
                    if (result) {
                        alert("Số lượng đã được cập nhật!");
                        loadCart(); // Refresh lại giỏ hàng
                    } else {
                        alert("Cập nhật số lượng thất bại!");
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Hàm xóa sản phẩm khỏi giỏ hàng
        function removeFromCart(cartItemId) {
            fetch(`http://localhost/DungVuongStore/public/api/cart/${cartItemId}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(result => {
                    if (result) {
                        alert("Sản phẩm đã được xóa!");
                        loadCart(); // Refresh lại giỏ hàng
                    } else {
                        alert("Xóa sản phẩm thất bại!");
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        //////////////////////////////////////////////////////////////////////////////////////
        function handleLogin(event) {
            event.preventDefault(); // Ngăn chặn hành động mặc định của form

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            // Kiểm tra các trường hợp trường trống
            if (!username || !password) {
                alert('Vui lòng nhập đầy đủ thông tin');
                return;
            }
            const loginData = {
                username,
                password
            };
            fetch('http://localhost/DungVuongStore/public/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(loginData),
                })
                .then(response => response.json())
                .then(result => {
                    if (result.message === "Login successful") {
                        // Điều hướng tùy theo vai trò người dùng
                        if (result.user.role === 'admin') {
                            window.location.href = 'dashboard.php'; // Chuyển hướng tới trang quản lý admin
                        } else {
                            window.location.href = 'index.php';
                        }
                    } else {
                        alert('Thông tin đăng nhập sai!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                });
        }

        function handleRegister(event) {
            event.preventDefault(); // Ngăn chặn hành động mặc định của form

            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            // Kiểm tra các trường hợp trường trống
            if (!username || !email || !password || !confirmPassword) {
                alert('Vui lòng nhập đầy đủ thông tin');
                return;
            }
            // Kiểm tra mật khẩu xác nhận
            if (password !== confirmPassword) {
                alert('Mật khẩu xác nhận không khớp');
                return;
            }
            const registerData = {
                username,
                email,
                password
            };
            fetch('http://localhost/DungVuongStore/public/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(registerData),
                })
                .then(response => response.json())
                .then(result => {
                    if (result.message === "User registered successfully") {
                        alert('Đăng ký thành công! Bạn có thể đăng nhập ngay.');
                        navigateTo('login'); // Sau khi đăng ký thành công, chuyển hướng đến trang đăng nhập
                    } else {
                        alert(result.message || 'Có lỗi xảy ra, vui lòng thử lại!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                });
        }
        window.onload = function() {
            navigateTo('product');
        }
        ///////////////////////////////////////////////////////////////////////////////////////////
        // Hàm tìm kiếm sản phẩm
        function searchProducts() {
            const keyword = document.getElementById('search-input').value.trim();

            if (!keyword) {
                alert("Vui lòng nhập từ khóa tìm kiếm!");
                return;
            }

            const apiUrl = `http://localhost/DungVuongStore/public/api/products/search?name=${encodeURIComponent(keyword)}`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(text => {
                    try {
                        const products = JSON.parse(text); // Chuyển đổi chuỗi JSON
                        const productList = document.getElementById('product-list');
                        productList.innerHTML = ''; // Xóa nội dung cũ
                        if (products.length === 0) {
                            productList.innerHTML = '<p>Không tìm thấy sản phẩm nào.</p>';
                            return;
                        }
                        products.forEach(product => {
                            const productItem = document.createElement('div');
                            productItem.classList.add('product-item');

                            // Ghép đường dẫn ảnh
                            const imageUrl = `../public/assets/images/${product.image}`;

                            productItem.innerHTML = `
                            <h3>${product.name}</h3>
                            <img src="${imageUrl}" alt="${product.name}" />
                            <p>${product.description}</p>
                            <p>${product.price} VNĐ</p>
                            <button onclick="addToCart(${product.id})">Thêm vào giỏ</button>
                        `;
                            productList.appendChild(productItem);
                        });
                    } catch (e) {
                        console.error('Dữ liệu không hợp lệ: ', e);
                        alert('Đã xảy ra lỗi khi nhận dữ liệu từ API.');
                    }
                })
                .then(products => {
                    console.log(products);
                    const productList = document.getElementById('product-list');
                    productList.innerHTML = ''; // Xóa nội dung cũ
                    if (products.length === 0) {
                        productList.innerHTML = '<p>Không tìm thấy sản phẩm nào.</p>';
                        return;
                    }
                    products.forEach(product => {
                        const productItem = document.createElement('div');
                        productItem.classList.add('product-item');

                        // Ghép đường dẫn ảnh
                        const imageUrl = `../public/assets/images/${product.image}`;

                        productItem.innerHTML = `
                            <h3>${product.name}</h3>
                            <img src="${imageUrl}" alt="${product.name}" />
                            <p>${product.description}</p>
                            <p>${product.price} VNĐ</p>
                            <button onclick="addToCart(${product.id})">Thêm vào giỏ</button>
                        `;
                        productList.appendChild(productItem);
                    });
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    alert('Đã xảy ra lỗi khi tìm kiếm.');
                });
        }

        // // Tìm kiếm khi nhấn Enter
        // document.getElementById('search-input').addEventListener('keypress', function(e) {
        //     if (e.key === 'Enter') {
        //         searchProducts();
        //     }
        // });

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        searchProducts();
                    }
                });
            }
        });
    </script>
</body>

</html>