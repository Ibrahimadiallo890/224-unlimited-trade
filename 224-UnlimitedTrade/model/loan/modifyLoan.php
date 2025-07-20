<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['loan_number'])) {
  $loanNumber = htmlentities($_POST['loan_number']);
  $productName = htmlentities($_POST['product_name']);
  $customerName = htmlentities($_POST['customer_name']);
  $loanQuantity = htmlentities($_POST['loan_quantity']);
  $unitPrice = htmlentities($_POST['unit_price']);
  $discount = htmlentities($_POST['discount']);
  $loanDate = htmlentities($_POST['loan_date']);
  $loanedBy = htmlentities($_POST['loaned_by']);
  $invoiceAmount = htmlentities($_POST['invoice_amount']);

  if (!empty($loanNumber) && !empty($productName) && !empty($customerName) && !empty($loanQuantity)) {

    // Sanitize loan number
    if ($loanNumber === '') {
      $_SESSION['error_message'] = "Loan number not provided";
      header("Location: ../../vue/loans.php");
      exit();
    };

    // Validate loan quantity. It has to be a number
    if (filter_var($loanQuantity, FILTER_VALIDATE_INT) === 0 || filter_var($loanQuantity, FILTER_VALIDATE_INT)) {
      // the quantity is valid
    } else {
      $_SESSION['error_message'] = "Quantity provided is not valid";
      header("Location: ../../vue/loans.php");
      exit();
    }

    // Validate unit price. It has to be a number or floating point value
    if (filter_var($unitPrice, FILTER_VALIDATE_FLOAT) === 0.0 || filter_var($unitPrice, FILTER_VALIDATE_FLOAT)) {
      // Valid float (unit price)
    } else {
      $_SESSION['error_message'] = "Unit price provided is not valid";
      header("Location: ../../vue/loans.php");
      exit();
    }

    // Validate discount if it's provided
    if (!empty($discount)) {
      if (filter_var($discount, FILTER_VALIDATE_FLOAT) === false) {
        $_SESSION['error_message'] = "Discount provided not valid";
        header("Location: ../../vue/loans.php");
        exit();
      }
    }

    // Check if the loan exists
    $loanCheckSql = "SELECT * FROM loan WHERE loan_number = :loan_number";
    $loanCheckStatement = $pdo->prepare($loanCheckSql);
    $loanCheckStatement->execute(['loan_number' => $loanNumber]);
    $loan = $loanCheckStatement->fetch(PDO::FETCH_ASSOC);

    if (!$loan) {
      $_SESSION['error_message'] = "Loan not found";
      header("Location: ../../vue/loans.php");
      exit();
    }

    // Calculate the stock difference
    $previousLoanQuantity = $loan['loan_quantity'];
    $stockDifference = $previousLoanQuantity - $loanQuantity;

    // Update the loan
    $updateLoanSql = "UPDATE loan SET product_name = :product_name, customer_name = :customer_name, loan_quantity = :loan_quantity, unit_price = :unit_price, discount = :discount, loan_date = :loan_date, loaned_by = :loaned_by, invoice_amount = :invoice_amount WHERE loan_number = :loan_number";
    $updateLoanStatement = $pdo->prepare($updateLoanSql);
    $updateLoanStatement->execute([
      'product_name' => $productName,
      'customer_name' => $customerName,
      'loan_quantity' => $loanQuantity,
      'unit_price' => $unitPrice,
      'discount' => $discount,
      'loan_date' => $loanDate,
      'loaned_by' => $loanedBy,
      'invoice_amount' => $invoiceAmount,
      'loan_number' => $loanNumber
    ]);

    // Update product quantity
    $updateProductSql = "UPDATE product SET product_quantity = product_quantity + :stock_difference WHERE product_name = :product_name";
    $updateProductStatement = $pdo->prepare($updateProductSql);
    $updateProductStatement->execute(['stock_difference' => $stockDifference, 'product_name' => $productName]);

    $_SESSION['success_message'] = "Loan modified successfully";
    header("Location: ../../vue/loans.php");
    exit();

  } else {
    $_SESSION['error_message'] = "Please fill all fields";
    header("Location: ../../vue/loans.php");
    exit();
  }
}
?>
