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

  <!-- customer table -->
  <div class="details" style="left: 0%; width:150%; margin-bottom:20px;">
    <div class="recentSales">

      <div class="cardHeader">
        <h2>Customers List</h2>
      </div>

      <table>
        <thead>
          <tr>
            <td>Customer Number</td>
            <td>Full Name</td>
            <td>Business Name</td>
            <td>Email</td>
            <td>Phone Number</td>
            <td>Address</td>
            <td>Country</td>
            <td>Type</td>
            <td>Status</td>
          </tr>
        </thead>

        <tbody>
          <?php
          $sql = "SELECT customer_number, full_name, business_name, email, phone_number, address, country, customer_type, customer_status FROM customer";
          $stmt = $pdo->query($sql);
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td> <?php echo $row['customer_number']; ?> </td>
              <td> <?php echo $row['full_name']; ?> </td>
              <td> <?php echo $row['business_name']; ?> </td>
              <td> <?php echo $row['email']; ?> </td>
              <td> <?php echo $row['phone_number']; ?> </td>
              <td> <?php echo $row['address']; ?> </td>
              <td> <?php echo $row['country']; ?> </td>
              <td> <?php echo $row['customer_type']; ?> </td>
              <td> <?php echo $row['customer_status']; ?> </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>

    </div>
  </div>

  <div class="btn" style="margin-left: 91%;">
    <a href="../../vue/customer.php">
      <button type="submit" id="customerList">Exit</button>
    </a>
  </div>

</body>

</html>

<?php
require_once "../../system/footer.php";
?>