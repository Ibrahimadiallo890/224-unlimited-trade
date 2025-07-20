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

  if (!empty($loanNumber) && ($productName) && ($customerName) && ($loanQuantity)) {

    // Sanitize loan number
    if ($loanNumber === '') {
      $_SESSION['error_message'] = "Loan number not provide";
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

    // Check if loan_number already exists
    $loanNumberCheckSql = "SELECT COUNT(*) as count FROM loan WHERE loan_number = :loan_number";
    $loanNumberCheckStatement = $pdo->prepare($loanNumberCheckSql);
    $loanNumberCheckStatement->execute(['loan_number' => $loanNumber]);
    $loanNumberCount = $loanNumberCheckStatement->fetchColumn();

    if ($loanNumberCount > 0) {
      $_SESSION['error_message'] = "Loan number already exists. Please proceed to update.";
      header("Location: ../../vue/loans.php");
      exit();
    }

    // Calculate the stock value
    $stockSql = "SELECT product_quantity FROM product WHERE product_name = :product_name";
    $stockStatement = $pdo->prepare($stockSql);
    $stockStatement->execute(['product_name' => $productName]);

    if ($stockStatement->rowCount() > 0) {
      // product is in the DB and loan is possible
      $row = $stockStatement->fetch(PDO::FETCH_ASSOC);
      $currentQuantity = $row['product_quantity'];

      if ($currentQuantity <= 0) {
        // stock is empty
        $_SESSION['error_message'] = "Impossible to loan this product. Stock is empty";
        header("Location: ../../vue/loans.php");
        exit();
      } elseif ($currentQuantity < $loanQuantity) {
        $_SESSION['error_message'] = "Not enough stock available to make this loan";
        header("Location: ../../vue/loans.php");
        exit();
      } else {
        $newQuantity = $currentQuantity - $loanQuantity;

        // check if customer is in the database
        $customerSql = "SELECT * FROM customer WHERE full_name = :full_name";
        $customerStatement = $pdo->prepare($customerSql);
        $customerStatement->execute(['full_name' => $customerName]);

        if ($customerStatement->rowCount() > 0) {
          // customer and product exist in DB
          $customerRow = $customerStatement->fetch(PDO::FETCH_ASSOC);
          $customerExist = $customerRow['full_name'];

          // insert into loan
          $insertLoan = "INSERT INTO loan (loan_number, product_name, customer_name, loan_quantity, unit_price, discount, loan_date, loaned_by, invoice_amount) VALUES (:loan_number, :product_name, :customer_name, :loan_quantity, :unit_price, :discount, :loan_date, :loaned_by, :invoice_amount)";
          $insertLoanStatement = $pdo->prepare($insertLoan);

          $insertLoanStatement->execute(['loan_number' => $loanNumber, 'product_name' => $productName, 'customer_name' => $customerName, 'loan_quantity' => $loanQuantity, 'unit_price' => $unitPrice, 'discount' => $discount, 'loan_date' => $loanDate, 'loaned_by' => $loanedBy, 'invoice_amount' => $invoiceAmount]);

          // update the stock in the product table
          $stockUpdateSql = "UPDATE product SET product_quantity = :product_quantity WHERE product_name = :product_name";
          $stockUpdateStatement = $pdo->prepare($stockUpdateSql);
          $stockUpdateStatement->execute(['product_quantity' => $newQuantity, 'product_name' => $productName]);

          $_SESSION['success_message'] = "Product loaned successfully";
          header("Location: ../../vue/loans.php");
          exit();
        } else {
          $_SESSION['error_message'] = "Customer does not exist in DB";
          header("Location: ../../vue/loans.php");
          exit();
        }
      }
    } else {
      $_SESSION['error_message'] = "Product does not exist in DB";
      header("Location: ../../vue/loans.php");
      exit();
    }
  } else {
    $_SESSION['error_message'] = "Please fill all fields";
    header("Location: ../../vue/loans.php");
    exit();
  }
}
