<?php
require_once '../../config/database.php';
session_start();

if ($_SESSION['role_id'] !== 3) {
    echo json_encode(['success' => false, 'message' => 'Нет доступа']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$categoryId = $data['id'] ?? null;

if (!$categoryId) {
    echo json_encode(['success' => false, 'message' => 'ID категории не указан.']);
    exit;
}

try {

    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $pdo->prepare("SELECT setval('categories_id_seq', (SELECT MAX(id) FROM categories))");
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Категория успешно удалена.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Категория с таким ID не найдена.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>