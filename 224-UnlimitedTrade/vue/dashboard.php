<?php
session_start();
require_once "../system/header.php";
require_once "../database/database.php";
require_once "../database/function.php";

if (!isset($_SESSION['username'])) {
  header('Location: ../loginForm/login.php');
  exit();
}
?>

<!-- main body cards start -->
<div class="cardBox">

  <div class="card">
    <div>
      <div class="number"><?php echo number_format(getTotalSales()['total_sales'], 0, '.', ',') . ' GNF'; ?></div>
      <div class="cardName">Sales Earnings</div>
    </div>
    <div class="iconBox">
      <ion-icon name="cash-outline"></ion-icon>
    </div>
  </div>

  <div class="card">
    <div>
      <div class="number"><?php echo getAllProducts()['number'] ?></div>
      <div class="cardName">Number of Products</div>
    </div>
    <div class="iconBox">
      <ion-icon name="pricetags-outline"></ion-icon>
    </div>
  </div>

  <div class="card">
    <div>
      <div class="number"><?php echo getAllSales()['number'] ?></div>
      <div class="cardName">Number of Sales</div>
    </div>
    <div class="iconBox">
      <ion-icon name="cash-outline"></ion-icon>
    </div>
  </div>

  <div class="card">
    <div>
      <div class="number"><?php echo number_format(getDailySales()['daily_sales'], 0, '.', ',') . ' GNF'; ?></div>
      <div class="cardName">Daily Income</div>
    </div>
    <div class="iconBox">
      <ion-icon name="cash-outline"></ion-icon>
    </div>
  </div>

  <div class="card">
    <div>
      <div class="number"><?php echo number_format(getTotalLoans()['total_loans'], 0, '.', ',') . ' GNF'; ?></div>
      <div class="cardName">Amount Loaned</div>
    </div>
    <div class="iconBox">
      <ion-icon name="cash-outline"></ion-icon>
    </div>
  </div>

  <div class="card">
    <div>
      <div class="number"><?php echo getAllPurchases()['number'] ?></div>
      <div class="cardName">Number of Purchases</div>
    </div>
    <div class="iconBox">
      <ion-icon name="card-outline"></ion-icon>
    </div>
  </div>

  <div class="card">
    <div>
      <div class="number">5</div>
      <div class="cardName">Number of Loans</div>
    </div>
    <div class="iconBox">
      <ion-icon name="pricetag-outline"></ion-icon>
    </div>
  </div>

  <div class="card">
    <div>
      <div class="number"><?php echo number_format(getTotalPurchases()['total_purchases'], 0, '.', ',') . ' GNF'; ?></div>
      <div class="cardName">Purchase Expenses</div>
    </div>
    <div class="iconBox">
      <ion-icon name="cash-outline"></ion-icon>
    </div>
  </div>

</div>
<!-- main body cards end -->

<!-- main body below cards (sales) start -->
<div class="details">
  <div class="recentSales">

    <div class="cardHeader">
      <h2>Recent Sales</h2>
      <a href="sale.php" class="btn">View All</a>
    </div>

    <table>
      <thead>
        <tr>
          <td>Sale Number</td>
          <td>Product Name</td>
          <td>Customer Name</td>
          <td>Quantity</td>
          <td>Total Price</td>
        </tr>
      </thead>

      <tbody>
        <?php
        $sql = "SELECT sale_number, product_name, customer_name, sale_quantity, invoice_amount FROM sale ORDER BY sale_number DESC LIMIT 5";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
            <td> <?php echo $row['sale_number']; ?> </td>
            <td> <?php echo $row['product_name']; ?> </td>
            <td> <?php echo $row['customer_name']; ?> </td>
            <td> <?php echo $row['sale_quantity']; ?> </td>
            <td> <?php echo $row['invoice_amount']; ?> </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>

  </div>

  <!-- customer list start -->
  <div class="recentCustomers">

    <div class="cardHeader">
      <h2>Recent Customers</h2>
      <a href="customer.php" class="btn">View All</a>
    </div>

    <table>
      <thead>
        <tr>
          <td>Customer Number</td>
          <td>Customer Name</td>
          <td>Status</td>
        </tr>
      </thead>

      <tbody>
        <?php
        $sql = "SELECT customer_number, full_name, customer_status FROM customer ORDER BY customer_number DESC LIMIT 5";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
            <td> <?php echo $row['customer_number']; ?> </td>
            <td> <?php echo $row['full_name']; ?> </td>
            <td> <?php echo $row['customer_status']; ?> </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>

  </div>
  <!-- customer list end -->
