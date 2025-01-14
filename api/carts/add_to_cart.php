<?php
require_once '../../config/database.php';
session_start();

header("Content-Type: application/json; charset=UTF-8");

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Пользователь не авторизован"
    ]);
    exit;
}

$userId = $_SESSION['user_id'];
$productId = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1; // Если количество не указано, используем 1

// Проверяем входные данные
if (!$productId || !is_numeric($productId)) {
    echo json_encode([
        "success" => false,
        "message" => $productId
    ]);
    exit;
}

if (!is_numeric($quantity) || $quantity <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Некорректное количество"
    ]);
    exit;
}

try {
    // Получаем корзину пользователя или создаём новую
    $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $userId]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        // Создаём новую корзину, если её нет
        $stmt = $pdo->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
        $stmt->execute([':user_id' => $userId]);
        $cartId = $pdo->lastInsertId();
    } else {
        $cartId = $cart['id'];
    }

    // Проверяем, есть ли уже этот товар в корзине
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart_has_items WHERE cart_id = :cart_id AND product_id = :product_id");
    $stmt->execute([':cart_id' => $cartId, ':product_id' => $productId]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        // Если товар уже есть, обновляем количество
        $newQuantity = $item['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart_has_items SET quantity = :quantity WHERE id = :id");
        $stmt->execute([':quantity' => $newQuantity, ':id' => $item['id']]);
    } else {
        // Если товара нет, добавляем его в корзину
        $stmt = $pdo->prepare("INSERT INTO cart_has_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");
        $stmt->execute([
            ':cart_id' => $cartId,
            ':product_id' => $productId,
            ':quantity' => $quantity
        ]);
    }

    echo json_encode([
        "success" => true,
        "message" => "Товар добавлен в корзину"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Ошибка базы данных: " . $e->getMessage()
    ]);
}