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
    <link rel="stylesheet" href="/bd/styles/login_style.css">
</head>

<body>
<header>
    <div class="navbar">
        <a href="/bd/index.php" id="home-link">Главная</a>
        <a href="/bd/cart.php" id="cart-link">Корзина</a>
        <a href="/bd/login.php" id="login-link">Войти</a>
    </div>
</header>

<!-- Центрированная форма регистрации -->
<div class="login-container">
    <h2>Регистрация</h2>
    <form id="register-form" method="POST">
        <div class="textbox">
            <input type="text" id="username" name="username" placeholder="Логин" required>
        </div>
        <div class="textbox">
            <input type="password" id="password" name="password" placeholder="Пароль" required>
        </div>
        <div class="textbox">
            <input type="password" id="confirm-password" name="confirm_password" placeholder="Подтвердите пароль" required>
        </div>
        <button type="submit" class="btn">Зарегистрироваться</button>
        <div id="error-message" class="error-message"></div>
    </form>
    <div class="footer">
        <p>Уже есть аккаунт? <a href="/bd/login.php">Войти</a></p>
    </div>
</div>

<script>
document.getElementById('register-form').addEventListener('submit', async (e) => {
    e.preventDefault(); 

    const username = document.getElementById('username').value; 
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value; 
    const errorMessage = document.getElementById('error-message');

    if (password !== confirmPassword) {
        errorMessage.textContent = 'Пароли не совпадают';
        errorMessage.style.display = 'block';
        return;
    }

    try {
        // Отправляем POST-запрос к API для регистрации
        const response = await fetch('/bd/api/users/register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        });
        const data = await response.json();

        if (data.success) {
            // Перенаправляем пользователя на страницу входа
            window.location.href = "/bd/login.php";
        } else {
            // Если произошла ошибка (например, логин уже занят)
            errorMessage.textContent = data.message || 'Ошибка регистрации';
            errorMessage.style.display = 'block';
        }
    } catch (error) {
        console.error('Ошибка при запросе:', error);
        errorMessage.textContent = 'Ошибка регистрации';
        errorMessage.style.display = 'block';
    }
});
</script>
</body>

</html>