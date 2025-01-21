<?php
session_start(); // Khởi tạo session

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
            <button onclick="navigateTo('dashboard')">Trang chủ</button>
            <button onclick="navigateTo('user-management')">Quản lý người dùng</button>
            <button onclick="navigateTo('product-management')">Quản lý sản phẩm</button>
            <button onclick="navigateTo('logout')">Đăng xuất</button>
            <p style="margin-left: auto; margin-right: 10px;">Tên đăng nhập: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
        </nav>
    </header>

    <main id="main-content">
        <!-- Nội dung sẽ thay đổi dựa trên lựa chọn của người dùng -->
    </main>

    <script>
        function navigateTo(page) {
            const mainContent = document.getElementById('main-content');

            switch (page) {
                case 'dashboard':
                    fetch('./views/dashboard-view.php')
                        .then(response => response.text())
                        .then(html => {
                            mainContent.innerHTML = html;
                            loadStats();
                        });
                    break;
                case 'user-management':
                    fetch('./views/user-management-view.php')
                        .then(response => response.text())
                        .then(html => {
                            mainContent.innerHTML = html;
                            loadUsers();
                        });
                    break;
                case 'product-management':
                    fetch('./views/product-management-view.php')
                        .then(response => response.text())
                        .then(html => {
                            mainContent.innerHTML = html;
                            loadProducts();
                        });
                    break;
                case 'logout':
                    window.location.href = './logout.php'; // Chuyển hướng đến trang đăng xuất
                    break;
                default:
                    mainContent.innerHTML = '<h2>Trang không tìm thấy</h2>';
            }

        }
        window.onload = function() {
            navigateTo('dashboard');
        }
        //////////////////////////////////////////////////////////////////////////////////////////////
        function loadStats() {
            fetch('http://localhost/DungVuongStore/public/api/users')
                .then(response => response.json())
                .then(users => {
                    // Đếm số lượng người dùng
                    const totalId = users.length;
                    // // Tính tổng ID của tất cả người dùng
                    // const totalId = users.reduce((sum, user) => sum + user.id, 0);
                    // Hiển thị tổng ID lên giao diện
                    document.getElementById('user-count').innerText = totalId || 'Không thể tính tổng.';
                })
                .catch(() => {
                    document.getElementById('user-count').innerText = 'Không thể tải.';
                });

            fetch('http://localhost/DungVuongStore/public/api/products')
                .then(response => response.json())
                .then(products => {
                    //Đếm số lượng người dùng
                    const totalId = products.length;
                    // // Tính tổng ID của tất cả người dùng
                    // const totalId = products.reduce((sum, product) => sum + product.id, 0);
                    // Hiển thị tổng ID lên giao diện
                    document.getElementById('product-count').innerText = totalId || 'Không thể tính tổng.';
                })
                .catch(() => {
                    document.getElementById('product-count').innerText = 'Không thể tải.';
                });
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        function loadUsers() {
            // Lắng nghe sự kiện submit form để thêm hoặc sửa người dùng
            document.getElementById('user-form').addEventListener('submit', function(event) {
                event.preventDefault();

                const id = document.getElementById('user-id').value;
                const username = document.getElementById('user-name').value;
                const password = document.getElementById('user-password').value;
                const email = document.getElementById('user-email').value;
                const role = document.getElementById('user-role').value;

                const user = {
                    id,
                    username,
                    password,
                    email,
                    role
                };

                const url = id ?
                    `http://localhost/DungVuongStore/public/api/users/${id}` :
                    'http://localhost/DungVuongStore/public/api/register';
                const method = id ? 'PUT' : 'POST';

                // Gửi yêu cầu thêm hoặc sửa người dùng
                fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(user)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result) {
                            alert(`${id ? 'Cập nhật' : 'Thêm'} thành công!`);
                            loadUsers();
                            document.getElementById('user-form').reset();
                        } else {
                            alert('Có lỗi xảy ra, vui lòng thử lại.');
                        }
                    })
            });
            // Hàm tải danh sách người dùng
            fetch('http://localhost/DungVuongStore/public/api/users')
                .then(response => response.json())
                .then(users => {
                    const table = document.getElementById('user-table');
                    table.innerHTML = ''; // Xóa dữ liệu cũ trước khi hiển thị mới
                    users.forEach(user => {
                        const row = `<tr>
                            <td>${user.id}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                            <td>
                                <button onclick="editUser(${user.id})">Sửa</button>
                                <button onclick="deleteUser(${user.id})">Xóa</button>
                            </td>
                        </tr>`;
                        table.innerHTML += row;
                    });
                })
        }

        // Hàm sửa thông tin người dùng
        function editUser(id) {
            fetch(`http://localhost/DungVuongStore/public/api/users/${id}`)
                .then(response => response.json())
                .then(user => {
                    document.getElementById('user-id').value = user.id;
                    document.getElementById('user-name').value = user.username;
                    document.getElementById('user-password').value = user.password;
                    document.getElementById('user-email').value = user.email;
                    document.getElementById('user-role').value = user.role;
                })
        }

        // Hàm xóa người dùng
        function deleteUser(id) {
            if (confirm('Xác nhận xóa người dùng này?')) {
                fetch(`http://localhost/DungVuongStore/public/api/users/${id}`, {
                        method: 'DELETE'
                    })
                    .then(() => loadUsers())
            }
        }


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function loadProducts() {
            // Gắn sự kiện submit cho form để xử lý thêm/sửa sản phẩm
            document.getElementById('product-form').addEventListener('submit', function(event) {
                event.preventDefault();

                const id = document.getElementById('product-id').value; // Kiểm tra nếu là cập nhật
                const name = document.getElementById('product-name').value;
                const description = document.getElementById('product-description').value;
                const price = document.getElementById('product-price').value;
                const image = document.getElementById('product-image').files[0];

                const newProduct = {
                    id: id,
                    name: name,
                    description: description,
                    price: price,
                    image,
                };
                const url = id ?
                    `http://localhost/DungVuongStore/public/api/products/${id}` :
                    'http://localhost/DungVuongStore/public/api/products';
                const method = id ? 'PUT' : 'POST';

                fetch(url, {
                        method,
                        headers: {
                            "Content-Type": "application/json", // Đảm bảo gửi dữ liệu dưới dạng JSON
                        },
                        body: JSON.stringify(newProduct)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result) {
                            alert(`${id ? 'Cập nhật' : 'Thêm'} sản phẩm thành công!`);
                            document.getElementById('product-form').reset();
                            document.getElementById('form-submit-button').textContent = 'Thêm sản phẩm';
                            loadProducts(); // Refresh danh sách sau khi thêm/sửa
                        } else {
                            alert('Có lỗi xảy ra, vui lòng thử lại.');
                        }
                    })
                    .catch(() => alert('Không thể xử lý yêu cầu.'));
            });
            // Tải dữ liệu danh sách sản phẩm từ API
            fetch('http://localhost/DungVuongStore/public/api/products')
                .then(response => response.json())
                .then(products => {
                    const productTable = document.getElementById('product-table');
                    productTable.innerHTML = ''; // Xóa dữ liệu cũ trước khi hiển thị mới

                    products.forEach(product => {
                        const imageUrl = `../public/assets/images/${product.image}`;
                        const row = `
                            <tr id="product-${product.id}">
                                <td>${product.id}</td>
                                <td>${product.name}</td>
                                <td><img src="${imageUrl}" alt="${product.name}" width="50"></td>
                                <td>${product.description}</td>
                                <td>${product.price} VNĐ</td>
                                <td>
                                    <button onclick="editProduct(${product.id})">Sửa</button>
                                    <button onclick="deleteProduct(${product.id})">Xóa</button>
                                </td>
                            </tr>`;
                        productTable.innerHTML += row;
                    });
                })
                .catch(() => {
                    document.getElementById('product-table').innerHTML = '<tr><td colspan="6">Không thể tải danh sách sản phẩm.</td></tr>';
                });
        }

        // Hàm sửa sản phẩm
        function editProduct(productId) {
            fetch(`http://localhost/DungVuongStore/public/api/products/${productId}`)
                .then(response => response.json())
                .then(product => {
                    document.getElementById('product-id').value = product.id;
                    document.getElementById('product-name').value = product.name;
                    document.getElementById('product-description').value = product.description;
                    document.getElementById('product-price').value = product.price;

                    document.getElementById('form-submit-button').textContent = 'Cập nhật sản phẩm';
                })
                .catch(() => alert('Không thể tải thông tin sản phẩm.'));
        }

        // Hàm xóa sản phẩm
        function deleteProduct(productId) {
            if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;

            fetch(`http://localhost/DungVuongStore/public/api/products/${productId}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Xóa sản phẩm thành công!');
                        document.getElementById(`product-${productId}`).remove();
                    } else {
                        alert('Có lỗi xảy ra khi xóa sản phẩm.');
                    }
                })
                .catch(() => alert('Không thể xóa sản phẩm.'));
        }
    </script>
</body>

</html>