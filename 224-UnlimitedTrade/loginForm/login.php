<?php
session_start();
require_once "../database/database.php";

if (isset($_SESSION['username'])) {
  header('Location: ../vue/dashboard.php');
  exit();
}

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = htmlentities($_POST['username']);
  $password = htmlentities($_POST['password']);

  // Get user data
  $getUser = "SELECT * FROM login WHERE username = :username";
  $userStatement = $pdo->prepare($getUser);
  $userStatement->execute(['username' => $username]);

  $user = $userStatement->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {

    $_SESSION['username'] = $username;

    // Redirect based on user type
    if ($user['user_type'] == 'Admin') {
      $_SESSION['success_message'] = "Admin loggedIn successfully";
      header("Location: ../vue/dashboard.php");
      exit;
    } elseif ($user['user_type'] == 'User') {
      $_SESSION['success_message'] = "User loggedIn successfully";
      header("Location: ../vue/dashboard-2.php");
      exit;
    } elseif ($user['user_type'] == 'Manager') {
      $_SESSION['success_message'] = "Manager loggedIn successfully";
      header("Location: ../vue/dashboard-3.php");
      exit;
    } else {
      $_SESSION['error_message'] = "Invalid user role";
      header("Location: login.php");
      exit();
    }
  } else {
    $_SESSION['error_message'] = "User does not exist. Please proceed to register";
    header("Location: login.php");
    exit();
  }
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
    <h2>224-Unlimited-Trade Login Page</h2>

    <form action="login.php" method="POST" id="login-form">

      <label for="username">Username:</label>
      <input type="text" name="username" id="username" autocomplete="username" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>

      <div class="btn">
        <button type="submit">Sign In</button>

        <button type="button" onclick="location.href='register.php';">Sign Up</button>

        <button type="button" onclick="location.href='resetPassword.php';">Reset Password</button>

        <button type="button" onclick="location.href='../vue/home.php';">Home</button>
        
      </div>
    </form>
  </div>

  <!-- JavaScript file -->
  <script src="../public/js/login.js"></script>

</body>

</html>