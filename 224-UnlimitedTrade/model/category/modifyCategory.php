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

  // check if category reference is in db
  $checkCategoryNumber = "SELECT category_number FROM category WHERE category_number = :category_number";

  $sql = $pdo->prepare($checkCategoryNumber);
  $sql->execute(['category_number' => $categoryNumber]);

  if ($sql->rowCount() > 0) {
    // construct the modify query
    $modifyCategory = "UPDATE category SET category_name= :category_name, category_status= :category_status WHERE category_number= :category_number";

    $stmt = $pdo->prepare($modifyCategory);
    $stmt->execute(['category_number' => $categoryNumber, 'category_name' => $categoryName, 'category_status' => $categoryStatus]);

    $_SESSION['success_message'] = "Category modified successfully";
    header("Location: ../../vue/category.php");
    exit();
  } else {
    $_SESSION['error_message'] = "Category modification failed";
    header("Location: ../../vue/category.php");
    exit();
  }
} else {
  $_SESSION['error_message'] = "Category not found";
  header("Location: ../../vue/category.php");
  exit();
}