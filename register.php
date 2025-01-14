<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="/bd/index.php" id="home-link">Главная</a>
            <a href="/bd/order.php" id="orders-link">Мои заказы</a>
            <a href="/bd/cart.php" id="cart-link">Корзина</a>
            <a href="/bd/login.php" id="login-link">Войти</a>
        </div>
    </header>

    <div class="login-form">
        <h2>Регистрация</h2>
        <form id="register-form">
            <input type="text" id="name" placeholder="Имя" required>
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>

    <script>
        document.getElementById('register-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            const response = await fetch("http://localhost/api/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ name, email, password })
            });

            const data = await response.json();
            if (data.token) {
                localStorage.setItem('token', data.token);
                window.location.href = "index.html";
            } else {
                alert("Ошибка регистрации");
            }
        });
    </script>
</body>
</html>