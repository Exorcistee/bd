<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: /bd/login.php"); // Перенаправляем на страницу логина
    exit;
}

// Данные пользователя из сессии
$userName = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="/bd/styles/account_style.css">
</head>

<body>
<header>
    <div class="navbar">
        <a href="/bd/index.php" id="home-link">Главная</a>
        <a href="/bd/account.php" id="profile-link">Личный кабинет</a>
        <a href="/bd/logout.php" id="logout-link">Выйти</a>
    </div>
</header>

<div class="account-container">
    <div class="account-header">
        <h1>Добро пожаловать, <?php echo htmlspecialchars($userName); ?>!</h1>
    </div>

    <h2>История заказов</h2>
    <p id="loading-message">Загрузка заказов...</p>
    <table class="order-table" id="orders-table" style="display: none;">
        <thead>
            <tr>
                <th>ID заказа</th>
                <th>Статус</th>
                <th>Дата создания</th>
            </tr>
        </thead>
        <tbody>
            <!-- Данные будут загружены через JS -->
        </tbody>
    </table>
    <p id="error-message" class="error-message" style="display: none;"></p>
</div>

<script>
    async function fetchOrders() {
        const loadingMessage = document.getElementById('loading-message');
        const errorMessage = document.getElementById('error-message');
        const ordersTable = document.getElementById('orders-table');
        const ordersTableBody = ordersTable.querySelector('tbody');

        try {
            const response = await fetch('/bd/api/orders/get_orders.php');
            const data = await response.json();

            if (data.success) {
                loadingMessage.style.display = 'none';
                ordersTable.style.display = 'table';

                // Заполняем таблицу заказов
                data.orders.forEach(order => {
                    const row = document.createElement('tr');
                    row.onclick = () => window.location.href = `/bd/order.php?order_id=${order.id}`;
                    row.innerHTML = `
                        <td>${order.id}</td>
                        <td>${order.status}</td>
                        <td>${order.created_at}</td>
                    `;
                    ordersTableBody.appendChild(row);
                });
            } else {
                loadingMessage.style.display = 'none';
                errorMessage.textContent = data.message;
                errorMessage.style.display = 'block';
            }
        } catch (error) {
            loadingMessage.style.display = 'none';
            errorMessage.textContent = 'Ошибка подключения к серверу.';
            errorMessage.style.display = 'block';
            console.error('Ошибка:', error);
        }
    }

    // Загружаем заказы при загрузке страницы
    fetchOrders();
</script>
</body>
</html>
