<?php
session_start();

require_once "../system/header.php";
require_once "../database/database.php";

if (!isset($_SESSION['username'])) {
  header('Location: ../loginForm/login.php');
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
  <link rel="stylesheet" href="../public/css/pages.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>

  <!-- category form -->
  <div class="pages-content">
    <h1>Category Details Form</h1>

    <div class="content">
      <form id="categoryForm" method="POST">

        <div class="form-group">
          <label for="category_number">Category Number</label>
          <input type="text" name="category_number" id="category_number" placeholder="#401">
        </div>

        <div class="form-group">
          <label for="category_name">Category Name</label>
          <input type="text" name="category_name" id="category_name" placeholder="Category Name">
        </div>

        <div class="form-group">
          <label for="category_status">Status</label>
          <select name="category_status" id="category_status">
            <?php include('../system/statusList.html'); ?>
          </select>
        </div>

        <div class="btn">
          <button type="submit" id="addCategory" name="action" value="addCategory">Add</button>

          <button type="submit" id="modifyCategory" name="action" value="modifyCategory">Modify</button>

          <button type="submit" id="deleteCategory" name="action" value="deleteCategory">Delete</button>

          <button type="button" id="populateCategory">Populate</button>

          <button type="reset" id="reset">Clear</button>
        </div>

      </form>

    </div>
  </div>

  <div class="btn" style="margin-left: 72%;">
    <a href="../model/category/categoryList.php">
      <button type="submit" id="categoryList">Categories List</button>
    </a>
  </div>

  <!-- script to call the different buttons -->
  <script>
    document.getElementById('categoryForm').addEventListener('submit', function(event) {
      event.preventDefault();
      var action = event.submitter.value;

      // Set the action based on the button clicked
      switch (action) {
        case 'addCategory':
          this.action = '../model/category/addCategory.php';
          break;

        case 'modifyCategory':
          this.action = '../model/category/modifyCategory.php';
          break;

        case 'deleteCategory':
          this.action = '../model/category/deleteCategory.php';
          break;

        default:
          break;
      }
      this.submit();
    });
  </script>

  <!-- script to populate category details -->
  <script>
    document.getElementById('populateCategory').addEventListener('click', function(event) {
      event.preventDefault();
      var categoryNumber = document.getElementById('category_number').value;

      if (categoryNumber.trim() === '') {
        alert("Please provide a category number");
        return;
      }

      $.ajax({
        url: '../model/category/populateCategory.php',
        type: 'POST',
        data: {
          category_number: categoryNumber
        },
        success: function(response) {
          var categoryDetails = JSON.parse(response);

          if (categoryDetails.error) {
            alert("Category number not available!");
          } else {
            document.getElementById('category_name').value = categoryDetails.category_name;
            document.getElementById('category_status').value = categoryDetails.category_status;
          }
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    });
  </script>

  <?php
  require_once "../system/footer.php";
  ?>

</body>

</html>