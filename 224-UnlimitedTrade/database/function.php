<?php
session_start();
require_once "database.php";

// function to get category details for the product form
function getCategoryDetails($pdo)
{
  try {

    $sql = "SELECT category_name FROM category";
    $stmt = $pdo->query($sql);

    $categoryDetails = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $categoryDetails[] = $row['category_name'];
    }
    return $categoryDetails;
  } catch (PDOException $e) {
    die("Error: " . $e->getMessage());
  }
}

// function to get customer details for the sale form
function getCustomerDetails($pdo)
{
  try {

    $sql = "SELECT full_name FROM customer";
    $stmt = $pdo->query($sql);

    $customerDetails = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $customerDetails[] = $row['full_name'];
    }
    return $customerDetails;
  } catch (PDOException $e) {
    die("Error: " . $e->getMessage());
  }
}

// function to get product details for the sale & purchase form
function getProductDetails($pdo)
{
  try {
    $sql = "SELECT product_name, unit_price FROM product";
    $stmt = $pdo->query($sql);

    $productDetails = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $productDetails[] = $row;
    }
    return $productDetails;
  } catch (PDOException $e) {
    die("Error: " . $e->getMessage());
  }
}

// function to get vendor details for the purchase form
function getVendorDetails($pdo)
{
  try {

    $sql = "SELECT company_name FROM vendor";
    $stmt = $pdo->query($sql);

    $vendorDetails = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $vendorDetails[] = $row['company_name'];
    }
    return $vendorDetails;
  } catch (PDOException $e) {
    die("Error: " . $e->getMessage());
  }
}

// Function to display number of products in dashboard
function getAllProducts()
{
  $sql = "SELECT COUNT(*) AS number FROM product";

  $req = $GLOBALS['pdo']->prepare($sql);
  $req->execute();

  return $req->fetch(PDO::FETCH_ASSOC);
}

// Function to display number of purchases in dashboard
function getAllPurchases()
{
  $sql = "SELECT COUNT(*) AS number FROM purchase";

  $req = $GLOBALS['pdo']->prepare($sql);
  $req->execute();

  return $req->fetch(PDO::FETCH_ASSOC);
}

// Function to display number of purchases in dashboard
function getAllSales()
{
  $sql = "SELECT COUNT(*) AS number FROM sale";

  $req = $GLOBALS['pdo']->prepare($sql);
  $req->execute();

  return $req->fetch(PDO::FETCH_ASSOC);
}

// Function to display total amount of today's sales in dashboard
function getDailySales()
{
  $sql = "SELECT SUM(REPLACE(invoice_amount, ',', '')) AS daily_sales 
          FROM sale 
          WHERE DATE(sale_date) = CURDATE()";

  $req = $GLOBALS['pdo']->prepare($sql);
  $req->execute();

  return $req->fetch(PDO::FETCH_ASSOC);
}

// Function to display total amount of sales in dashboard
function getTotalSales()
{
  $sql = "SELECT SUM(REPLACE(invoice_amount, ',', '')) AS total_sales FROM sale";

  $req = $GLOBALS['pdo']->prepare($sql);
  $req->execute();

  return $req->fetch(PDO::FETCH_ASSOC);
}

// Function to display total amount of purchase expense in dashboard
function getTotalPurchases()
{
  $sql = "SELECT SUM(REPLACE(invoice_amount, ',', '')) 
          AS total_purchases FROM purchase";

  $req = $GLOBALS['pdo']->prepare($sql);
  $req->execute();

  return $req->fetch(PDO::FETCH_ASSOC);
}

// Function to display total amount of loan expense in dashboard
function getTotalLoans()
{
  $sql = "SELECT SUM(REPLACE(invoice_amount, ',', '')) 
          AS total_loans FROM loan";

  $req = $GLOBALS['pdo']->prepare($sql);
  $req->execute();

  return $req->fetch(PDO::FETCH_ASSOC);
}

// function to get sale details for the sale printing form
function getSalePrint($pdo)
{
  if (isset($_POST['sale_number'])) {
    $sale_number = $_POST['sale_number'];

    try {
      $sql = "SELECT sale_number, product_name, customer_name, sale_quantity, unit_price, discount, sale_date, sold_by, invoice_amount FROM sale WHERE sale_number = :sale_number";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':sale_number', $sale_number); // Corrected variable name here
      $stmt->execute();

      $printDetails = array();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $printDetails[] = $row;
      }
      return $printDetails;
    } catch (PDOException $e) {
      die("Error: " . $e->getMessage());
    }
  }
}

// function to get product details for the product printing form
function getProductPrint($pdo)
{
  if (isset($_POST['product_number'])) {
    $product_number = $_POST['product_number'];

    try {
      $sql = "SELECT product_number, product_name, category_name, product_quantity, unit_price, discount, manufacturing_date, expiration_date, product_status FROM product WHERE product_number = :product_number";
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':product_number', $product_number); // Corrected variable name here
      $stmt->execute();

      $printDetails = array();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $printDetails[] = $row;
      }
      return $printDetails;
    } catch (PDOException $e) {
      die("Error: " . $e->getMessage());
    }
  }
}

// function to get loan details for the loan printing form
function getLoanPrint($pdo)
{
  if (isset($_POST['loan_number'])) {
    $loan_number = $_POST['loan_number'];

    try {
      $sql = "SELECT loan_number, product_name, customer_name, loan_quantity, unit_price, discount, loan_date, loaned_by, invoice_amount FROM loan WHERE loan_number = :loan_number";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':loan_number', $loan_number); // Corrected variable name here
      $stmt->execute();

      $printDetails = array();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $printDetails[] = $row;
      }
      return $printDetails;
    } catch (PDOException $e) {
      die("Error: " . $e->getMessage());
    }
  }
}

// function to get customer name and total credit for the settlement page.
function getLoanCustomerDetails($pdo)
{
  try {
    $sql = "SELECT customer_name, invoice_amount FROM loan";
    $stmt = $pdo->query($sql);

    $customerDetails = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $customerDetails[] = $row;
    }
    return $customerDetails;
  } catch (PDOException $e) {
    die("Error: " . $e->getMessage());
  }
}

// function to get purchase details for the purchase printing form
function getPurchasePrint($pdo)
{
  if (isset($_POST['purchase_number'])) {
    $purchase_number = $_POST['purchase_number'];

    try {
      $sql = "SELECT purchase_number, product_name, company_name, purchase_quantity, unit_price, discount, purchase_date, purchased_by, invoice_amount FROM purchase WHERE purchase_number = :purchase_number";
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':purchase_number', $purchase_number); // Corrected variable name here
      $stmt->execute();

      $printDetails = array();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $printDetails[] = $row;
      }
      return $printDetails;
    } catch (PDOException $e) {
      die("Error: " . $e->getMessage());
    }
  }
}