</div>
<!-- main body below cards (sale) end -->

<!-- main body below cards (purchase) start -->
<div class="details">
  <div class="recentSales">

    <div class="cardHeader">
      <h2>Recent Purchases</h2>
      <a href="purchase.php" class="btn">View All</a>
    </div>

    <table>
      <thead>
        <tr>
          <td>Purchase Number</td>
          <td>Product Name</td>
          <td>Company Name</td>
          <td>Quantity</td>
          <td>Total Price</td>
        </tr>
      </thead>

      <tbody>
        <?php
        $sql = "SELECT purchase_number, product_name, company_name, purchase_quantity, invoice_amount FROM purchase ORDER BY purchase_number DESC LIMIT 5";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
            <td> <?php echo $row['purchase_number']; ?> </td>
            <td> <?php echo $row['product_name']; ?> </td>
            <td> <?php echo $row['company_name']; ?> </td>
            <td> <?php echo $row['purchase_quantity']; ?> </td>
            <td> <?php echo $row['invoice_amount']; ?> </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>

  </div>

  <!-- vendor list start -->
  <div class="recentCustomers">

    <div class="cardHeader">
      <h2>Recent Vendors</h2>
      <a href="vendor.php" class="btn">View All</a>
    </div>

    <table>
      <thead>
        <tr>
          <td>Vendor Number</td>
          <td>Vendor Name</td>
          <td>Status</td>
        </tr>
      </thead>

      <tbody>
        <?php
        $sql = "SELECT vendor_number, full_name, vendor_status FROM vendor ORDER BY vendor_number DESC LIMIT 5";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
            <td> <?php echo $row['vendor_number']; ?> </td>
            <td> <?php echo $row['full_name']; ?> </td>
            <td> <?php echo $row['vendor_status']; ?> </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>

  </div>
  <!-- vendor list end -->
</div>
<!-- main body below cards (purchase) end -->

<!-- main body below cards (product) start -->
<div class="details">
  <div class="recentSales">

    <div class="cardHeader">
      <h2>Recent Products</h2>
      <a href="product.php" class="btn">View All</a>
    </div>

    <table>
      <thead>
        <tr>
          <td>Product Number</td>
          <td>Product Name</td>
          <td>Quantity</td>
          <td>Unit Price</td>
          <td>Status</td>
        </tr>
      </thead>

      <tbody>
        <?php
        $sql = "SELECT product_number, product_name, product_quantity, unit_price, product_status FROM product ORDER BY product_number DESC LIMIT 5";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
            <td> <?php echo $row['product_number']; ?> </td>
            <td> <?php echo $row['product_name']; ?> </td>
            <td> <?php echo $row['product_quantity']; ?> </td>
            <td> <?php echo $row['unit_price']; ?> </td>
            <td> <?php echo $row['product_status']; ?> </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- product list start -->
  <div class="recentCustomers">

    <div class="cardHeader">
      <h2>Recent Categories</h2>
      <a href="Category.php" class="btn">View All</a>
    </div>

    <table>
      <thead>
        <tr>
          <td>Category Number</td>
          <td>Category Name</td>
          <td>Status</td>
        </tr>
      </thead>

      <tbody>
        <?php
        $sql = "SELECT category_number, category_name, category_status FROM category ORDER BY category_number DESC LIMIT 5";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
            <td> <?php echo $row['category_number']; ?> </td>
            <td> <?php echo $row['category_name']; ?> </td>
            <td> <?php echo $row['category_status']; ?> </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>

  </div>
  <!-- product list end -->
</div>
<!-- main body below cards (product) end -->

</div>
<!-- main body topbar end-2 -->

</div>

<!-- navigation end -->
<?php
require_once "../system/footer.php";
?>
