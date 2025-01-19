<?php
require_once '../../config/database.php'; 

session_start(); 
header("Content-Type: application/json; charset=UTF-8");

if (isset($_GET['login']) && isset($_GET['password'])) {
    $login = $_GET['login'];
    $password = $_GET['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");
        $stmt->execute([':login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['password'] === $password) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['cart_id'] = $user['cart_id'];

                echo json_encode([
                    "success" => true,
                    "message" => "Вход выполнен успешно"
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Неверный пароль"
                ]);
            }
        } else {

            echo json_encode([
                "success" => false,
                "message" => "Нет такого пользователя"
            ]);
        }
    } catch (PDOException $e) {

        echo json_encode([
            "success" => false,
            "message" => "Ошибка сервера: " . $e->getMessage()
        ]);
    }
} else {

    echo json_encode([
        "success" => false,
        "message" => "Не переданы логин или пароль"
    ]);
}
?>
