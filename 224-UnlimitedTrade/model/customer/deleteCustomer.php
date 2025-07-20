<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['customer_number'])) {
  $customerNumber = htmlentities($_POST['customer_number']);

  // Sanitize product number
  if ($customerNumber === '') {
    $_SESSION['error_message'] = "Customer number not provided";
    header("Location: ../../vue/customer.php");
    exit();
  }

  // Check if customerNumber is in the DB
  $checkCustomerNumber = "SELECT customer_number FROM customer WHERE customer_number = :customer_number";

  $checkCustomerNumberStatement = $pdo->prepare($checkCustomerNumber);
  $checkCustomerNumberStatement->execute(['customer_number' => $customerNumber]);

  if ($checkCustomerNumberStatement->rowCount() > 0) {

    // Start the deletion process
    $deleteCustomer = "DELETE FROM customer WHERE customer_number = :customer_number";

    $deleteCustomerStatement = $pdo->prepare($deleteCustomer);
    $deleteCustomerStatement->execute(['customer_number' => $customerNumber]);

    $_SESSION['success_message'] = "Customer deleted successfully";
    header("Location: ../../vue/customer.php");
    exit();
  } else {
    $_SESSION['error_message'] = "Customer does not exist";
    header("Location: ../../vue/customer.php");
    exit();
  }
} else {
  $_SESSION['error_message'] = "Customer number not provided";
  header("Location: ../../vue/customer.php");
  exit();
}
