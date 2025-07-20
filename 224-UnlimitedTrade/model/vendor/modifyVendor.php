<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['vendor_number'])) {
  $vendorNumber = htmlentities($_POST['vendor_number']);
  $fullName = htmlentities($_POST['full_name']);
  $companyName = htmlentities($_POST['company_name']);
  $email = htmlentities($_POST['email']);
  $phoneNumber = htmlentities($_POST['phone_number']);
  $address = htmlentities($_POST['address']);
  $country = htmlentities($_POST['country']);
  $vendorType = htmlentities($_POST['vendor_type']);
  $vendorStatus = htmlentities($_POST['vendor_status']);

  if (isset($fullName) && isset($phoneNumber) && isset($address)  && isset($companyName)) {

    // validate vendor number
    if ($vendorNumber === '') {
      $_SESSION['error_message'] = "Vendor number not provide";
      header("Location: ../../vue/vendor.php");
      exit();
    }

    // Regular expression pattern for a typical phone number format
    $pattern = "/^\+?[0-9]+(?:[\s-][0-9]+)*$/";

    if (preg_match($pattern, $phoneNumber)) {
      // Valid phone number
    } else {
      $_SESSION['error_message'] = "Please enter a valid phone number";
      header("Location: ../../vue/vendor.php");
      exit();
    }

    // Validate email only if it's provided by user
    if (!empty($email)) {
      if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $_SESSION['error_message'] = "Please enter a valid phone email";
        header("Location: ../../vue/vendor.php");
        exit();
      }
    }

    // Validate address
    if ($address == '') {
      $_SESSION['error_message'] = "Please enter the address";
      header("Location: ../../vue/vendor.php");
      exit();
    }

    // Check if Full name is empty or not
    if ($fullName == '') {
      $_SESSION['error_message'] = "Please enter full name";
      header("Location: ../../vue/vendor.php");
      exit();
    }

    // Check if vendor number is in the DB
    $vendorCheck = "SELECT vendor_number FROM vendor WHERE vendor_number = :vendor_number";
    $vendorCheckStatement = $pdo->prepare($vendorCheck);
    $vendorCheckStatement->execute(['vendor_number' => $vendorNumber]);

    if ($vendorCheckStatement->rowCount() > 0) {
      // Start the update process
      $sql = 'UPDATE vendor SET full_name = :full_name, company_name = :company_name, email = :email, phone_number = :phone_number, address = :address, country = :country, vendor_type = :vendor_type, vendor_status = :vendor_status WHERE vendor_number = :vendor_number';

      $stmt = $pdo->prepare($sql);
      $stmt->execute(['vendor_number' => $vendorNumber, 'full_name' => $fullName, 'company_name' => $companyName, 'email' => $email, 'phone_number' => $phoneNumber, 'address' => $address, 'country' => $country, 'vendor_type' => $vendorType, 'vendor_status' => $vendorStatus, 'vendor_number' => $vendorNumber]);

      $_SESSION['success_message'] = "Vendor modified successfully";
      header("Location: ../../vue/vendor.php");
      exit();
    }
  } 
  else {
    $_SESSION['error_message'] = "Please fill all fields";
    header("Location: ../../vue/vendor.php");
    exit();
  }
}
