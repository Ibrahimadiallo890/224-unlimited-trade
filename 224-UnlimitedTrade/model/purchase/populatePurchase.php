<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['purchase_number'])) {
    $purchaseNumber = $_POST['purchase_number'];

    // Prepare SQL statement to fetch purchase details based on purchase number
    $sql = "SELECT product_name, company_name, purchase_quantity, unit_price, discount, purchase_date, purchased_by, invoice_amount FROM purchase WHERE purchase_number = :purchase_number";

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['purchase_number' => $purchaseNumber]);

    // Fetch the purchase details
    $purchaseDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($purchaseDetails) {
        echo json_encode($purchaseDetails);
    } else {
        echo json_encode(['error' => 'Purchase not found']);
    }
} 
else {
    echo json_encode(['error' => 'Purchase number not provided']);
}
