<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['product_number'])) {
  $productNumber = htmlentities($_POST['product_number']);

  // Sanitize product number
  if ($productNumber === '') {
    $_SESSION['error_message'] = "Product number not provided";
    header("Location: ../../vue/product.php");
    exit();
  }

  // Check if productNumber is in the DB
  $checkProductNumber = "SELECT product_number FROM product WHERE product_number = :product_number";

  $checkProductNumberStatement = $pdo->prepare($checkProductNumber);
  $checkProductNumberStatement->execute(['product_number' => $productNumber]);

  if ($checkProductNumberStatement->rowCount() > 0) {

    // Start the deletion process
    $deleteProduct = "DELETE FROM product WHERE product_number = :product_number";

    $deleteProductStatement = $pdo->prepare($deleteProduct);
    $deleteProductStatement->execute(['product_number' => $productNumber]);

    $_SESSION['success_message'] = "Product deleted successfully";
    header("Location: ../../vue/product.php");
    exit();
  } else {
    $_SESSION['error_message'] = "Product does not exist";
    header("Location: ../../vue/product.php");
    exit();
  }
} else {
  $_SESSION['error_message'] = "Product number not provided";
  header("Location: ../../vue/product.php");
  exit();
}
