<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['sale_number'])) {
    $saleNumber = $_POST['sale_number'];

    // Prepare SQL statement to fetch sale details based on sale number
    $sql = "SELECT product_name, customer_name, sale_quantity, unit_price, discount, sale_date, sold_by, invoice_amount FROM sale WHERE sale_number = :sale_number";

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['sale_number' => $saleNumber]);

    // Fetch the sale details
    $saleDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($saleDetails) {
        echo json_encode($saleDetails);
    } else {
        echo json_encode(['error' => 'Sale not found']);
    }
} 
else {
    echo json_encode(['error' => 'Sale number not provided']);
}
