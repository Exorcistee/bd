<?php
require_once '../../config/database.php';
session_start();

try {

    $stmt = $pdo->prepare("SELECT id, user_id, product_id, rating, review_text FROM reviews");
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $reviews]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>