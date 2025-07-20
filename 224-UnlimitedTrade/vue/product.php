<?php
session_start();

require_once "../system/header.php";
require_once "../database/database.php";
require_once "../database/function.php";

if (!isset($_SESSION['username'])) {
  header('Location: ../loginForm/login.php');
  exit();
}

$categoryDetails = getCategoryDetails($pdo);
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

  <!-- product form -->
  <div class="pages-content">
    <h1>Product Details Form</h1>

    <div class="content">
      <form id="productForm" method="POST">

        <div class="details" style="left: 0%; width:150%;">
          <div class="recentSales">
            <table>
              <thead>
                <tr>
                  <td>
                    <div class="form-group">
                      <label for="product_number">Product Number</label>
                      <input type="text" name="product_number" id="product_number" placeholder="#101">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="product_name">Product Name</label>
                      <input type="text" name="product_name" id="product_name" placeholder="Product Name">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="category_name">Category Name</label>
                      <select name="category_name" id="category_name">
                        <?php
                        foreach ($categoryDetails as $category) {
                          echo '<option value="' . $category . '">' . $category . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-group">
                      <label for="product_quantity">Quantity</label>
                      <input type="number" name="product_quantity" id="product_quantity" placeholder="Product Quantity" value="0">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="unit_price">Unit Price</label>
                      <input type="text" name="unit_price" id="unit_price" placeholder="Product Unit Price" value="0">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="discount">Discount %</label>
                      <input type="text" name="discount" id="discount" placeholder="Product Discount" value="0">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-group">
                      <label for="manufacturing_date">Manufacturing Date</label>
                      <input type="datetime-local" name="manufacturing_date" id="manufacturing_date" value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="expiration_date">Expiration Date</label>
                      <input type="datetime-local" name="expiration_date" id="expiration_date" value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="product_status">Status</label>
                      <select name="product_status" id="product_status">
                        <?php include('../system/statusList.html'); ?>
                      </select>
                    </div>
                  </td>
                </tr>
              </thead>
            </table>
          </div>
        </div>

        <div class="btn">
          <button type="submit" id="addProduct" name="action" value="addProduct">Add</button>

          <button type="submit" id="modifyProduct" name="action" value="modifyProduct">Modify</button>

          <button type="submit" id="deleteProduct" name="action" value="deleteProduct">Delete</button>

          <button type="button" id="populateProduct">Populate</button>

          <button type="reset" id="reset">Clear</button>
        </div>

      </form>
    </div>
  </div>

  <div class="btn" style="margin-left: 73%;">
    <a href="../model/product/productList.php">
      <button type="submit" id="productList">Products List</button>
    </a>
  </div>

  <!-- script to call the buttons -->
  <script>
    document.getElementById('productForm').addEventListener('submit', function(event) {
      event.preventDefault();
      var action = event.submitter.value;

      // Set the action based on the button clicked
      switch (action) {
        case 'addProduct':
          this.action = '../model/product/addProduct.php';
          break;

        case 'modifyProduct':
          this.action = '../model/product/modifyProduct.php';
          break;

        case 'deleteProduct':
          this.action = '../model/product/deleteProduct.php';
          break;

        default:
          break;
      }
      this.submit();
    });
  </script>

  <!-- script to populate product details -->
  <script>
    document.getElementById('populateProduct').addEventListener('click', function(event) {
      event.preventDefault();
      var productNumber = document.getElementById('product_number').value;

      if (productNumber.trim() === '') {
        alert("Please provide a product number");
        return;
      }

      $.ajax({
        url: '../model/product/populateProduct.php',
        method: 'POST',
        data: {
          product_number: productNumber
        },
        success: function(response) {
          var productDetails = JSON.parse(response);

          if (productDetails.error) {
            alert("Product number not available!");
          } else {
            // Populate the form fields with the retrieved product details
            document.getElementById('product_name').value = productDetails.product_name;
            document.getElementById('category_name').value = productDetails.category_name;
            document.getElementById('product_quantity').value = productDetails.product_quantity;
            document.getElementById('unit_price').value = productDetails.unit_price;
            document.getElementById('discount').value = productDetails.discount;
            document.getElementById('manufacturing_date').value = productDetails.manufacturing_date;
            document.getElementById('expiration_date').value = productDetails.expiration_date;
            document.getElementById('product_status').value = productDetails.product_status;
          }
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    });
  </script>

  <!-- Function to format unit price -->
  <script>
    function formatUnitPrice() {
      var unitPriceInput = document.getElementById('unit_price');
      var unitPriceValue = parseFloat(unitPriceInput.value.replace(/,/g, ''));

      if (!isNaN(unitPriceValue)) {
        unitPriceInput.value = unitPriceValue.toLocaleString();
      }
    }
    document.getElementById('unit_price').addEventListener('input', formatUnitPrice);
  </script>

  <?php
  require_once "../system/footer.php";
  ?>

</body>

</html>