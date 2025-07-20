<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['customer_number'])) {
  $customerNumber = htmlentities($_POST['customer_number']);
  $fullName = htmlentities($_POST['full_name']);
  $businessName = htmlentities($_POST['business_name']);
  $email = htmlentities($_POST['email']);
  $phoneNumber = htmlentities($_POST['phone_number']);
  $address = htmlentities($_POST['address']);
  $country = htmlentities($_POST['country']);
  $customerType = htmlentities($_POST['customer_type']);
  $customerStatus = htmlentities($_POST['customer_status']);

  if (isset($fullName) && isset($phoneNumber) && isset($address)  && isset($businessName)) {

    // validate customer number
    if ($customerNumber === '') {
      $_SESSION['error_message'] = "Customer number not provide";
      header("Location: ../../vue/customer.php");
      exit();
    }

    // Regular expression pattern for a typical phone number format
    $pattern = "/^\+?[0-9]+(?:[\s-][0-9]+)*$/";

    if (preg_match($pattern, $phoneNumber)) {
      // Valid phone number
    } else {
      $_SESSION['error_message'] = "Please enter a valid phone number";
      header("Location: ../../vue/customer.php");
      exit();
    }

    // Validate email only if it's provided by user
    if (!empty($email)) {
      if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $_SESSION['error_message'] = "Please enter a valid phone email";
        header("Location: ../../vue/customer.php");
        exit();
      }
    }

    // Validate address
    if ($address == '') {
      $_SESSION['error_message'] = "Please enter the address";
      header("Location: ../../vue/customer.php");
      exit();
    }

    // Check if Full name is empty or not
    if ($fullName == '') {
      $_SESSION['error_message'] = "Please enter full name";
      header("Location: ../../vue/customer.php");
      exit();
    }

    // Check if business name is empty or not
    if ($businessName == '') {
      $_SESSION['error_message'] = "Please enter business name";
      header("Location: ../../vue/customer.php");
      exit();
    }

    // Check if customer number is in the DB
    $customerCheck = "SELECT customer_number FROM customer WHERE customer_number = :customer_number";
    $customerCheckStatement = $pdo->prepare($customerCheck);
    $customerCheckStatement->execute(['customer_number' => $customerNumber]);

    if ($customerCheckStatement->rowCount() > 0) {
      // Start the update process
      $sql = 'UPDATE customer SET full_name = :full_name, business_name = :business_name, email = :email, phone_number = :phone_number, address = :address, country = :country, customer_type = :customer_type, customer_status = :customer_status WHERE customer_number = :customer_number';

      $stmt = $pdo->prepare($sql);
      $stmt->execute(['customer_number' => $customerNumber, 'full_name' => $fullName, 'business_name' => $businessName, 'email' => $email, 'phone_number' => $phoneNumber, 'address' => $address, 'country' => $country, 'customer_type' => $customerType, 'customer_status' => $customerStatus, 'customer_number' => $customerNumber]);

      $_SESSION['success_message'] = "Customer modified successfully";
      header("Location: ../../vue/customer.php");
      exit();
    }
  } else {
    $_SESSION['error_message'] = "Please fill all fields";
    header("Location: ../../vue/customer.php");
    exit();
  }
}
