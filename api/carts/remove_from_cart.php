<?php
require_once '../../config/database.php';
session_start();

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Пользователь не авторизован"
    ]);
    exit;
}

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$productId = $input['product_id'] ?? null;

if (!$productId || !is_numeric($productId)) {
    echo json_encode([
        "success" => false,
        "message" => "Некорректный идентификатор товара"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        DELETE FROM cart_has_items
        WHERE cart_id = (SELECT id FROM carts WHERE user_id = :user_id)
        AND product_id = :product_id
    ");
    $stmt->execute([
        ':user_id' => $userId,
        ':product_id' => $productId
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Товар удален из корзины"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Ошибка базы данных: " . $e->getMessage()
    ]);
}