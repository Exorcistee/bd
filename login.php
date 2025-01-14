<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Войти</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="/bd/styles/login_style.css">
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

    <!-- Центрированная форма входа -->
    <div class="login-container">
        <h2>Вход</h2>
        <form id="login-form" method="POST">
        <div class="textbox">
            <input type="text" id="username" name="username" placeholder="Логин" required>
        </div>
        <div class="textbox">
            <input type="password" id="password" name="password" placeholder="Пароль" required>
        </div>
        <div class="checkbox">
            <input type="checkbox" id="remember-me">
            <label for="remember-me">Запомнить меня</label>
        </div>
        <button type="submit" class="btn">Войти</button>
        <div id="error-message" class="error-message"></div>
    </form>
    <div class="footer">
        <a href="#">Забыли пароль?</a>
        <p>Еще нет аккаунта? <a href="#">Зарегистрироваться</a></p>
    </div>
    </div>

    <script>
    document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault(); // Предотвращаем стандартное поведение формы

        const username = document.getElementById('username').value; // Получаем логин
        const password = document.getElementById('password').value; // Получаем пароль
        const errorMessage = document.getElementById('error-message');

        try {
            // Отправляем GET-запрос к API
            const response = await fetch(`http://localhost/bd/api/users/login.php?login=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`);
            const data = await response.json();

            if (data.success) {
                // Перенаправляем пользователя на главную страницу
                window.location.href = "/bd/index.php";
            } else {
                // Если произошла ошибка (неверный логин или пароль)
                errorMessage.textContent = 'Неверные данные';
                errorMessage.style.display = 'block'; 
            }
        } catch (error) {
            console.error('Ошибка при запросе:', error);
            errorMessage.textContent = 'Неверные данные';
            errorMessage.style.display = 'block';
        }
    });
</script>
</body>

</html>