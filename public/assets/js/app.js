function navigateTo(page) {
  const mainContent = document.getElementById("main-content");
  switch (page) {
    case "product":
      fetch("product_view.php")
        .then((response) => response.text())
        .then((html) => {
          mainContent.innerHTML = html;
          loadProducts(); // Tải sản phẩm từ API
        });
      break;
    case "cart":
      fetch("cart_view.php")
        .then((response) => response.text())
        .then((html) => {
          mainContent.innerHTML = html;
          loadCart(); // Tải giỏ hàng từ API
        });
      break;
    case "login":
      fetch("login_view.php")
        .then((response) => response.text())
        .then((html) => {
          mainContent.innerHTML = html;
        });
      break;
    case "register":
      fetch("register_view.php")
        .then((response) => response.text())
        .then((html) => {
          mainContent.innerHTML = html;
        });
      break;
    default:
      mainContent.innerHTML = "<h2>Trang không tìm thấy</h2>";
  }
}

function loadProducts() {
  fetch("/api/products")
    .then((response) => response.json())
    .then((products) => {
      const productList = document.getElementById("product-list");
      products.forEach((product) => {
        const productItem = document.createElement("div");
        productItem.classList.add("product-item");
        productItem.innerHTML = `
                    <h3>${product.name}</h3>
                    <p>${product.description}</p>
                    <p>${product.price} VNĐ</p>
                    <button onclick="addToCart(${product.id})">Thêm vào giỏ</button>
                `;
        productList.appendChild(productItem);
      });
    });
}

function loadCart() {
  fetch("/api/cart")
    .then((response) => response.json())
    .then((cartItems) => {
      const cartList = document.getElementById("cart-list");
      cartItems.forEach((item) => {
        const cartItem = document.createElement("div");
        cartItem.classList.add("cart-item");
        cartItem.innerHTML = `
                    <p>${item.product.name} - ${item.quantity} x ${item.product.price} VNĐ</p>
                    <button onclick="removeFromCart(${item.product.id})">Xóa</button>
                `;
        cartList.appendChild(cartItem);
      });
    });
}

function addToCart(productId) {
  const data = { productId, quantity: 1 };
  fetch("/api/cart", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((result) => {
      alert(result.success || result.error);
    });
}

function removeFromCart(productId) {
  fetch(`/api/cart/${productId}`, {
    method: "DELETE",
  })
    .then((response) => response.json())
    .then((result) => {
      alert(result.success || result.error);
      loadCart(); // Tải lại giỏ hàng
    });
}
