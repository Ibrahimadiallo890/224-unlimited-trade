<?php
session_start();
require_once "../../database/database.php";

if (isset($_POST['vendor_number'])) {
  $vendorNumber = htmlentities($_POST['vendor_number']);

  // Sanitize product number
  if ($vendorNumber === '') {
    $_SESSION['error_message'] = "Vendor number not provided";
    header("Location: ../../vue/vendor.php");
    exit();
  }

  // Check if customerNumber is in the DB
  $checkVendorNumber = "SELECT vendor_number FROM vendor WHERE vendor_number = :vendor_number";

  $checkVendorNumberStatement = $pdo->prepare($checkVendorNumber);
  $checkVendorNumberStatement->execute(['vendor_number' => $vendorNumber]);

  if ($checkVendorNumberStatement->rowCount() > 0) {

    // Start the deletion process
    $deleteVendor = "DELETE FROM vendor WHERE vendor_number = :vendor_number";

    $deleteVendorStatement = $pdo->prepare($deleteVendor);
    $deleteVendorStatement->execute(['vendor_number' => $vendorNumber]);

    $_SESSION['success_message'] = "Vendor deleted successfully";
    header("Location: ../../vue/vendor.php");
    exit();
  } else {
    $_SESSION['error_message'] = "Vendor does not exist";
    header("Location: ../../vue/vendor.php");
    exit();
  }
} else {
  $_SESSION['error_message'] = "Vendor number not provided";
  header("Location: ../../vue/vendor.php");
  exit();
}
