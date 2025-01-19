<?php
require_once '../../config/database.php';
session_start();

if ($_SESSION['role_id'] !== 3) {
    echo json_encode(['success' => false, 'message' => 'Нет доступа']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$categoryName = $data['category_name'] ?? '';

if (empty($categoryName)) {
    echo json_encode(['success' => false, 'message' => 'Название категории не может быть пустым.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (:category_name)");
    $stmt->bindParam(':category_name', $categoryName, PDO::PARAM_STR);
    $stmt->execute();

    $newCategoryId = $pdo->lastInsertId();
    echo json_encode([
        'success' => true,
        'message' => 'Категория успешно добавлена.',
        'category' => [
            'id' => $newCategoryId,
            'category_name' => $categoryName,
        ],
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>