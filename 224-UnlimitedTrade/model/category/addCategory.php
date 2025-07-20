<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['category_number'])) {
  $categoryNumber = htmlentities($_POST['category_number']);
  $categoryName = htmlentities($_POST['category_name']);
  $categoryStatus = htmlentities($_POST['category_status']);

  // validation process
  if ($categoryNumber === '' || $categoryName === '') {
    $_SESSION['error_message'] = "Please fill all fields";
    header("Location: ../../vue/category.php");
    exit();
  }

  // Check if category number already exists
  $checkSql = "SELECT COUNT(*) AS count FROM category WHERE category_number = :category_number";

  $checkStmt = $pdo->prepare($checkSql);

  $checkStmt->execute(['category_number' => $categoryNumber]);
  $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

  if ($result['count'] > 0) {
    $_SESSION['error_message'] = "Category already exists. Please proceed to update";
    header("Location: ../../vue/category.php");
    exit();
  }

  // start the insert process
  $sql = "INSERT INTO category (category_number, category_name, category_status) VALUES (:category_number, :category_name, :category_status)";
  $stmt = $pdo->prepare($sql);

  $stmt->execute(['category_number' => $categoryNumber, 'category_name' => $categoryName, 'category_status' => $categoryStatus]);

  $_SESSION['success_message'] = "Category added successfully";
  header("Location: ../../vue/category.php");
  exit();
} else {
  $_SESSION['error_message'] = "Category addition failed";
  header("Location: ../../vue/category.php");
  exit();
}
