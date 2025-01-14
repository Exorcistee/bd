<?php
require_once '../../config/database.php';

header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'], $data['name'], $data['description'], $data['price'])) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id");
    $stmt->execute([
        ':id' => $data['id'],
        ':name' => $data['name'],
        ':description' => $data['description'],
        ':price' => $data['price']
    ]);

    echo json_encode(["success" => true, "message" => "Product updated"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>