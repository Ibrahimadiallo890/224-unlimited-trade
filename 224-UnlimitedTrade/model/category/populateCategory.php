<?php
session_start();
require_once "../../database/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $categoryNumber = $_POST['category_number'];

  // Prepare and execute SQL query to fetch category details
  $sql = "SELECT category_name, category_status FROM category WHERE category_number = :categoryNumber";
  $stmt = $pdo->prepare($sql);

  $stmt->execute(['categoryNumber' => $categoryNumber]);
  $categoryDetails = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$categoryDetails) {
    echo json_encode(['error' => true]);
  } 
  else {
    echo json_encode($categoryDetails);
  }
}
