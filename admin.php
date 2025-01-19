<?php
session_start();

// Проверка, является ли пользователь администратором
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 3) {
    header("Location: /bd/login.php");
    exit;
}

require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="/bd/styles/admin_style.css">
</head>
<body>
    <header>
        <h1>Админ-панель</h1>
        <nav>
            <a href="/bd/index.php">Главная</a>
            <a href="/bd/logout.php">Выйти</a>
        </nav>
    </header>

    <main class="admin-container">

        <section>
            <h2>Добавить товар</h2>
            <form id="add-product-form" class="admin-container" action="add_product.php" method="POST" enctype="multipart/form-data">
                <label for="product-name">Название:</label>
                <input type="text" id="product-name" name="name" placeholder="Введите название товара" required>

                <label for="product-description">Описание:</label>
                <textarea id="product-description" name="description" placeholder="Введите описание товара" required></textarea>

                <label for="product-price">Цена:</label>
                <input type="number" id="product-price" name="price" placeholder="Введите цену" step="0.01" required>

                <label for="product-brand">Бренд:</label>
                <input type="text" id="product-brand" name="brand" placeholder="Введите бренд товара" required>

                <label for="product-specifications">Характеристики:</label>
                <textarea id="product-specifications" name="specifications" placeholder="Введите характеристики товара" required></textarea>

                <label for="product-category">Категория:</label>
                <select id="product-category" name="category_id" required>
                    <option value="" disabled selected>Выберите категорию</option>
                </select>

                <label for="product-image">Выберите изображение:</label>
<input type="file" id="product-image" name="product_image" accept="image/*" required>

                <button type="submit">Добавить товар</button>
            </form>
        </section>

        <!-- Список товаров -->
        <section>
            <h2>Список товаров</h2>
            <table id="products-table" class="orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Цена</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </section>

        <!-- Категории -->
        <section>
            <h2>Добавить категорию</h2>
            <form id="add-category-form" class="admin-container">
                <label for="category-name">Название категории:</label>
                <input type="text" id="category-name" name="category_name" placeholder="Введите название категории" required>
                <button type="submit">Добавить категорию</button>
            </form>

            <h2>Список категорий</h2>
            <table id="categories-table" class="orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </section>

        <!-- Отзывы -->
        <section>
            <h2>Список отзывов</h2>
            <table id="reviews-table" class="orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID пользователя</th>
                        <th>ID товара</th>
                        <th>Рейтинг</th>
                        <th>Текст отзыва</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </section>
        <section>
    <h2>Список пользователей</h2>
    <table id="users-table" class="orders-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Логин</th>
                <th>Пароль</th>
                <th>Роль</th>
                <th>Корзина</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</section>
    </main>


    <script src="/bd/scripts/admin_script.js"></script>
</body>
</html>