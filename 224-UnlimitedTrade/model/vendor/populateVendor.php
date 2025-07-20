<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['vendor_number'])) {
    $vendorNumber = $_POST['vendor_number'];

    // Prepare SQL statement to fetch product details based on product number
    $sql = "SELECT full_name, company_name, email, phone_number, address, country, vendor_type, vendor_status FROM vendor WHERE vendor_number = :vendor_number";

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['vendor_number' => $vendorNumber]);

    // Fetch the product details
    $vendorDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vendorDetails) {
        echo json_encode($vendorDetails);
    } else {
        echo json_encode(['error' => 'Vendor not found']);
    }
} 
else {
    echo json_encode(['error' => 'Vendor number not provided']);
}
