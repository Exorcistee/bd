<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /bd/login.php");
    exit;
}

$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/bd/images/';

if (!is_dir($uploadDir)) {
    die(json_encode(['success' => false, 'message' => 'Папка для загрузки не существует.']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $brand = $_POST['brand'];
    $specifications = $_POST['specifications'];
    $categoryId = $_POST['category_id'];

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['product_image']['tmp_name'];
        $imageName = $_FILES['product_image']['name'];
        $imageType = $_FILES['product_image']['type'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageType, $allowedTypes)) {
            die(json_encode(['success' => false, 'message' => 'Неверный тип изображения. Разрешены только JPG, PNG и GIF.']));
        }

        $imagePath = $uploadDir . basename($imageName);

        if (!move_uploaded_file($imageTmpPath, $imagePath)) {
            die(json_encode(['success' => false, 'message' => 'Ошибка при загрузке изображения.']));
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, brand, specifications, category_id) 
                                   VALUES (:name, :description, :price, :brand, :specifications, :category_id)");
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':brand' => $brand,
                ':specifications' => $specifications,
                ':category_id' => $categoryId
            ]);

            $productId = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_url) 
                                   VALUES (:product_id, :image_url)");
            $stmt->execute([
                ':product_id' => $productId,
                ':image_url' => '/bd/images/' . basename($imageName)
            ]);

            echo json_encode(['success' => true, 'message' => 'Товар успешно добавлен.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Изображение не было загружено.']);
    }
}
?>