<?php
session_start();

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    header("Location: /bd/login.php");
    exit;
}

$userName = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="navbar">
        <a href="/bd/index.php">Главная</a>
        <a href="/bd/account.php">Личный кабинет</a>
        <a href="/bd/cart.php">Корзина</a>
        <a href="/bd/logout.php">Выйти</a>
    </div>
</header>

<div class="cart-container">
    <h1>Добро пожаловать, <?php echo htmlspecialchars($userName); ?>!</h1>
    <h2>Ваша корзина</h2>

    <div id="cart-content">
        <p id="loading-message">Загрузка содержимого корзины...</p>
        <table class="cart-table" id="cart-table" style="display: none;">
            <thead>
                <tr>
                    <th>Картинка</th>
                    <th>Название</th>
                    <th>Количество</th>
                    <th>Цена за единицу</th>
                    <th>Общая цена</th>
                </tr>
            </thead>
            <tbody>
                <!-- Данные будут загружены через JS -->
            </tbody>
        </table>
        <p id="error-message" style="color: red; display: none;"></p>
        <div id="cart-total" class="cart-total"></div>
    </div>
</div>

<script>
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

            console.log('Ответ API корзины:', data); // Для отладки

            if (data.success && data.cart.length > 0) {
                loadingMessage.style.display = 'none';
                cartTable.style.display = 'table';

                data.cart.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><img src="${item.image_url || '/bd/images/noimage.png'}" alt="${item.name}" style="max-width: 50px; height: auto;"></td>
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>${parseFloat(item.price).toFixed(2)} руб.</td>
                        <td>${parseFloat(item.total_price).toFixed(2)} руб.</td>
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

    // Загружаем содержимое корзины при загрузке страницы
    fetchCart();
</script>
</body>
</html>