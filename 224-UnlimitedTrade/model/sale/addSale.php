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

  if (!empty($saleNumber) && ($productName) && ($customerName) && ($saleQuantity)) {

    // Sanitize sale number
    if ($saleNumber === '') {
      $_SESSION['error_message'] = "Sale number not provide";
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

    // Check if sale_number already exists
    $saleNumberCheckSql = "SELECT COUNT(*) as count FROM sale WHERE sale_number = :sale_number";
    $saleNumberCheckStatement = $pdo->prepare($saleNumberCheckSql);
    $saleNumberCheckStatement->execute(['sale_number' => $saleNumber]);
    $saleNumberCount = $saleNumberCheckStatement->fetchColumn();

    if ($saleNumberCount > 0) {
      $_SESSION['error_message'] = "Sale number already exists. Please proceed to update.";
      header("Location: ../../vue/sale.php");
      exit();
    }

    // Calculate the stock value
    $stockSql = "SELECT product_quantity FROM product WHERE product_name = :product_name";
    $stockStatement = $pdo->prepare($stockSql);
    $stockStatement->execute(['product_name' => $productName]);

    if ($stockStatement->rowCount() > 0) {
      // product is in the DB and sale is possible
      $row = $stockStatement->fetch(PDO::FETCH_ASSOC);
      $currentQuantity = $row['product_quantity'];

      if ($currentQuantity <= 0) {
        // stock is empty
        $_SESSION['error_message'] = "Impossible to sell this product. Stock is empty";
        header("Location: ../../vue/sale.php");
        exit();
      } elseif ($currentQuantity < $saleQuantity) {
        $_SESSION['error_message'] = "Not enough stock available to make this sale";
        header("Location: ../../vue/sale.php");
        exit();
      } else {
        $newQuantity = $currentQuantity - $saleQuantity;

        // check if customer is in the database
        $customerSql = "SELECT * FROM customer WHERE full_name = :full_name";
        $customerStatement = $pdo->prepare($customerSql);
        $customerStatement->execute(['full_name' => $customerName]);

        if ($customerStatement->rowCount() > 0) {
          // customer and product exist in DB
          $customerRow = $customerStatement->fetch(PDO::FETCH_ASSOC);
          $customerExist = $customerRow['full_name'];

          // insert into sale
          $insertSale = "INSERT INTO sale (sale_number, product_name, customer_name, sale_quantity, unit_price, discount, sale_date, sold_by, invoice_amount) VALUES (:sale_number, :product_name, :customer_name, :sale_quantity, :unit_price, :discount, :sale_date, :sold_by, :invoice_amount)";
          $insertSaleStatement = $pdo->prepare($insertSale);

          $insertSaleStatement->execute(['sale_number' => $saleNumber, 'product_name' => $productName, 'customer_name' => $customerName, 'sale_quantity' => $saleQuantity, 'unit_price' => $unitPrice, 'discount' => $discount, 'sale_date' => $saleDate, 'sold_by' => $soldBy, 'invoice_amount' => $invoiceAmount]);

          // update the stock in the product table
          $stockUpdateSql = "UPDATE product SET product_quantity = :product_quantity WHERE product_name = :product_name";
          $stockUpdateStatement = $pdo->prepare($stockUpdateSql);
          $stockUpdateStatement->execute(['product_quantity' => $newQuantity, 'product_name' => $productName]);

          $_SESSION['success_message'] = "Product sold successfully";
          header("Location: ../../vue/sale.php");
          exit();
        } else {
          $_SESSION['error_message'] = "Customer does not exist in DB";
          header("Location: ../../vue/sale.php");
          exit();
        }
      }
    } else {
      $_SESSION['error_message'] = "Product does not exist in DB";
      header("Location: ../../vue/sale.php");
      exit();
    }
  } else {
    $_SESSION['error_message'] = "Please fill all fields";
    header("Location: ../../vue/sale.php");
    exit();
  }
}
