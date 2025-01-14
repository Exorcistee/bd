<?php
require_once '../../config/database.php'; // Подключение к базе данных

session_start(); // Запуск сессии
header("Content-Type: application/json; charset=UTF-8");

// Проверяем, есть ли логин и пароль в GET-запросе
if (isset($_GET['login']) && isset($_GET['password'])) {
    $login = $_GET['login'];
    $password = $_GET['password'];

    try {
        // Проверяем, существует ли пользователь с таким логином
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");
        $stmt->execute([':login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Если пользователь найден, проверяем пароль
            if ($user['password'] === $password) {
                // Успешный вход: сохраняем данные пользователя в сессии
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['cart_id'] = $user['cart_id'];

                echo json_encode([
                    "success" => true,
                    "message" => "Вход выполнен успешно"
                ]);
            } else {
                // Неверный пароль
                echo json_encode([
                    "success" => false,
                    "message" => "Неверный пароль"
                ]);
            }
        } else {
            // Если пользователь не найден
            echo json_encode([
                "success" => false,
                "message" => "Нет такого пользователя"
            ]);
        }
    } catch (PDOException $e) {
        // Ошибка при работе с базой данных
        echo json_encode([
            "success" => false,
            "message" => "Ошибка сервера: " . $e->getMessage()
        ]);
    }
} else {
    // Если логин или пароль не переданы
    echo json_encode([
        "success" => false,
        "message" => "Не переданы логин или пароль"
    ]);
}
?>
