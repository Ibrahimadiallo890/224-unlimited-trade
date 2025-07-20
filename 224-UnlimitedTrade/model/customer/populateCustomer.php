<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['customer_number'])) {
    $customerNumber = $_POST['customer_number'];

    // Prepare SQL statement to fetch product details based on product number
    $sql = "SELECT full_name, business_name, email, phone_number, address, country, customer_type, customer_status FROM customer WHERE customer_number = :customer_number";

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['customer_number' => $customerNumber]);

    // Fetch the product details
    $customerDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customerDetails) {
        echo json_encode($customerDetails);
    } else {
        echo json_encode(['error' => 'Customer not found']);
    }
} 
else {
    echo json_encode(['error' => 'Customer number not provided']);
}
