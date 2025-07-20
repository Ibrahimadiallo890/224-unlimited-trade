<?php
session_start();

require_once "../system/header.php";
require_once "../database/database.php";
require_once "../database/function.php";

if (!isset($_SESSION['username'])) {
  header('Location: ../loginForm/login.php');
  exit();
}

$customerDetails = getLoanCustomerDetails($pdo);
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

  <!-- settlement form -->
  <div class="pages-content">
    <h1>Settlement Details Form</h1>

    <div class="content">
      <form id="settlementForm" method="POST">

        <div class="form-group">
          <label for="settlement_number">Settlement Number</label>
          <input type="text" name="settlement_number" id="settlement_number" placeholder="#801">
        </div>

        <div class="form-group">
          <label for="customer_name">Customer Details</label>
          <select name="customer_name" id="customer_name">
            <?php foreach ($customerDetails as $customer) : ?>
              <option value="<?php echo $customer['customer_name']; ?>"><?php echo $customer['customer_name'] . ' - Total Credit(GNF): ' . $customer['invoice_amount']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="settled_by">Settled By</label>
          <select name="settled_by" id="settled_by">
            <?php include('../system/userList.html'); ?>
          </select>
        </div>

        <div class="form-group">
          <label for="settlement_date">Settlement Date</label>
          <input type="datetime-local" name="settlement_date" id="settlement_date" value="<?php echo date('Y-m-d\TH:i'); ?>">
        </div>

        <div class="form-group">
          <label for="settled_amount">Total Payed(GNF)</label>
          <input type="text" name="settled_amount" id="settled_amount" value="0">
        </div>

        <div class="btn">
          <button type="submit" id="addSettlement" name="action" value="addSettlement">Settle</button>

          <button type="button" id="populateSettlement">Populate</button>

          <button type="reset" id="reset">Clear</button>
        </div>

      </form>

    </div>
  </div>

  <div class="btn" style="margin-left: 71%;">
    <a href="../model/settlement/settlementList.php">
      <button type="submit" id="settlementList">Settlements List</button>
    </a>
  </div>

  <!-- script to call the different buttons -->
  <script>
    document.getElementById('settlementForm').addEventListener('submit', function(event) {
      event.preventDefault();
      var action = event.submitter.value;

      // Set the action based on the button clicked
      switch (action) {
        case 'addSettlement':
          this.action = '../model/settlement/addSettlement.php';
          break;

        default:
          break;
      }
      this.submit();
    });
  </script>

  <!-- script to populate settlement details -->
  <script>
    document.getElementById('populateSettlement').addEventListener('click', function(event) {
      event.preventDefault();
      var settlementNumber = document.getElementById('settlement_number').value;

      if (settlementNumber.trim() === '') {
        alert("Please provide a settlement number");
        return;
      }

      $.ajax({
        url: '../model/settlement/populateSettlement.php',
        type: 'POST',
        data: {
          settlement_number: settlementNumber
        },
        success: function(response) {
          var settlementDetails = JSON.parse(response);

          if (settlementDetails.error) {
            alert("Settlement number not available!");
          } else {
            document.getElementById('customer_name').value = settlementDetails.customer_name;
            document.getElementById('settled_by').value = settlementDetails.settled_by;
            document.getElementById('settlement_date').value = settlementDetails.settlement_date;
            document.getElementById('settled_amount').value = settlementDetails.settled_amount;
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