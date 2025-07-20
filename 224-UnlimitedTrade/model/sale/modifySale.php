<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['sale_number'])) {
  $saleNumber = htmlentities($_POST['sale_number']);
  $productName = htmlentities($_POST['product_name']);
  $customerName = htmlentities($_POST['customer_name']);
  $saleQuantity = htmlentities($_POST['sale_quantity']);
  $unitPrice = htmlentities($_POST['unit_price']);
  $discount = htmlentities($_POST['discount']);
  $saleDate = htmlentities($_POST['sale_date']);
  $soldBy = htmlentities($_POST['sold_by']);
  $invoiceAmount = htmlentities($_POST['invoice_amount']);

  if (!empty($saleNumber) && !empty($productName) && !empty($customerName) && !empty($saleQuantity)) {

    // Sanitize sale number
    if ($saleNumber === '') {
      $_SESSION['error_message'] = "Sale number not provided";
      header("Location: ../../vue/sale.php");
      exit();
    };

    // Validate sale quantity. It has to be a number
    if (filter_var($saleQuantity, FILTER_VALIDATE_INT) === 0 || filter_var($saleQuantity, FILTER_VALIDATE_INT)) {
      // the quantity is valid
    } else {
      $_SESSION['error_message'] = "Quantity provided is not valid";
      header("Location: ../../vue/sale.php");
      exit();
    }

    // Validate unit price. It has to be a number or floating point value
    if (filter_var($unitPrice, FILTER_VALIDATE_FLOAT) === 0.0 || filter_var($unitPrice, FILTER_VALIDATE_FLOAT)) {
      // Valid float (unit price)
    } else {
      $_SESSION['error_message'] = "Unit price provided is not valid";
      header("Location: ../../vue/sale.php");
      exit();
    }

    // Validate discount if it's provided
    if (!empty($discount)) {
      if (filter_var($discount, FILTER_VALIDATE_FLOAT) === false) {
        $_SESSION['error_message'] = "Discount provided not valid";
        header("Location: ../../vue/sale.php");
        exit();
      }
    }

    // Check if the sale exists
    $saleCheckSql = "SELECT * FROM sale WHERE sale_number = :sale_number";
    $saleCheckStatement = $pdo->prepare($saleCheckSql);
    $saleCheckStatement->execute(['sale_number' => $saleNumber]);
    $sale = $saleCheckStatement->fetch(PDO::FETCH_ASSOC);

    if (!$sale) {
      $_SESSION['error_message'] = "Sale not found";
      header("Location: ../../vue/sale.php");
      exit();
    }

    // Calculate the stock difference
    $previousSaleQuantity = $sale['sale_quantity'];
    $stockDifference = $previousSaleQuantity - $saleQuantity;

    // Update the sale
    $updateSaleSql = "UPDATE sale SET product_name = :product_name, customer_name = :customer_name, sale_quantity = :sale_quantity, unit_price = :unit_price, discount = :discount, sale_date = :sale_date, sold_by = :sold_by, invoice_amount = :invoice_amount WHERE sale_number = :sale_number";
    $updateSaleStatement = $pdo->prepare($updateSaleSql);
    $updateSaleStatement->execute([
      'product_name' => $productName,
      'customer_name' => $customerName,
      'sale_quantity' => $saleQuantity,
      'unit_price' => $unitPrice,
      'discount' => $discount,
      'sale_date' => $saleDate,
      'sold_by' => $soldBy,
      'invoice_amount' => $invoiceAmount,
      'sale_number' => $saleNumber
    ]);

    // Update product quantity
    $updateProductSql = "UPDATE product SET product_quantity = product_quantity + :stock_difference WHERE product_name = :product_name";
    $updateProductStatement = $pdo->prepare($updateProductSql);
    $updateProductStatement->execute(['stock_difference' => $stockDifference, 'product_name' => $productName]);

    $_SESSION['success_message'] = "Sale modified successfully";
    header("Location: ../../vue/sale.php");
    exit();

  } else {
    $_SESSION['error_message'] = "Please fill all fields";
    header("Location: ../../vue/sale.php");
    exit();
  }
}
?>
