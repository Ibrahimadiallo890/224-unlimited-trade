<?php
session_start();
require_once "../database/database.php";

if (isset($_SESSION['username'])) {
  header('Location: ../vue/dashboard.php');
  exit();
}

if (isset($_POST['user_type'])) {
  $userType = htmlentities($_POST['user_type']);
  $username = htmlentities($_POST['username']);
  $password = htmlentities($_POST['password']);

  // Password validation
  if (strlen($password) < 8) {
    $_SESSION['error_message'] = "Password must be at least 8 characters long.";
    header("Location: register.php");
    exit();
  }

  // Check for additional complexity requirements
  if (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[^a-zA-Z0-9]/", $password)) {
    $_SESSION['error_message'] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    header("Location: register.php");
    exit();
  }

  // Hash the password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // check if username exist
  $userCheck = "SELECT COUNT(*) AS count FROM login WHERE username = :username";
  $checkStatement = $pdo->prepare($userCheck);
  $checkStatement->execute([':username' => $username]);
  $result = $checkStatement->fetch(PDO::FETCH_ASSOC);

  if ($result['count'] > 0) {
    $_SESSION['error_message'] = "Username already exists.";
    header("Location: register.php");
    exit();
  }

  // start the insertion process
  $insertUser = "INSERT INTO login (user_type, username, password) VALUES (:user_type, :username, :password)";

  $insertStatement = $pdo->prepare($insertUser);
  $insertStatement->execute(['user_type' => $userType, 'username' => $username, 'password' => $hashedPassword]);

  $_SESSION['success_message'] = "User registered successfully";
  header("Location: register.php");
  exit();
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
  <link rel="stylesheet" href="../public/css/login.css">
</head>

<body>

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

  <div class="login-container">
    <h2>224-Unlimited-Trade Registration Form</h2>

    <form action="register.php" method="post">

      <label for="user_type">User Type</label>
      <select name="user_type" id="user_type">
        <?php include("../system/userList.html"); ?>
      </select>

      <label for="username">Username:</label>
      <input type="text" name="username" id="username" autocomplete="username" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>

      <div class="btn">
        <button type="submit">Confirm</button>

        <button type="reset" id="reset">Clear</button>

        <button type="button" onclick="location.href='login.php';">Exit</button>
      </div>

    </form>
  </div>
</body>

</html>
