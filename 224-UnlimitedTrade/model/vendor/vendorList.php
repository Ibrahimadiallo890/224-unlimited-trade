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

  <!-- vendor table -->
  <div class="details" style="left: 0%; width:150%; margin-bottom:20px;">
    <div class="recentSales">

      <div class="cardHeader">
        <h2>Vendors List</h2>
      </div>

      <table>
        <thead>
          <tr>
            <td>Vendor Number</td>
            <td>Full Name</td>
            <td>Company Name</td>
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
          $sql = "SELECT vendor_number, full_name, company_name, email, phone_number, address, country, vendor_type, vendor_status FROM vendor";
          $stmt = $pdo->query($sql);
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td> <?php echo $row['vendor_number']; ?> </td>
              <td> <?php echo $row['full_name']; ?> </td>
              <td> <?php echo $row['company_name']; ?> </td>
              <td> <?php echo $row['email']; ?> </td>
              <td> <?php echo $row['phone_number']; ?> </td>
              <td> <?php echo $row['address']; ?> </td>
              <td> <?php echo $row['country']; ?> </td>
              <td> <?php echo $row['vendor_type']; ?> </td>
              <td> <?php echo $row['vendor_status']; ?> </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>

    </div>
  </div>

  <div class="btn" style="margin-left: 91%;">
    <a href="../../vue/vendor.php">
      <button type="submit" id="vendorList">Exit</button>
    </a>
  </div>

</body>

</html>

<?php
require_once "../../system/footer.php";
?>