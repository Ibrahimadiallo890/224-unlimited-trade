<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['purchase_number'])) {
  $PurchaseNumber = htmlentities($_POST['purchase_number']);
  $productName = htmlentities($_POST['product_name']);
  $companyName = htmlentities($_POST['company_name']);
  $purchaseQuantity = htmlentities($_POST['purchase_quantity']);
  $unitPrice = htmlentities($_POST['unit_price']);
  $discount = htmlentities($_POST['discount']);
  $purchaseDate = htmlentities($_POST['purchase_date']);
  $purchasedBy = htmlentities($_POST['purchased_by']);
  $invoiceAmount = htmlentities($_POST['invoice_amount']);

  $initialStock = 0;
  $newStock = 0;

  if (!empty($PurchaseNumber) && ($productName) && ($companyName)) {

    // Sanitize purchase number
    if ($PurchaseNumber === '') {
      $_SESSION['error_message'] = "Purchase number not provide";
      header("Location: ../../vue/purchase.php");
      exit();
    };

    // Validate purchase quantity. It has to be a number
    if (filter_var($purchaseQuantity, FILTER_VALIDATE_INT) === 0 || filter_var($purchaseQuantity, FILTER_VALIDATE_INT)) {
      // the quantity is valid
    } else {
      $_SESSION['error_message'] = "Quantity provided is not valid";
      header("Location: ../../vue/purchase.php");
      exit();
    }

    // Validate unit price. It has to be a number or floating point value
    if (filter_var($unitPrice, FILTER_VALIDATE_FLOAT) === 0.0 || filter_var($unitPrice, FILTER_VALIDATE_FLOAT)) {
      // Valid float (unit price)
    } else {
      $_SESSION['error_message'] = "Unit price provided is not valid";
      header("Location: ../../vue/purchase.php");
      exit();
    }

    // Validate discount if it's provided
    if (!empty($discount)) {
      if (filter_var($discount, FILTER_VALIDATE_FLOAT) === false) {
        $_SESSION['error_message'] = "Discount provided not valid";
        header("Location: ../../vue/purchase.php");
        exit();
      }
    }

    // Check if purchase_number already exists
    $purchaseNumberCheckSql = "SELECT COUNT(*) as count FROM purchase WHERE purchase_number = :purchase_number";
    $purchaseNumberCheckStatement = $pdo->prepare($purchaseNumberCheckSql);
    $purchaseNumberCheckStatement->execute(['purchase_number' => $PurchaseNumber]);
    $purchaseNumberCount = $purchaseNumberCheckStatement->fetchColumn();

    if ($purchaseNumberCount > 0) {
      $_SESSION['error_message'] = "Purchase number already exists. Please proceed to update.";
      header("Location: ../../vue/purchase.php");
      exit();
    }

    // Check if the item exists in item table and 
    // calculate the stock values and update to match the new purchase quantity
    $stockSql = "SELECT product_quantity FROM product WHERE product_name = :product_name";
    $stockStatement = $pdo->prepare($stockSql);
    $stockStatement->execute(['product_name' => $productName]);

    if ($stockStatement->rowCount() > 0) {
      // check if vendor company name is in the database
      $vendorSql = "SELECT * FROM vendor WHERE company_name = :company_name";
      $vendorStatement = $pdo->prepare($vendorSql);
      $vendorStatement->execute(['company_name' => $companyName]);

      // start the insertion process
      $insertPurchaseSql = "INSERT INTO purchase (purchase_number, product_name, company_name, purchase_quantity, unit_price, discount, purchase_date, purchased_by, invoice_amount) VALUES (:purchase_number, :product_name, :company_name, :purchase_quantity, :unit_price, :discount, :purchase_date, :purchased_by, :invoice_amount)";
      $insertPurchaseStatement = $pdo->prepare($insertPurchaseSql);

      $insertPurchaseStatement->execute(['purchase_number' => $PurchaseNumber, 'product_name' => $productName, 'company_name' => $companyName, 'purchase_quantity' => $purchaseQuantity, 'unit_price' => $unitPrice, 'discount' => $discount, 'purchase_date' => $purchaseDate, 'purchased_by' => $purchasedBy, 'invoice_amount' => $invoiceAmount]);

      // Calculate the new stock value using the existing stock in product table
      $row = $stockStatement->fetch(PDO::FETCH_ASSOC);
      $initialStock = $row['product_quantity'];
      $newStock = $initialStock + $purchaseQuantity;

      // Update the new stock value in product table
      $updateStockSql = "UPDATE product SET product_quantity = :product_quantity WHERE product_name = :product_name";
      $updateStockStatement = $pdo->prepare($updateStockSql);
      $updateStockStatement->execute(['product_quantity' => $newStock, 'product_name' => $productName]);

      $_SESSION['success_message'] = "Product purchased successfully";
      header("Location: ../../vue/purchase.php");
      exit();
    }
  } else {
    $_SESSION['error_message'] = "Please fill all fields";
    header("Location: ../../vue/purchase.php");
    exit();
  }
} 
else {
  $_SESSION['error_message'] = "Vendor details does not exist in DB";
  header("Location: ../../vue/purchase.php");
  exit();
}
