<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: /bd/login.php");
    exit;
}

$userName = $_SESSION['user_name'] ?? 'Гость';

// Проверяем, передан ли ID заказа
if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']); // Приводим к числу
} else {
    die("ID заказа не передан или некорректен");
}

// Отладочный вывод
error_log("GET параметр: " . ($_GET['order_id'] ?? 'нет параметра'));
error_log("Преобразованный order_id: " . $orderId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детали заказа</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="/bd/styles/order_has_product_style.css">
</head>
<body>
<header>
    <div class="navbar">
        <a href="/bd/index.php" id="home-link">Главная</a>
        <a href="/bd/account.php" id="profile-link">Личный кабинет</a>
        <a href="/bd/logout.php" id="logout-link">Выйти</a>
    </div>
</header>

<div class="order-container">
    <div class="order-header">
        <h1>Добро пожаловать, <?php echo htmlspecialchars($userName); ?>!</h1>
        <h2>Детали заказа #<?php echo htmlspecialchars($orderId); ?></h2>
    </div>

    <table class="order-table">
        <thead>
            <tr>
                <th>Картинка</th>
                <th>Название товара</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody id="order-products">
            <!-- Данные будут загружены через JS -->
        </tbody>
    </table>
    <p id="error-message" style="color: red; display: none;"></p>
    <div id="order-total" class="order-total"></div>
</div>

<script>
  async function fetchOrderDetails() {  
    const orderId = <?php echo $orderId; ?>;
    console.log('123');
    const productsTable = document.getElementById('order-products');
    const errorMessage = document.getElementById('error-message');
    const orderTotal = document.getElementById('order-total');
    let totalSum = 0;

    try {
        const response = await fetch(`/bd/api/orders/get_order_has_product.php?order_id=${orderId}`);
        const data = await response.json();

        if (data.success) {
            data.products.forEach(product => {
                console.log('Обрабатываемый товар:', product); 
                const price = parseFloat(product.price);
                const quantity = parseInt(product.quantity);
                const sum = price * quantity;
                totalSum += sum;

                console.log('123');

                // Берём первое изображение из массива или устанавливаем "Нет изображения"
                const imageUrl = product.images && product.images.length > 0 
                    ? product.images[0] 
                    : '/bd/images/noimage.png';

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><img src="${imageUrl}" alt="${product.name}" style="max-width: 50px; height: auto;" /></td>
                    <td>${product.name}</td>
                    <td>${quantity}</td>
                    <td>${isNaN(price) ? 'N/A' : price.toFixed(2)} руб.</td>
                    <td>${isNaN(sum) ? 'N/A' : sum.toFixed(2)} руб.</td>
                `;
                productsTable.appendChild(row);
            });

            orderTotal.textContent = `Общая сумма заказа: ${totalSum.toFixed(2)} руб.`;
        } else {
            console.log('123');
            errorMessage.textContent = data.message;
            errorMessage.style.display = 'block';
        }
    } catch (error) {
        errorMessage.textContent = 'Ошибка подключения к серверу.';
        errorMessage.style.display = 'block';
        console.error('Ошибка:', error);
    }
}
fetchOrderDetails();

    </script>
</body>
</html>
