<?php
session_reset();
require_once "../../database/database.php";

if (isset($_POST['product_number'])) {
  $productNumber = htmlentities($_POST['product_number']);
  $productName = htmlentities($_POST['product_name']);
  $categoryName = htmlentities($_POST['category_name']);
  $quantity = htmlentities($_POST['product_quantity']);
  $unitPrice = htmlentities($_POST['unit_price']);
  $discount = htmlentities($_POST['discount']);
  $manufacturingDate = htmlentities($_POST['manufacturing_date']);
  $expirationDate = htmlentities($_POST['expiration_date']);
  $productStatus = htmlentities($_POST['product_status']);

  $initialStock = 0;
  $newStock = 0;

  // Check if mandatory fields are not empty
  if (!empty($productNumber) && ($productName) && ($quantity) && ($unitPrice)) {

    // Sanitize product number
    if ($productNumber === '') {
      $_SESSION['error_message'] = "Product number not provide";
      header("Location: ../../vue/product.php");
      exit();
    };

    // Validate product quantity. It has to be a number
    if (filter_var($quantity, FILTER_VALIDATE_INT) === 0 || filter_var($quantity, FILTER_VALIDATE_INT)) {
      // the quantity is valid
    } else {
      $_SESSION['error_message'] = "Quantity provided not valid";
      header("Location: ../../vue/product.php");
      exit();
    }

    // Validate unit price using regular expression
    if (preg_match('/^\d{1,3}(,\d{3})*(\.\d+)?$/', $unitPrice)) {
      // The unitPrice is valid
    } else {
      $_SESSION['error_message'] = "Unit Price provided is not valid";
      header("Location: ../../vue/product.php");
      exit();
    }

    // Validate discount if it's provided
    if (!empty($discount)) {
      if (filter_var($discount, FILTER_VALIDATE_FLOAT) === false) {
        $_SESSION['error_message'] = "Discount provided not valid";
        header("Location: ../../vue/product.php");
        exit();
      }
    }

    // Calculate the stock values
    $stockSql = "SELECT product_quantity FROM product WHERE product_number = :product_number";
    $stockStatement = $pdo->prepare($stockSql);
    $stockStatement->execute(['product_number' => $productNumber]);

    if ($stockStatement->rowCount() > 0) {
      $row = $stockStatement->fetch(PDO::FETCH_ASSOC);

      $initialStock = $row['product_quantity'];
      $newStock = $initialStock + $quantity;
    } else {
      $_SESSION['error_message'] = "Product does not exist in DB. Update is not possible";
      header("Location: ../../vue/product.php");
      exit();
    }

    // Construct update query
    $updateProduct = "UPDATE product SET product_name = :product_name, category_name = :category_name, product_quantity = :product_quantity, unit_price = :unit_price, discount = :discount, manufacturing_date = :manufacturing_date, expiration_date = :expiration_date, product_status = :product_status WHERE product_number = :product_number";

    $updateProductStatement = $pdo->prepare($updateProduct);
    $updateProductStatement->execute(['product_name' => $productName, 'category_name' => $categoryName, 'product_quantity' => $quantity, 'unit_price' => $unitPrice, 'discount' => $discount, 'manufacturing_date' => $manufacturingDate, 'expiration_date' => $expirationDate, 'product_status' => $productStatus, 'product_number' => $productNumber]);

    $_SESSION['success_message'] = "Product modified successfully";
    header("Location: ../../vue/product.php");
    exit();
  } else {
    $_SESSION['error_message'] = "Please fill all fields";
    header("Location: ../../vue/product.php");
    exit();
  }
}
