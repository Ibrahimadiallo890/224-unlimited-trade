<?php
session_start();

require_once "../../system/header.php";
require_once "../../database/database.php";
require_once "../../database/function.php";

if (!isset($_SESSION['username'])) {
  header('Location: ../../loginForm/login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty($_POST['purchase_number'])) {
    $_SESSION['error_message'] = 'Purchase number is required.';
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
  } else {
    $purchasePrint = getPurchasePrint($pdo, $_POST['purchase_number']);
    if (empty($purchasePrint)) {
      $_SESSION['error_message'] = 'No Purchases found for the provided purchase number.';
      header('Location: ' . $_SERVER['PHP_SELF']);
      exit();
    }
  }
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
    <?php echo ucfirst(str_replace(".php", "", basename($_SERVER['PHP_SELF']))); ?>
  </title>

  <link rel="stylesheet" href="../../public/css/style.css">
  <link rel="stylesheet" href="../../public/css/pages.css">

  <style>
    :root {
      --blue: #2a2185;
      --white: #fff;
      --gray: #f5f5f5;
      --black1: #222;
      --black2: #999;
    }

    .error {
      color: var(--black1);
      text-align: center;
      margin-bottom: 20px;
    }

    .success {
      color: var(--blue);
      text-align: center;
      margin-bottom: 20px;
    }

    button {
      background-color: var(--blue);
      color: var(--white);
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      margin-bottom: 20px;
      position: center;
    }

    button:hover {
      background-color: var(--black1);
    }

    input[type="text"] {
      padding: 10px;
      border: 2px solid var(--black1);
      border-radius: 5px;
      width: 200px;
      margin-bottom: 10px;
      box-sizing: border-box;
      margin-left: 32%;
      margin-top: 30px;
    }

    .print {
      margin-bottom: 20px;
    }

    .print h2 {
      font-size: 24px;
      margin-bottom: 10px;
      color: var(--blue);
      text-align: center;
    }

    .print p {
      margin: 5px 0;
      text-align: left;
      font-size: 15px;
      margin-top: 30px;
      margin-bottom: 20px;
      color: var(--black1);
      font-weight: bold;
      margin-left: 1%;
    }

    .printTable {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      font-size: 17px;
      margin-top: 30px;
    }

    .printTable th,
    .printTable td {
      border: none;
      padding: 8px;
      text-align: left;
    }

    .printTable th {
      background-color: var(--gray);
      color: var(--blue);
      font-weight: bold;
    }

    .printTable td {
      background-color: var(--gray);
      color: var(--black1);
      font-weight: bold;
      font-size: 15px;
    }

    .printTable tr:nth-child(even) {
      background-color: var(--white);
    }

    .printTable tr:hover {
      background-color: var(--gray);
    }
  </style>

</head>

<body>

  <div class="pages-content">
    <form method="POST" action="">
      <h1>Purchases Printing Form</h1>
      <input type="text" name="purchase_number" placeholder="Enter Purchase Number">
      <button type="submit">Search</button>
    </form>

    <div class="content" id="printContent">
      <div class="print">
        <h2>224-UNLIMITED-TRADE</h2>

        <?php if (isset($purchasePrint) && !empty($purchasePrint)) : ?>
          <?php foreach ($purchasePrint as $purchase) : ?>

            <div class="print">
              <p>
                Product Name: <?php echo htmlspecialchars($purchase['product_name']); ?> 
              </p>
            </div>

            <div class="print">
              <p>
              Company Name: <?php echo htmlspecialchars($purchase['company_name']); ?>
              <p>
            </div>

            <div class="print">
              <p>
              Purchased By: <?php echo htmlspecialchars($purchase['purchased_by']); ?>
              <p>
            </div>

            <table class="printTable">
              <tr>
                <th>Purchase Number</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Discount %</th>
                <th>Purchase Date</th>
                <th>Invoice Amount (GNF)</th>
              </tr>
              <tr>
                <td><?php echo htmlspecialchars($purchase['purchase_number']); ?></td>
                <td><?php echo htmlspecialchars($purchase['purchase_quantity']); ?></td>
                <td><?php echo htmlspecialchars($purchase['unit_price']); ?></td>
                <td><?php echo htmlspecialchars($purchase['discount']); ?></td>
                <td><?php echo htmlspecialchars($purchase['purchase_date']); ?></td>
                <td><?php echo htmlspecialchars($purchase['invoice_amount']); ?></td>
              </tr>
            </table>
            <hr>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="btn" style="margin-left: 77.5%;">
    <button id="printButton" style="margin-left: -42%;"><ion-icon name="print-outline"></ion-icon>Print</button>
    <a href="purchaseList.php">
      <button type="submit" id="productList">Exit</button>
    </a>
  </div>

  <script>
    document.getElementById('printButton').addEventListener('click', function() {
      var printContent = document.getElementById('printContent').innerHTML;
      var originalContent = document.body.innerHTML;

      var printWindow = window.open('', '', 'height=800,width=600');
      printWindow.document.write('<html><head><title>Print Sale</title>');
      printWindow.document.write('<link rel="stylesheet" href="../../public/css/style.css" type="text/css">');
      printWindow.document.write('<link rel="stylesheet" href="../../public/css/pages.css" type="text/css">');
      printWindow.document.write('<style>:root { --blue: #2a2185; --white: #fff; --gray: #f5f5f5; --black1: #222; --black2: #999; } .error { color: var(--black1); font-weight: bold; text-align: center; margin-bottom: 20px; } .success { color: var(--blue); font-weight: bold; text-align: center; margin-bottom: 20px; } button { background-color: var(--blue); color: var(--white); padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-bottom: 20px; position: center; } button:hover { background-color: var(--black1); } input[type="text"] { padding: 10px; border: 2px solid var(--black1); border-radius: 5px; width: 200px; margin-bottom: 10px; box-sizing: border-box; margin-left: 32%; margin-top: 30px; } .print { margin-bottom: 20px; } .print h2 { font-size: 24px; margin-bottom: 10px; color: var(--blue); text-align: center; } .print p { margin: 5px 0; text-align: left; font-size: 15px; margin-top: 30px; margin-bottom: 20px; color: var(--black); font-weight: bold; margin-left: 1%; } .printTable { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 17px; margin-top: 30px; } .printTable th, .printTable td { border: none; padding: 8px; text-align: left; } .printTable th { background-color: var(--gray); color: var(--blue); font-weight: bold; } .printTable td { background-color: var(--gray); color: var(--black1); font-weight: bold; font-size: 15px; } .printTable tr:nth-child(even) { background-color: var(--white); } .printTable tr:hover { background-color: var(--gray); }</style>');
      printWindow.document.write('</head><body >');
      printWindow.document.write(printContent);
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
    });
  </script>

</body>

</html>

<?php
require_once "../../system/footer.php";
?>
