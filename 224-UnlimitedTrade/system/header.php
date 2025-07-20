<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['username'])) {
  header('Location: ../loginForm/login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    <?php
    echo ucfirst(str_replace(".php", "", basename($_SERVER['PHP_SELF'])));
    ?>
  </title>

  <!-- css styles links -->
  <link rel="stylesheet" href="../public/css/style.css" />
</head>

<body>

  <!-- navigation start -->
  <div class="container">

    <!-- navigation menu start -->
    <div class="navigation">
      <ul>

        <li>
          <a href="#">
            <span class="icon">
              <ion-icon name="business-outline"></ion-icon>
            </span>
            <span class="title">224-Unlimited-Trade</span>
          </a>
        </li>

        <li>
          <a href="../vue/dashboard.php">
            <span class="icon">
              <ion-icon name="home-outline"></ion-icon>
            </span>
            <span class="title">Dashboard</span>
          </a>
        </li>

        <li>
          <a href="../vue/product.php">
            <span class="icon">
              <ion-icon name="pricetags-outline"></ion-icon>
            </span>
            <span class="title">Product</span>
          </a>
        </li>

        <li>
          <a href="../vue/purchase.php">
            <span class="icon">
              <ion-icon name="card-outline"></ion-icon>
            </span>
            <span class="title">Purchase</span>
          </a>
        </li>

        <li>
          <a href="../vue/sale.php">
            <span class="icon">
              <ion-icon name="cash-outline"></ion-icon>
            </span>
            <span class="title">Sales</span>
          </a>
        </li>

        <li>
          <a href="../vue/category.php">
            <span class="icon">
              <ion-icon name="copy-outline"></ion-icon>
            </span>
            <span class="title">Category</span>
          </a>
        </li>

        <li>
          <a href="../vue/customer.php">
            <span class="icon">
              <ion-icon name="people-outline"></ion-icon>
            </span>
            <span class="title">Customer</span>
          </a>
        </li>

        <li>
          <a href="../vue/vendor.php">
            <span class="icon">
              <ion-icon name="people-circle-outline"></ion-icon>
            </span>
            <span class="title">Vendor</span>
          </a>
        </li>

        <li>
          <a href="../vue/loans.php">
            <span class="icon">
              <ion-icon name="pricetag-outline"></ion-icon>
            </span>
            <span class="title">Loans</span>
          </a>
        </li>

        <li>
          <a href="../vue/settlements.php">
            <span class="icon">
              <ion-icon name="pricetags-outline"></ion-icon>
            </span>
            <span class="title">Settlements</span>
          </a>
        </li>

        <li>
          <a href="../vue/calculator.php">
            <span class="icon">
              <ion-icon name="calculator-outline"></ion-icon>
            </span>
            <span class="title">Calculator</span>
          </a>
        </li>

      </ul>
    </div>
    <!-- navigation menu end -->

    <!-- main body topbar start -->
    <div class="main">
      <div class="topbar">

        <div class="toggle">
          <ion-icon name="grid-outline"></ion-icon>
        </div>

        <div class="text">
          <h1>Welcome to 224-Unlimited-Trade</h1>
        </div>

        <div class="user">
          <a href="../loginForm/signOut.php">
            <ion-icon name="log-out-outline"></ion-icon>
          </a>
        </div>

      </div>
      <!-- main body topbar end-1 -->
      