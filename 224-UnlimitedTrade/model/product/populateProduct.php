<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['product_number'])) {
    $productNumber = $_POST['product_number'];

    // Prepare SQL statement to fetch product details based on product number
    $sql = "SELECT product_name, category_name, product_quantity, unit_price, discount, manufacturing_date, expiration_date, product_status FROM product WHERE product_number = :product_number";

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['product_number' => $productNumber]);

    // Fetch the product details
    $productDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($productDetails) {
        echo json_encode($productDetails);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
} 
else {
    echo json_encode(['error' => 'Product number not provided']);
}
