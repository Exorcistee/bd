<?php
require_once '../../config/database.php';
session_start();

if ($_SESSION['role_id'] !== 3) {
    echo json_encode(['success' => false, 'message' => 'Нет доступа']);
    exit;
}

$productId = json_decode(file_get_contents('php://input'), true)['product_id'];

if (!$productId || !is_numeric($productId)) {
    echo json_encode(['success' => false, 'message' => 'Некорректный идентификатор товара']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $productId]);
    echo json_encode(['success' => true, 'message' => 'Товар удален']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
