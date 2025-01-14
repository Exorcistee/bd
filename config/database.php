<?php
$host = 'localhost';
$dbname = 'project';
$user = 'adm';
$password = '12345';
$port = '5432'; // Стандартный порт для PostgreSQL

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    // Создаём PDO экземпляр
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    // Проверка соединения
    // echo "Соединение с базой данных установлено.";
} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
    exit;
}
?>