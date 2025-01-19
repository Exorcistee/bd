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

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "ID заказа не передан или некорректен"
    ]);
    exit;
}

$orderId = intval($_GET['order_id']);
$userId = $_SESSION['user_id'];

try {

    $stmt = $pdo->prepare("SELECT id FROM orders WHERE id = :order_id AND user_id = :user_id");
    $stmt->execute([':order_id' => $orderId, ':user_id' => $userId]);

    if (!$stmt->fetch()) {
        echo json_encode([
            "success" => false,
            "message" => "Заказ не найден или доступ запрещён"
        ]);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT p.id, p.name, ohp.quantity, ohp.price
        FROM order_has_product ohp
        JOIN products p ON ohp.product_id = p.id
        WHERE ohp.order_id = :order_id
    ");
    $stmt->execute([':order_id' => $orderId]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as &$product) {
        $stmtImages = $pdo->prepare("SELECT image_url FROM product_images WHERE product_id = :product_id");
        $stmtImages->execute([':product_id' => $product['id']]);
        $product['images'] = $stmtImages->fetchAll(PDO::FETCH_COLUMN); // Получаем массив URL изображений
    }

    echo json_encode([
        "success" => true,
        "products" => $products
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Ошибка базы данных: " . $e->getMessage()
    ]);
}
?>
