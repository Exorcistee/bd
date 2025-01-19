<?php
require_once '../../config/database.php';
session_start();

if ($_SESSION['role_id'] !== 3) {
    echo json_encode(['success' => false, 'message' => 'Нет доступа']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['id'] ?? null;

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'ID пользователя не указан.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Пользователь успешно удален.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>