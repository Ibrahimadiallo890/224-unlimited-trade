<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['loan_number'])) {
    $loanNumber = $_POST['loan_number'];

    // Prepare SQL statement to fetch loan details based on loan number
    $sql = "SELECT product_name, customer_name, loan_quantity, unit_price, discount, loan_date, loaned_by, invoice_amount FROM loan WHERE loan_number = :loan_number";

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['loan_number' => $loanNumber]);

    // Fetch the loan details
    $loanDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($loanDetails) {
        echo json_encode($loanDetails);
    } else {
        echo json_encode(['error' => 'Loan not found']);
    }
} 
else {
    echo json_encode(['error' => 'Loan number not provided']);
}
