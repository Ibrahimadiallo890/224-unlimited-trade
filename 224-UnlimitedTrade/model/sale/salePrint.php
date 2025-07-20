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
  if (empty($_POST['sale_number'])) {
    $_SESSION['error_message'] = 'Sale number is required.';
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
  } else {
    $salePrint = getSalePrint($pdo, $_POST['sale_number']);
    if (empty($salePrint)) {
      $_SESSION['error_message'] = 'No sales found for the provided sale number.';
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
      <h1>Sales Receipts</h1>
      <input type="text" name="sale_number" placeholder="Enter Sale Number">
      <button type="submit">Search</button>
    </form>

    <div class="content" id="printContent">
      <div class="print">
        <h2>224-UNLIMITED-TRADE</h2>

        <?php if (isset($salePrint) && !empty($salePrint)) : ?>
          <?php foreach ($salePrint as $sale) : ?>
            <div class="print">
              <p>Sale Receipt Number: <?php echo htmlspecialchars($sale['sale_number']); ?></p>
            </div>

            <div class="print">
              <p>Invoice Made By: <?php echo htmlspecialchars($sale['sold_by']); ?> </p>
            </div>

            <div class="print">
              <p>
                Customer Name: <?php echo htmlspecialchars($sale['customer_name']); ?>
              <p>
            </div>

            <div class="print">
              <p>Sale Date: <?php echo date('d/m/Y H:i:s', strtotime($sale['sale_date'])); ?></p>
            </div>

            <table class="printTable">
              <tr>
                <th>Designation</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Discount %</th>
                <th>Total Price(GNF)</th>
              </tr>
              <tr>
                <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                <td><?php echo htmlspecialchars($sale['sale_quantity']); ?></td>
                <td><?php echo htmlspecialchars($sale['unit_price']); ?></td>
                <td><?php echo htmlspecialchars($sale['discount']); ?></td>
                <td><?php echo htmlspecialchars($sale['invoice_amount']); ?></td>
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
    <a href="saleList.php">
      <button type="submit" id="saleList">Exit</button>
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