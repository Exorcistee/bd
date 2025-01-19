<?php
require_once '../../config/database.php';
session_start();

if ($_SESSION['role_id'] !== 3) {
    echo json_encode(['success' => false, 'message' => 'Нет доступа']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['id'] ?? null;
$newRoleId = $data['role_id'] ?? null;

if (!$userId || !$newRoleId) {
    echo json_encode(['success' => false, 'message' => 'ID пользователя или ID роли не указаны.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE users SET role_id = :role_id WHERE id = :id");
    $stmt->bindParam(':role_id', $newRoleId, PDO::PARAM_INT);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Роль пользователя успешно обновлена.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>