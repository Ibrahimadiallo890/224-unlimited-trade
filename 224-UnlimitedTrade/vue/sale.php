<?php
session_start();

require_once "../system/header.php";
require_once "../database/database.php";
require_once "../database/function.php";

if (!isset($_SESSION['username'])) {
  header('Location: ../loginForm/login.php');
  exit();
}

$customerDetails = getCustomerDetails($pdo);
$productDetails = getProductDetails($pdo);
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

  <!-- sale form -->
  <div class="pages-content">
    <h1>Sale Details Form</h1>

    <div class="content">
      <form id="saleForm" method="POST">

        <div class="details" style="left: 0%; width:150%;">
          <div class="recentSales">
            <table>
              <thead>
                <tr>
                  <td>
                    <div class="form-group">
                      <label for="sale_number">Sale Number</label>
                      <input type="text" name="sale_number" id="sale_number" placeholder="#301">
                    </div>
                  </td>

                  <td style="width: 35%;">
                    <div class="form-group">
                      <label for="product_name">Product Details</label>
                      <select name="product_name" id="product_name">
                        <?php foreach ($productDetails as $product) : ?>
                          <option value="<?php echo $product['product_name']; ?>"><?php echo $product['product_name'] . ' - Unit Price: ' . $product['unit_price']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="customer_name">Customer Name</label>
                      <select name="customer_name" id="customer_name">
                        <?php foreach ($customerDetails as $customer) : ?>
                          <option value="<?php echo $customer; ?>"><?php echo $customer; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-group">
                      <label for="sale_quantity">Quantity</label>
                      <input type="number" name="sale_quantity" id="sale_quantity" placeholder="Product Quantity" value="0">
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
                      <label for="sale_date">Sale Date</label>
                      <input type="datetime-local" name="sale_date" id="sale_date" value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="sold_by">Sold By</label>
                      <select name="sold_by" id="sold_by">
                        <?php include('../system/userList.html'); ?>
                      </select>
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="invoice_amount">Total Price(GNF)</label>
                      <input type="text" name="invoice_amount" id="invoice_amount" value="0" readonly>
                    </div>
                  </td>
                </tr>
              </thead>
            </table>
          </div>
        </div>

        <div class="btn">
          <button type="submit" id="addSale" name="action" value="addSale">Add</button>

          <button type="submit" id="modifySale" name="action" value="modifySale">Modify</button>

          <button type="button" id="populateSale">Populate</button>

          <button type="reset" id="reset">Clear</button>
        </div>

      </form>
    </div>
  </div>

  <div class="btn" style="margin-left: 75%;">
    <a href="../model/sale/saleList.php">
      <button type="submit" id="saleList">Sales List</button>
    </a>
  </div>

  <!-- script to call the buttons -->
  <script>
    document.getElementById('saleForm').addEventListener('submit', function(event) {
      event.preventDefault();
      var action = event.submitter.value;

      // Set the action based on the button clicked
      switch (action) {
        case 'addSale':
          this.action = '../model/sale/addSale.php';
          break;

        case 'modifySale':
          this.action = '../model/sale/modifySale.php';
          break;

        default:
          break;
      }
      this.submit();
    });
  </script>

  <!-- script to populate sale details -->
  <script>
    document.getElementById('populateSale').addEventListener('click', function(event) {
      event.preventDefault();
      var saleNumber = document.getElementById('sale_number').value;

      if (saleNumber.trim() === '') {
        alert("Please provide a sale number");
        return;
      }

      $.ajax({
        url: '../model/sale/populateSale.php',
        method: 'POST',
        data: {
          sale_number: saleNumber
        },
        success: function(response) {
          var saleDetails = JSON.parse(response);

          if (saleDetails.error) {
            alert("Sale number not available!")
          } else {
            // Populate the form fields with the retrieved sale details
            document.getElementById('product_name').value = saleDetails.product_name;
            document.getElementById('customer_name').value = saleDetails.customer_name;
            document.getElementById('sale_quantity').value = saleDetails.sale_quantity;
            document.getElementById('unit_price').value = saleDetails.unit_price;
            document.getElementById('discount').value = saleDetails.discount;
            document.getElementById('sale_date').value = saleDetails.sale_date;
            document.getElementById('sold_by').value = saleDetails.sold_by;
            document.getElementById('invoice_amount').value = saleDetails.invoice_amount;
          }
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    });
  </script>

  <!-- Function to calculate total price -->
  <script>
    function calculateTotalPrice() {
      var quantity = parseInt(document.getElementById('sale_quantity').value);
      var unitPrice = parseFloat(document.getElementById('unit_price').value);
      var discount = parseFloat(document.getElementById('discount').value);

      // Calculate total price
      var totalPrice = quantity * unitPrice * ((100 - discount) / 100);

      // Display total price
      document.getElementById('invoice_amount').value = totalPrice.toLocaleString();
    }

    document.getElementById('sale_quantity').addEventListener('input', calculateTotalPrice);
    document.getElementById('unit_price').addEventListener('input', calculateTotalPrice);
    document.getElementById('discount').addEventListener('input', calculateTotalPrice);
  </script>

  <?php
  require_once "../system/footer.php";
  ?>

</body>

</html>