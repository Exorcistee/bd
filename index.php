<?php
session_start(); // Запуск сессии для проверки авторизации

$apiUrl = "http://localhost/bd/api/products/get.php";
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

// Проверяем, авторизован ли пользователь
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин техники</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="/bd/index.php" id="home-link">Главная</a>
            <a href="/bd/cart.php" id="cart-link">Корзина</a>
            <?php if ($isLoggedIn): ?>
                <a href="/bd/account.php" id="profile-link">Личный кабинет</a>
                <a href="/bd/logout.php" id="logout-link">Выйти</a>
            <?php else: ?>
                <a href="/bd/login.php" id="login-link">Вход</a>
            <?php endif; ?>
        </div>
    </header>
    
    <div class="main-content">
        <h1>Наши Товары</h1>
        <div id="product-list">
            <?php
            if ($data['success'] && count($data['products']) > 0) {
                foreach ($data['products'] as $product) {
                    echo '<div class="product">';
                    echo '<div class="product-images">';
                    if (!empty($product['images'])) {
                        foreach ($product['images'] as $image) {
                            echo '<img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($product['name']) . '" />';
                        }
                    } else {
                        echo '<p>Изображений нет.</p>';
                    }
                    echo '</div>';

                    echo '<div class="product-details">';
                    echo '<h2>' . htmlspecialchars($product['name']) . '</h2>';
                    echo '<p>' . htmlspecialchars($product['description']) . '</p>';
                    echo '<p class="price">Цена: ' . number_format($product['price'], 2, ',', ' ') . ' руб.</p>';
                    echo '<button class="add-to-cart-btn" onclick="addToCart(' . (int)$product['id'] . ')">Добавить в корзину</button>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Товары не найдены.</p>';
            }
            ?>
        </div>
    </div>

    <script src="/bd/scripts/index_script.js"></script>  
</body>
</html>
