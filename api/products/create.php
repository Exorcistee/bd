<?php
require_once '../../config/database.php';

header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['name'], $data['description'], $data['price'])) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price) VALUES (:name, :description, :price)");
    $stmt->execute([
        ':name' => $data['name'],
        ':description' => $data['description'],
        ':price' => $data['price']
    ]);

    echo json_encode(["success" => true, "message" => "Product added"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>