async function fetchCart() {
    const loadingMessage = document.getElementById('loading-message');
    const errorMessage = document.getElementById('error-message');
    const cartTable = document.getElementById('cart-table');
    const cartTableBody = cartTable.querySelector('tbody');
    const cartTotal = document.getElementById('cart-total');
    let totalSum = 0;

    try {
        const response = await fetch('/bd/api/carts/get_cart.php');
        const data = await response.json();

        if (data.success && data.cart.length > 0) {
            loadingMessage.style.display = 'none';
            cartTable.style.display = 'table';
            cartTableBody.innerHTML = '';

            data.cart.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><img src="${item.image_url || '/bd/images/noimage.png'}" alt="${item.name}" style="max-width: 50px; height: auto;"></td>
                    <td>${item.name}</td>
                    <td>
                        <button class="quantity-btn" onclick="decreaseQuantity(${item.product_id}, ${item.quantity})">-</button>
                        ${item.quantity}
                        <button class="quantity-btn" onclick="increaseQuantity(${item.product_id}, ${item.quantity})">+</button>
                    </td>
                    <td>${parseFloat(item.price).toFixed(2)} руб.</td>
                    <td>${parseFloat(item.total_price).toFixed(2)} руб.</td>
                    <td><button class="remove-from-cart-btn" onclick="removeFromCart(${item.product_id})">Удалить</button></td>
                `;
                cartTableBody.appendChild(row);
                totalSum += parseFloat(item.total_price);
            });

            cartTotal.textContent = `Общая сумма: ${totalSum.toFixed(2)} руб.`;
        } else {
            loadingMessage.style.display = 'none';
            errorMessage.textContent = data.message || "Корзина пуста.";
            errorMessage.style.display = 'block';
        }
    } catch (error) {
        loadingMessage.style.display = 'none';
        errorMessage.textContent = 'Ошибка подключения к серверу.';
        errorMessage.style.display = 'block';
        console.error('Ошибка:', error);
    }
}

async function removeFromCart(productId) {
    try {
        const response = await fetch('/bd/api/carts/remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId }),
        });

        const data = await response.json();

        if (data.success) {
            alert('Товар удален из корзины');
            fetchCart(); 
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Ошибка при удалении из корзины:', error);
        alert('Произошла ошибка при удалении товара из корзины.');
    }
}

async function updateQuantity(productId, newQuantity) {
    try {
        const response = await fetch('/bd/api/carts/update_quantity.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity: newQuantity }),
        });

        const data = await response.json();

        if (data.success) {
            fetchCart(); 
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Ошибка при обновлении количества товара:', error);
        alert('Произошла ошибка при обновлении количества товара.');
    }
}

function increaseQuantity(productId, currentQuantity) {
    updateQuantity(productId, currentQuantity + 1);
}

function decreaseQuantity(productId, currentQuantity) {
    if (currentQuantity > 1) {
        updateQuantity(productId, currentQuantity - 1);
    } else {
        alert('Количество товара не может быть меньше 1.');
    }
}

document.getElementById('checkout-button').addEventListener('click', () => {
    window.location.href = '/bd/checkout.php';
});


fetchCart();