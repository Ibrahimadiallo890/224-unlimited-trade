<?php
session_start();

require_once "../system/header.php";
require_once "../database/database.php";
require_once "../database/function.php";

if (!isset($_SESSION['username'])) {
  header('Location: ../loginForm/login.php');
  exit();
}

$vendorDetails = getVendorDetails($pdo);
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

  <!-- purchase form -->
  <div class="pages-content">
    <h1>Purchase Details Form</h1>

    <div class="content">
      <form id="purchaseForm" method="POST">

        <div class="details" style="left: 0%; width:150%;">
          <div class="recentSales">
            <table>
              <thead>
                <tr>
                  <td>
                    <div class="form-group">
                      <label for="purchase_number">Purchase Number</label>
                      <input type="text" name="purchase_number" id="purchase_number" placeholder="#201">
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
                      <label for="company_name">Company Name</label>
                      <select name="company_name" id="company_name">
                        <?php foreach ($vendorDetails as $vendor) : ?>
                          <option value="<?php echo $vendor; ?>"><?php echo $vendor; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-group">
                      <label for="purchase_quantity">Quantity</label>
                      <input type="number" name="purchase_quantity" id="purchase_quantity" placeholder="Product Quantity" value="0">
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
                      <label for="purchase_date">Purchase Date</label>
                      <input type="datetime-local" name="purchase_date" id="purchase_date" value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="purchased_by">Purchased By</label>
                      <select name="purchased_by" id="purchased_by">
                        <?php include('../system/userList.html'); ?>
                      </select>
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="invoice_amount">Total Price(GNF)</label>
                      <input type="text" name="invoice_amount" id="invoice_amount" readonly>
                    </div>
                  </td>
                </tr>
              </thead>
            </table>
          </div>
        </div>

        <div class="btn">
          <button type="submit" id="addPurchase" name="action" value="addPurchase">Add</button>

          <button type="submit" id="modifyPurchase" name="action" value="modifyPurchase">Modify</button>

          <button type="button" id="populatePurchase">Populate</button>

          <button type="reset" id="reset">Clear</button>
        </div>

      </form>
    </div>
  </div>

  <div class="btn" style="margin-left: 72%;">
    <a href="../model/purchase/purchaseList.php">
      <button type="submit" id="purchaseList">Purchases List</button>
    </a>
  </div>

  <!-- script to call the buttons -->
  <script>
    document.getElementById('purchaseForm').addEventListener('submit', function(event) {
      event.preventDefault();
      var action = event.submitter.value;

      // Set the action based on the button clicked
      switch (action) {
        case 'addPurchase':
          this.action = '../model/purchase/addPurchase.php';
          break;

        case 'modifyPurchase':
          this.action = '../model/purchase/modifyPurchase.php';
          break;

        default:
          break;
      }
      this.submit();
    });
  </script>

  <!-- script to populate product details -->
  <script>
    document.getElementById('populatePurchase').addEventListener('click', function(event) {
      event.preventDefault();
      var purchaseNumber = document.getElementById('purchase_number').value;

      if (purchaseNumber.trim() === '') {
        alert("Please provide a purchase number");
        return;
      }

      $.ajax({
        url: '../model/purchase/populatePurchase.php',
        method: 'POST',
        data: {
          purchase_number: purchaseNumber
        },
        success: function(response) {
          var purchaseDetails = JSON.parse(response);

          if (purchaseDetails.error) {
            alert("Purchase number not available!");
          } else {
            // Populate the form fields with the retrieved purchase details
            document.getElementById('product_name').value = purchaseDetails.product_name;
            document.getElementById('company_name').value = purchaseDetails.company_name;
            document.getElementById('purchase_quantity').value = purchaseDetails.purchase_quantity;
            document.getElementById('unit_price').value = purchaseDetails.unit_price;
            document.getElementById('discount').value = purchaseDetails.discount;
            document.getElementById('purchase_date').value = purchaseDetails.purchase_date;
            document.getElementById('purchased_by').value = purchaseDetails.purchased_by;
            document.getElementById('invoice_amount').value = purchaseDetails.invoice_amount;
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
      var quantity = parseInt(document.getElementById('purchase_quantity').value);
      var unitPrice = parseFloat(document.getElementById('unit_price').value);
      var discount = parseFloat(document.getElementById('discount').value);

      // Calculate total price
      var totalPrice = quantity * unitPrice * ((100 - discount) / 100);

      // Display total price
      document.getElementById('invoice_amount').value = totalPrice.toLocaleString();
    }

    document.getElementById('purchase_quantity').addEventListener('input', calculateTotalPrice);
    document.getElementById('unit_price').addEventListener('input', calculateTotalPrice);
    document.getElementById('discount').addEventListener('input', calculateTotalPrice);
  </script>

  <?php
  require_once "../system/footer.php";
  ?>

</body>

</html>