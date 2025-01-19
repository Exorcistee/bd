<?php
require_once '../../config/database.php';
session_start();

if ($_SESSION['role_id'] !== 3) {
    echo json_encode(['success' => false, 'message' => 'Нет доступа']);
    exit;
}

try {
    $stmt = $pdo->query("SELECT id, name, description, price FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'products' => $products]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
