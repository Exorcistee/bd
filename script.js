const apiUrl = "http://localhost/api";  // Замените на ваш URL API

let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Функция для выполнения API-запросов
async function apiRequest(method, url, data = {}) {
    const headers = {
        "Content-Type": "application/json",
        "Authorization": localStorage.getItem('token') ? `Bearer ${localStorage.getItem('token')}` : "",
    };

    const response = await fetch(url, {
        method,
        headers,
        body: method === "POST" || method === "PUT" ? JSON.stringify(data) : undefined,
    });

    return response.json();
}

// Загрузка списка товаров
async function fetchProducts() {
    const products = await apiRequest("GET", `${apiUrl}/products`);
    const productList = document.getElementById('product-list');

    products.data.forEach(product => {
        const productItem = document.createElement('div');
        productItem.className = 'product-item';
        productItem.innerHTML = `
            <img src="${product.image_url}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>${product.description}</p>
            <p class="price">${product.price}₽</p>
            <button onclick="addToCart(${product.id})">Добавить в корзину</button>
        `;
        productList.appendChild(productItem);
    });
}

// Добавление товара в корзину
function addToCart(productId) {
    const existingProduct = cart.find(item => item.product_id === productId);

    if (existingProduct) {
        existingProduct.quantity += 1;
    } else {
        cart.push({ product_id: productId, quantity: 1 });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
}

// Инициализация страницы
fetchProducts();