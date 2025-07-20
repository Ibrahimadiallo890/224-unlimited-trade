<?php
session_start();
require_once "../database/database.php";

if (isset($_SESSION['username'])) {
  header('Location: ../vue/dashboard.php');
  exit();
}

if (isset($_POST['username'], $_POST['old_password'], $_POST['new_password'])) {
  $username = htmlentities($_POST['username']);
  $oldPassword = htmlentities($_POST['old_password']);
  $newPassword = htmlentities($_POST['new_password']);

  // Password validation
  if (strlen($newPassword) < 8) {
    $_SESSION['error_message'] = "New password must be at least 8 characters long.";
    header("Location: resetPassword.php");
    exit();
  }

  if (!preg_match("/[A-Z]/", $newPassword) || !preg_match("/[a-z]/", $newPassword) || !preg_match("/[0-9]/", $newPassword) || !preg_match("/[^a-zA-Z0-9]/", $newPassword)) {
    $_SESSION['error_message'] = "New password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    header("Location: resetPassword.php");
    exit();
  }

  // Get user data
  $getUser = "SELECT * FROM login WHERE username = :username";
  $userStatement = $pdo->prepare($getUser);
  $userStatement->execute(['username' => $username]);

  $user = $userStatement->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($oldPassword, $user['password'])) {
    // Check if old and new passwords are different
    if ($oldPassword === $newPassword) {
      $_SESSION['error_message'] = "New password must be different from the old password";
      header("Location: resetPassword.php");
      exit();
    }

    // Update the password in the database
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updatePassword = "UPDATE login SET password = :password WHERE username = :username";
    $updateStatement = $pdo->prepare($updatePassword);
    $updateStatement->execute(['password' => $hashedNewPassword, 'username' => $username]);

    $_SESSION['success_message'] = "Password changed successfully";
    header("Location: resetPassword.php");
    exit();
  } else {
    $_SESSION['error_message'] = "Invalid username or old password";
    header("Location: resetPassword.php");
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
    <h2>224-Unlimited-Trade Reset Password Form</h2>

    <form action="resetPassword.php" method="post">

      <label for="username">Username:</label>
      <input type="text" name="username" id="username" autocomplete="username" required>

      <label for="old_password">Old Password:</label>
      <input type="password" name="old_password" id="old_password" required>

      <label for="new_password">New Password:</label>
      <input type="password" name="new_password" id="new_password" required>

      <div class="btn">
        <button type="submit">Confirm</button>

        <button type="reset" id="reset">Clear</button>

        <button type="button" onclick="location.href='login.php';">Exit</button>
      </div>

    </form>
  </div>
</body>

</html>