<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../../config/database.php';

try {
    $stmt = $pdo->query("SELECT id, name, description, price FROM products");
    $products = $stmt->fetchAll();

    foreach ($products as &$product) {
        $stmtImages = $pdo->prepare("SELECT image_url FROM product_images WHERE product_id = :product_id");
        $stmtImages->execute([':product_id' => $product['id']]);
        $product['images'] = $stmtImages->fetchAll(PDO::FETCH_COLUMN);
    }

    echo json_encode(["success" => true, "products" => $products]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>