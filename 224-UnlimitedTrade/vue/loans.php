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

  <!-- loan form -->
  <div class="pages-content">
    <h1>Loan Details Form</h1>

    <div class="content">
      <form id="loanForm" method="POST">

        <div class="details" style="left: 0%; width:150%;">
          <div class="recentSales">
            <table>
              <thead>
                <tr>
                  <td>
                    <div class="form-group">
                      <label for="loan_number">Loan Number</label>
                      <input type="text" name="loan_number" id="loan_number" placeholder="#701">
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
                      <label for="loan_quantity">Quantity</label>
                      <input type="number" name="loan_quantity" id="loan_quantity" placeholder="Product Quantity" value="0">
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
                      <label for="loan_date">Loan Date</label>
                      <input type="datetime-local" name="loan_date" id="loan_date" value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="loaned_by">Loaned By</label>
                      <select name="loaned_by" id="loaned_by">
                        <?php include('../system/userList.html'); ?>
                      </select>
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="invoice_amount">Total Credit(GNF)</label>
                      <input type="text" name="invoice_amount" id="invoice_amount" value="0" readonly>
                    </div>
                  </td>
                </tr>
              </thead>
            </table>
          </div>
        </div>

        <div class="btn">
          <button type="submit" id="addLoan" name="action" value="addLoan">Add</button>

          <button type="submit" id="modifyLoan" name="action" value="modifyLoan">Modify</button>

          <button type="button" id="populateLoan">Populate</button>

          <button type="reset" id="reset">Clear</button>
        </div>

      </form>
    </div>
  </div>

  <div class="btn" style="margin-left: 75%;">
    <a href="../model/loan/loanList.php">
      <button type="submit" id="loanList">Loans List</button>
    </a>
  </div>

  <!-- script to call the buttons -->
  <script>
    document.getElementById('loanForm').addEventListener('submit', function(event) {
      event.preventDefault();
      var action = event.submitter.value;

      // Set the action based on the button clicked
      switch (action) {
        case 'addLoan':
          this.action = '../model/loan/addLoan.php';
          break;

        case 'modifyLoan':
          this.action = '../model/loan/modifyLoan.php';
          break;

        default:
          break;
      }
      this.submit();
    });
  </script>

  <!-- script to populate loan details -->
  <script>
    document.getElementById('populateLoan').addEventListener('click', function(event) {
      event.preventDefault();
      var loanNumber = document.getElementById('loan_number').value;

      if (loanNumber.trim() === '') {
        alert("Please provide a loan number");
        return;
      }

      $.ajax({
        url: '../model/loan/populateLoan.php',
        method: 'POST',
        data: {
          loan_number: loanNumber
        },
        success: function(response) {
          var loanDetails = JSON.parse(response);

          if (loanDetails.error) {
            alert("Loan number not available!")
          } else {
            // Populate the form fields with the retrieved sale details
            document.getElementById('product_name').value = loanDetails.product_name;
            document.getElementById('customer_name').value = loanDetails.customer_name;
            document.getElementById('loan_quantity').value = loanDetails.loan_quantity;
            document.getElementById('unit_price').value = loanDetails.unit_price;
            document.getElementById('discount').value = loanDetails.discount;
            document.getElementById('loan_date').value = loanDetails.loan_date;
            document.getElementById('loaned_by').value = loanDetails.loaned_by;
            document.getElementById('invoice_amount').value = loanDetails.invoice_amount;
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
      var quantity = parseInt(document.getElementById('loan_quantity').value);
      var unitPrice = parseFloat(document.getElementById('unit_price').value);
      var discount = parseFloat(document.getElementById('discount').value);

      // Calculate total price
      var totalPrice = quantity * unitPrice * ((100 - discount) / 100);

      // Display total price
      document.getElementById('invoice_amount').value = totalPrice.toLocaleString();
    }

    document.getElementById('loan_quantity').addEventListener('input', calculateTotalPrice);
    document.getElementById('unit_price').addEventListener('input', calculateTotalPrice);
    document.getElementById('discount').addEventListener('input', calculateTotalPrice);
  </script>

  <?php
  require_once "../system/footer.php";
  ?>

</body>

</html>