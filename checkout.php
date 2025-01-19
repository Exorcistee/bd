<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /bd/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="navbar">
        <a href="/bd/index.php" id="home-link">Главная</a>
        <a href="/bd/cart.php" id="cart-link">Корзина</a>
        <a href="/bd/logout.php" id="logout-link">Выйти</a>
    </div>
</header>

<div class="checkout-container">
    <h1>Оформление заказа</h1>
    <form id="order-form" method="POST">
    <label for="delivery-address">Адрес доставки:</label>
    <input type="text" id="delivery-address" name="delivery_address" placeholder="Введите адрес доставки" class="address-input" required>

    <button type="submit">Оформить заказ</button>
</form>
    <p id="confirmation-message" style="color: green; display: none;">Заказ оформлен!</p>
</div>

<script>
document.getElementById('order-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const deliveryAddress = document.getElementById('delivery-address').value;
    const confirmationMessage = document.getElementById('confirmation-message');

    try {
        const response = await fetch('/bd/api/orders/submit_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ delivery_address: deliveryAddress })
        });

        const data = await response.json();

        if (data.success) {
            confirmationMessage.style.display = 'block';
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Ошибка при оформлении заказа:', error);
    }
});
</script>
</body>
</html>