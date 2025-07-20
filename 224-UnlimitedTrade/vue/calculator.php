<?php
session_start();
require_once "../system/header.php";

if (!isset($_SESSION['username'])) {
  header('Location: ../loginForm/login.php');
  exit();
}
?>

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
  <link rel="stylesheet" href="../public/css/pages.css">
  <style>
    :root {
      --blue: #2a2185;
      --white: #fff;
      --gray: #f5f5f5;
      --black1: #222;
      --black2: #999;
    }

    .calculator {
      width: 600px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid var(--black2);
      border-radius: 5px;
      background-color: var(--blue);
    }

    .calculator form {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      grid-gap: 10px;
    }

    .main-input {
      grid-column: span 4;
      padding: 10px;
      font-size: 16px;
      border: 1px solid var(--black2);
      border-radius: 5px;
      background-color: var(--white);
    }

    .num-btn,
    .calculator-btn,
    .clear,
    .equal {
      padding: 10px;
      font-size: 16px;
      border: 1px solid var(--black2);
      border-radius: 5px;
      background-color: var(--white);
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .num-btn:hover,
    .calculator-btn:hover,
    .clear:hover,
    .equal:hover {
      background-color: var(--gray);
    }
  </style>
</head>

<body>
  <div class="pages-content">
    <h1>System Calculator</h1>

    <div class="calculator">
      <form id="calculatorForm">
        <input type="text" class="main-input" id="display" disabled>

        <!-- Buttons -->
        <button type="button" class="num-btn" onclick="addToDisplay('7')">7</button>
        <button type="button" class="num-btn" onclick="addToDisplay('8')">8</button>
        <button type="button" class="num-btn" onclick="addToDisplay('9')">9</button>
        <button type="button" class="calculator-btn" onclick="addToDisplay('+')">+</button>

        <button type="button" class="num-btn" onclick="addToDisplay('4')">4</button>
        <button type="button" class="num-btn" onclick="addToDisplay('5')">5</button>
        <button type="button" class="num-btn" onclick="addToDisplay('6')">6</button>
        <button type="button" class="calculator-btn" onclick="addToDisplay('-')">-</button>

        <button type="button" class="num-btn" onclick="addToDisplay('1')">1</button>
        <button type="button" class="num-btn" onclick="addToDisplay('2')">2</button>
        <button type="button" class="num-btn" onclick="addToDisplay('3')">3</button>
        <button type="button" class="calculator-btn" onclick="addToDisplay('*')">*</button>

        <button type="button" class="clear" onclick="clearDisplay()">Clear</button>
        <button type="button" class="num-btn" onclick="addToDisplay('0')">0</button>
        <button type="button" class="equal" onclick="calculate()">=</button>
        <button type="button" class="calculator-btn" onclick="addToDisplay('/')">/</button>

      </form>
    </div>
  </div>

  <script>
    // Function to add characters to the display with comma formatting
    function addToDisplay(value) {

      var display = document.getElementById('display');
      var currentValue = display.value;

      if (currentValue === 'Error') {
        currentValue = '';
      }
      // Append the new value
      currentValue += value;

      // Format the value with commas for thousands
      var formattedValue = formatNumberWithCommas(currentValue);
      display.value = formattedValue;
    }

    // Function to clear the display
    function clearDisplay() {
      document.getElementById('display').value = '';
    }

    // Function to perform calculation
    function calculate() {
      try {
        // Remove commas from the input value before evaluation
        var expression = document.getElementById('display').value.replace(/,/g, '');

        // Evaluate the expression and format the result with commas for thousands
        var result = eval(expression).toLocaleString();
        document.getElementById('display').value = result;

      } catch (error) {
        document.getElementById('display').value = 'Error';
      }
    }

    function formatNumberWithCommas(number) {
      // Remove existing commas and then reformat with correct commas
      return number.replace(/,/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
  </script>

</body>

</html>

<?php
require_once "../system/footer.php";
?>

