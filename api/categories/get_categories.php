<?php
require_once '../../config/database.php';

try {
    $stmt = $pdo->prepare("SELECT id, category_name FROM categories");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'categories' => $categories
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка базы данных: ' . $e->getMessage()
    ]);
}
?>
