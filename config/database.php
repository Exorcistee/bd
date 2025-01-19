<?php
$host = 'localhost';
$dbname = 'project';
$user = 'adm';
$password = '12345';
$port = '5432'; 

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";

    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
    exit;
}
?>