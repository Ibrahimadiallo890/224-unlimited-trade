<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['category_number'])) {
  $categoryNumber = htmlentities($_POST['category_number']);

  // validation process
  if ($categoryNumber === '') {
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
    $deleteCategory = "DELETE FROM category WHERE category_number= :category_number";

    $stmt = $pdo->prepare($deleteCategory);
    $stmt->execute(['category_number' => $categoryNumber]);

    $_SESSION['success_message'] = "Category deleted successfully";
    header("Location: ../../vue/category.php");
    exit();
  } else {
    $_SESSION['error_message'] = "Category deletion failed";
    header("Location: ../../vue/category.php");
    exit();
  }
} else {
  $_SESSION['error_message'] = "Category not found";
  header("Location: ../../vue/category.php");
  exit();
}