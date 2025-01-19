<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Вы не авторизованы.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$deliveryAddress = $data['delivery_address'] ?? null;

if (!$deliveryAddress) {
    echo json_encode(['success' => false, 'message' => 'Адрес доставки обязателен.']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
    INSERT INTO orders (user_id, status, delivery_address, created_at) 
    VALUES (:user_id, 'Ожидание', :delivery_address, NOW())
");
$stmt->bindParam(':user_id', $userId);
$stmt->bindParam(':delivery_address', $deliveryAddress);
$stmt->execute();

    $orderId = $pdo->lastInsertId();

    $cartStmt = $pdo->prepare("
        SELECT chi.product_id, chi.quantity, p.price
        FROM cart_has_items chi
        INNER JOIN products p ON chi.product_id = p.id
        WHERE chi.cart_id = (SELECT id FROM carts WHERE user_id = :user_id)
    ");
    $cartStmt->bindParam(':user_id', $userId);
    $cartStmt->execute();
    $cartItems = $cartStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        echo json_encode(['success' => false, 'message' => 'Корзина пуста.']);
        exit;
    }

    $orderProductStmt = $pdo->prepare("
        INSERT INTO order_has_product (order_id, product_id, quantity, price) 
        VALUES (:order_id, :product_id, :quantity, :price)
    ");
    foreach ($cartItems as $item) {
        $orderProductStmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $item['product_id'],
            ':quantity' => $item['quantity'],
            ':price' => $item['price']
        ]);
    }

    $clearCartStmt = $pdo->prepare("
        DELETE FROM cart_has_items WHERE cart_id = (SELECT id FROM carts WHERE user_id = :user_id)
    ");
    $clearCartStmt->bindParam(':user_id', $userId);
    $clearCartStmt->execute();

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Заказ успешно оформлен.']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Ошибка оформления заказа: ' . $e->getMessage()]);
}
?>