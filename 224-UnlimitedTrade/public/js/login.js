document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("login-form")
    .addEventListener("submit", function (event) {
      var username = document.getElementById("username").value;
      var password = document.getElementById("password").value;

      if (username.trim() === "" || password.trim() === "") {
        alert("Please fill in both username and password fields.");
        event.preventDefault(); // Prevent form submission
      }
    });
});
