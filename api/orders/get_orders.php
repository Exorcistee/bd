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
    $stmt = $pdo->prepare("SELECT id, status, created_at FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute([':user_id' => $userId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "orders" => $orders
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Ошибка базы данных: " . $e->getMessage()
    ]);
}
?>