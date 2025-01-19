<?php
require_once '../../config/database.php';


try {
    $stmt = $pdo->prepare("
        SELECT users.id, users.name, users.login, users.password, users.role_id, users.cart_id, roles.role_name 
        FROM users 
        LEFT JOIN roles ON users.role_id = roles.id
    ");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $users]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>