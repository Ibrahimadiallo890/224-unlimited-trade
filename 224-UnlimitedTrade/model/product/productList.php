<?php
session_start();

require_once "../../system/header.php";
require_once "../../database/database.php";

if (!isset($_SESSION['username'])) {
  header('Location: ../../loginForm/login.php');
  exit();
}
?>

<?php
// Check if error or success message is set
if (isset($_SESSION['error_message'])) {
  echo '<div class="error">' . $_SESSION['error_message'] . '</div>';
  unset($_SESSION['error_message']);
}
if (isset($_SESSION['success_message'])) {
  echo '<div class="success">' . $_SESSION['success_message'] . '</div>';
  unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php
    echo ucfirst(str_replace(".php", "", basename($_SERVER['PHP_SELF'])));
    ?>
  </title>
  <link rel="stylesheet" href="../../public/css/style.css">
  <link rel="stylesheet" href="../../public/css/pages.css">
</head>

<body>
  
  <!-- product table -->
  <div class="details" style="left: 0%; width:150%; margin-bottom:20px;">
    <div class="recentSales">

      <div class="cardHeader">
        <h2>Products List</h2>

        <a href="productPrint.php" style="color: blue; font-size: 30px; margin-right: 1%;"><ion-icon name="print-outline"></ion-icon>
        </a>

      </div>

      <table>
        <thead>
          <tr>
            <td>Product Number</td>
            <td>Product Name</td>
            <td>Category Name</td>
            <td>Quantity</td>
            <td>Unit Price</td>
            <td>Discount %</td>
            <td>Manufacturing Date</td>
            <td>Expiration Date</td>
            <td>Status</td>
            <td>Total Stock</td>
          </tr>
        </thead>

        <tbody>
          <?php
          $sql = "SELECT product_number, product_name, category_name, product_quantity, unit_price, discount, manufacturing_date, expiration_date, product_status FROM product";
          $stmt = $pdo->query($sql);
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td> <?php echo $row['product_number']; ?> </td>
              <td> <?php echo $row['product_name']; ?> </td>
              <td> <?php echo $row['category_name']; ?> </td>
              <td> <?php echo $row['product_quantity']; ?> </td>
              <td> <?php echo $row['unit_price']; ?> </td>
              <td> <?php echo $row['discount']; ?> </td>
              <td> <?php echo $row['manufacturing_date']; ?> </td>
              <td> <?php echo $row['expiration_date']; ?> </td>
              <td> <?php echo $row['product_status']; ?> </td>
              <td> <?php echo $row['product_quantity']; ?> </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>

    </div>
  </div>

  <div class="btn" style="margin-left: 91%;">
    <a href="../../vue/product.php">
      <button type="submit" id="productList">Exit</button>
    </a>
  </div>

</body>

</html>

<?php
require_once "../../system/footer.php";
?>
