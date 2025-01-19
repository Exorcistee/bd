<?php
session_start();

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
    <title>Моя корзина</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="/bd/styles/cart_style.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="/bd/index.php">Главная</a>
            <a href="/bd/account.php">Личный кабинет</a>
            <a href="/bd/logout.php">Выйти</a>
        </div>
    </header>

    <div class="cart-container">
        <h1>Добро пожаловать, <?= htmlspecialchars($userName) ?>!</h1>
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
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Данные будут загружены через JS -->
                </tbody>
            </table>
            <p id="error-message" style="color: red; display: none;"></p>
            <div id="cart-total" class="cart-total"></div>
        </div>
        <div class="cart-actions">
    <button id="checkout-button" class="btn">Оформить заказ</button>
</div>
    </div>

    <script src="/bd/scripts/cart_script.js"></script>
</body>
</html>