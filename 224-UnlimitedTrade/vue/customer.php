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

  <!-- customer form -->
  <div class="pages-content">
    <h1>Customer Details Form</h1>

    <div class="content">
      <form id="customerForm" method="POST">

        <div class="details" style="left: 0%; width:150%;">
          <div class="recentSales">
            <table>
              <thead>
                <tr>
                  <td style="width: 35%;">
                    <div class="form-group">
                      <label for="customer_number">Customer Number</label>
                      <input type="text" name="customer_number" id="customer_number" placeholder="#501">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="full_name">Full Name</label>
                      <input type="text" name="full_name" id="full_name" placeholder="Customer Full Name">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="business_name">Business Name</label>
                      <input type="text" name="business_name" id="business_name" placeholder="Business Name">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" name="email" id="email" placeholder="Customer Email" autocomplete="email">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="phone_number">Phone Number</label>
                      <input type="text" name="phone_number" id="phone_number" placeholder="Phone Number">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="address">Address</label>
                      <input type="text" name="address" id="address" placeholder="Customer Address" autocomplete="address">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-group">
                      <label for="country">Country</label>
                      <select name="country" id="country" autocomplete="country">
                        <?php include('../system/countriesList.html'); ?>
                      </select>
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="customer_type">Type</label>
                      <select name="customer_type" id="customer_type">
                        <?php include('../system/typeList.html'); ?>
                      </select>
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="customer_status">Status</label>
                      <select name="customer_status" id="customer_status">
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
          <button type="submit" id="addCustomer" name="action" value="addCustomer">Add</button>

          <button type="submit" id="modifyCustomer" name="action" value="modifyCustomer">Modify</button>

          <button type="submit" id="deleteCustomer" name="action" value="deleteCustomer">Delete</button>

          <button type="button" id="populateCustomer">Populate</button>

          <button type="reset" id="reset">Clear</button>
        </div>

      </form>

    </div>
  </div>

  <div class="btn" style="margin-left: 72%;">
    <a href="../model/customer/customerList.php">
      <button type="submit" id="customerList">Customers List</button>
    </a>
  </div>

  <!-- script to call the different buttons -->
  <script>
    document.getElementById('customerForm').addEventListener('submit', function(event) {
      event.preventDefault();
      var action = event.submitter.value;

      // Set the action based on the button clicked
      switch (action) {
        case 'addCustomer':
          this.action = '../model/customer/addCustomer.php';
          break;

        case 'modifyCustomer':
          this.action = '../model/customer/modifyCustomer.php';
          break;

        case 'deleteCustomer':
          this.action = '../model/customer/deleteCustomer.php';
          break;

        default:
          break;
      }
      this.submit();
    });
  </script>

  <!-- script to populate customer details -->
  <script>
    document.getElementById('populateCustomer').addEventListener('click', function(event) {
      event.preventDefault();
      var customerNumber = document.getElementById('customer_number').value;

      if (customerNumber.trim() === '') {
        alert("Please provide a customer number");
        return;
      }

      $.ajax({
        url: '../model/customer/populateCustomer.php',
        type: 'POST',
        data: {
          customer_number: customerNumber
        },
        success: function(response) {
          var customerDetails = JSON.parse(response);

          if (customerDetails.error) {
            alert("Customer number not available");
          } else {
            document.getElementById('full_name').value = customerDetails.full_name;
            document.getElementById('business_name').value = customerDetails.business_name;
            document.getElementById('email').value = customerDetails.email;
            document.getElementById('phone_number').value = customerDetails.phone_number;
            document.getElementById('address').value = customerDetails.address;
            document.getElementById('country').value = customerDetails.country;
            document.getElementById('customer_type').value = customerDetails.customer_type;
            document.getElementById('customer_status').value = customerDetails.customer_status;
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
