<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['settlement_number'])) {
  $settlementNumber = htmlentities($_POST['settlement_number']);
  $customerName = htmlentities($_POST['customer_name']);
  $settledBy = htmlentities($_POST['settled_by']);
  $settlementDate = htmlentities($_POST['settlement_date']);
  $settledAmount = htmlentities($_POST['settled_amount']);

  // validation process
  if ($settlementNumber === '') {
    $_SESSION['error_message'] = "Settlement number not provided";
    header("Location: ../../vue/settlements.php");
    exit();
  };

  if ($customerName === '') {
    $_SESSION['error_message'] = "Customer name not provided";
    header("Location: ../../vue/settlements.php");
    exit();
  };
  
  if ($settledAmount <= 0) {
    $_SESSION['error_message'] = "Amount must be greater than zero";
    header("Location: ../../vue/settlements.php");
    exit();
  }

  // Check if settlement number already exists
  $checkSql = "SELECT COUNT(*) AS count FROM settlements WHERE settlement_number = :settlement_number";
  $checkStmt = $pdo->prepare($checkSql);
  $checkStmt->execute(['settlement_number' => $settlementNumber]);
  $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

  if ($result['count'] > 0) {
    $_SESSION['error_message'] = "Settlement already exists. Please to a new Settlement";
    header("Location: ../../vue/settlements.php");
    exit();
  }

  // Start the insert process
  try {
    $pdo->beginTransaction();

    // Insert into settlements table
    $insertSettlementSql = "INSERT INTO settlements (settlement_number, customer_name, settled_by, settlement_date, settled_amount) VALUES (:settlement_number, :customer_name, :settled_by, :settlement_date, :settled_amount)";
    $insertSettlementStmt = $pdo->prepare($insertSettlementSql);
    $insertSettlementStmt->execute([
      'settlement_number' => $settlementNumber,
      'customer_name' => $customerName,
      'settled_by' => $settledBy,
      'settlement_date' => $settlementDate,
      'settled_amount' => $settledAmount
    ]);

    // Update loan table
    $updateLoanSql = "UPDATE loan SET invoice_amount = invoice_amount - :settled_amount WHERE customer_name = :customer_name";
    $updateLoanStmt = $pdo->prepare($updateLoanSql);
    $updateLoanStmt->execute([
      'settled_amount' => $settledAmount,
      'customer_name' => $customerName
    ]);

    $pdo->commit();
    $_SESSION['success_message'] = "Settlement added and loan updated successfully";
    header("Location: ../../vue/settlements.php");
    exit();
  } catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error_message'] = "Settlement addition failed: " . $e->getMessage();
    header("Location: ../../vue/settlements.php");
    exit();
  }
} else {
  $_SESSION['error_message'] = "Settlement addition failed";
  header("Location: ../../vue/settlements.php");
  exit();
}
?>
