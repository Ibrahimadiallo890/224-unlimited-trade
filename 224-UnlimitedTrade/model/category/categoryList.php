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

  <!-- category table -->
  <div class="details" style="left: 7%; width:130%; margin-bottom:20px;">
    <div class="recentSales">

      <div class="cardHeader">
        <h2>Categories List</h2>
      </div>

      <table>
        <thead>
          <tr>
            <td>Category Number</td>
            <td>Category Name</td>
            <td>Status</td>
          </tr>
        </thead>

        <tbody>
          <?php
          $sql = "SELECT category_number, category_name, category_status FROM category";
          $stmt = $pdo->query($sql);
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td> <?php echo $row['category_number']; ?> </td>
              <td> <?php echo $row['category_name']; ?> </td>
              <td> <?php echo $row['category_status']; ?> </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>

    </div>
  </div>

  <div class="btn" style="margin-left: 85%;">
    <a href="../../vue/category.php">
      <button type="submit" id="categoryList">Exit</button>
    </a>
  </div>

</body>

</html>

<?php
require_once "../../system/footer.php";
?>
