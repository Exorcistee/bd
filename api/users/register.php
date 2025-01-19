<?php
require_once '../../config/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? null;
$password = $data['password'] ?? null;

if (!$username || !$password) {
    echo json_encode(['success' => false, 'message' => 'Все поля обязательны для заполнения.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $exists = $stmt->fetchColumn();

    if ($exists) {
        echo json_encode(['success' => false, 'message' => 'Логин уже занят.']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO users (name, login, password, role_id) VALUES (:username, :username, :password, 1)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Регистрация успешна.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>