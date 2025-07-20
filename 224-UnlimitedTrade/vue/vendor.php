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

  <!-- vendor form -->
  <div class="pages-content">
    <h1>Vendor Details Form</h1>

    <div class="content">
      <form id="vendorForm" method="POST">

        <div class="details" style="left: 0%; width:150%;">
          <div class="recentSales">
            <table>
              <thead>
                <tr>
                  <td style="width: 35%;">
                    <div class="form-group">
                      <label for="vendor_number">Vendor Number</label>
                      <input type="text" name="vendor_number" id="vendor_number" placeholder="#601">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="full_name">Full Name</label>
                      <input type="text" name="full_name" id="full_name" placeholder="Vendor Full Name">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="company_name">Company Name</label>
                      <input type="text" name="company_name" id="company_name" placeholder="Company Name">
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" name="email" id="email" placeholder="Vendor Email" autocomplete="email">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="phone_number">Phone Number</label>
                      <input type="text" name="phone_number" id="phone_number" placeholder="Vendor Phone Number">
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="address">Address</label>
                      <input type="text" name="address" id="address" placeholder="Vendor Address" autocomplete="address">
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
                      <label for="vendor_type">Type</label>
                      <select name="vendor_type" id="vendor_type">
                        <?php include('../system/typeList.html'); ?>
                      </select>
                    </div>
                  </td>

                  <td>
                    <div class="form-group">
                      <label for="vendor_status">Status</label>
                      <select name="vendor_status" id="vendor_status">
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
          <button type="submit" id="addVendor" name="action" value="addVendor">Add</button>

          <button type="submit" id="modifyVendor" name="action" value="modifyVendor">Modify</button>

          <button type="submit" id="deleteVendor" name="action" value="deleteVendor">Delete</button>

          <button type="button" id="populateVendor">Populate</button>

          <button type="reset" id="reset">Clear</button>
        </div>

      </form>

    </div>
  </div>

  <div class="btn" style="margin-left: 73%;">
    <a href="../model/vendor/vendorList.php">
      <button type="submit" id="vendorList">Vendors List</button>
    </a>
  </div>

  <!-- script to call the different buttons -->
  <script>
    document.getElementById('vendorForm').addEventListener('submit', function(event) {
      event.preventDefault();
      var action = event.submitter.value;

      // Set the action based on the button clicked
      switch (action) {
        case 'addVendor':
          this.action = '../model/vendor/addVendor.php';
          break;

        case 'modifyVendor':
          this.action = '../model/vendor/modifyVendor.php';
          break;

        case 'deleteVendor':
          this.action = '../model/vendor/deleteVendor.php';
          break;

        default:
          break;
      }
      this.submit();
    });
  </script>

  <!-- script to populate vendor details -->
  <script>
    document.getElementById('populateVendor').addEventListener('click', function(event) {
      event.preventDefault();
      var vendorNumber = document.getElementById('vendor_number').value;

      if (vendorNumber.trim() === '') {
        alert("Please provide a vendor number");
        return;
      }

      $.ajax({
        url: '../model/vendor/populateVendor.php',
        type: 'POST',
        data: {
          vendor_number: vendorNumber
        },
        success: function(response) {
          var vendorDetails = JSON.parse(response);

          if (vendorDetails.error) {
            alert("Vendor number not available!")
          } else {
            document.getElementById('full_name').value = vendorDetails.full_name;
            document.getElementById('company_name').value = vendorDetails.company_name;
            document.getElementById('email').value = vendorDetails.email;
            document.getElementById('phone_number').value = vendorDetails.phone_number;
            document.getElementById('address').value = vendorDetails.address;
            document.getElementById('country').value = vendorDetails.country;
            document.getElementById('vendor_type').value = vendorDetails.vendor_type;
            document.getElementById('vendor_status').value = vendorDetails.vendor_status;
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
