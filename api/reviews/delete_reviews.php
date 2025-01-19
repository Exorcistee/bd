<?php
require_once '../../config/database.php';
session_start();

if ($_SESSION['role_id'] !== 3) {
    echo json_encode(['success' => false, 'message' => 'Нет доступа']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$reviewId = $data['id'] ?? null;

if (!$reviewId) {
    echo json_encode(['success' => false, 'message' => 'ID отзыва не указан.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = :id");
    $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Отзыв успешно удален.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Отзыв с таким ID не найден.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>