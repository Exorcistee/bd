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

try {
    $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $userId]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        echo json_encode([
            "success" => true,
            "cart" => [],
            "message" => "Корзина пуста"
        ]);
        exit;
    }

    $cartId = $cart['id'];

    $stmt = $pdo->prepare("
        SELECT 
            p.id AS product_id,
            p.name,
            p.price,
            chi.quantity,
            (p.price * chi.quantity) AS total_price,
            (SELECT image_url FROM product_images WHERE product_id = p.id LIMIT 1) AS image_url
        FROM cart_has_items chi
        JOIN products p ON chi.product_id = p.id
        WHERE chi.cart_id = :cart_id
    ");
    $stmt->execute([':cart_id' => $cartId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "cart" => $items
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Ошибка базы данных: " . $e->getMessage()
    ]);
}