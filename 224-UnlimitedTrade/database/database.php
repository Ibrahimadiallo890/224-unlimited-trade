<?php
session_start();

try {
    // Set database connection parameters
    $host = 'localhost';
    $database = '224_unlimited_trade';
    $user = 'root';
    $password = '';

    // Establish a connection using PDO
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);

    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Set fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} 
catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
