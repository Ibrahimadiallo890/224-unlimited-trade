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

  <!-- loan table -->
  <div class="details" style="left: 0%; width:150%; margin-bottom:20px;">
    <div class="recentSales">

      <div class="cardHeader">
        <h2>Loans List</h2>

        <a href="loanPrint.php" style="color: blue; font-size: 30px; margin-right: 1%;"><ion-icon name="print-outline"></ion-icon>
        </a>
      </div>

      <table>
        <thead>
          <tr>
            <td>Loan Number</td>
            <td>Product Name</td>
            <td>Customer Name</td>
            <td>Quantity</td>
            <td>Unit Price</td>
            <td>Discount %</td>
            <td>Loan Date</td>
            <td>Loaned By</td>
            <td>Total Credit(GNF)</td>
          </tr>
        </thead>

        <tbody>
          <?php
          $sql = "SELECT loan_number, product_name, customer_name, loan_quantity, unit_price, discount, loan_date, loaned_by, invoice_amount FROM loan";
          $stmt = $pdo->query($sql);
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td> <?php echo $row['loan_number']; ?> </td>
              <td> <?php echo $row['product_name']; ?> </td>
              <td> <?php echo $row['customer_name']; ?> </td>
              <td> <?php echo $row['loan_quantity']; ?> </td>
              <td> <?php echo $row['unit_price']; ?> </td>
              <td> <?php echo $row['discount']; ?> </td>
              <td> <?php echo $row['loan_date']; ?> </td>
              <td> <?php echo $row['loaned_by']; ?> </td>
              <td> <?php echo $row['invoice_amount']; ?> </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>

    </div>
  </div>

  <div class="btn" style="margin-left: 91%;">
    <a href="../../vue/loans.php">
      <button type="submit" id="loanList">Exit</button>
    </a>
  </div>

</body>

</html>

<?php
require_once "../../system/footer.php";
?>
