<?php
require_once '../config/config.php';

echo "<h1>Installing Database...</h1>";

try {
    $dsn = "mysql:host=" . DB_HOST;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents('../database/schema.sql');



    echo "<p>Please import <strong>database/schema.sql</strong> using phpMyAdmin or MySQL Workbench.</p>";
    echo "<p>Database Host: " . DB_HOST . "</p>";
    echo "<p>Database Name: " . DB_NAME . "</p>";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
