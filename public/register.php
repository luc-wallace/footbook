<?php
require "./base/top.php";

// https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = $_POST["password"];
  $confirmPassword = $_POST["confirmPassword"];
  $usernameErr = $emailErr = $passwordErr = "";

  if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
    $usernameErr = "Username must only contain letters and numbers";
  }
  if (!preg_match("/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/", $email)) {
    $emailErr = "Invalid email format";
  }
  if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-\.\/\\\]).{8,}$/", $password)) {
    $passwordErr = "Password must between 8 and 40 characters and contain at least: one uppercase letter, one lowercase letter, one digit and one special character";
  }
  if ($password !== $confirmPassword) {
    $passwordErr = "Passwords are not equal";
  }
  if (empty($username_err) && empty($email_err) && empty($password_err)) {
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    if ($stmt->execute()) {
      $stmt->store_result();
      if ($stmt->num_rows() == 1) {
        $username_err = "This username is already taken.";
      } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (email, password, username) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $passwordHash, $username);
        if ($stmt->execute()) {
          header("location: login.php");
        } else {
          echo "Something went wrong. Please try again later.";
        }
      }
    }
  } else {
    echo "Something went wrong. Try again later.";
  }
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <label for="email">Email</label>
  <input type="text" id="email" name="email" />
  <label for="username">Username</label>
  <input type="text" id="username" name="username" />
  <label for="password">Password</label>
  <input type="text" id="password" name="password">
  <label for="password">Confirm Password</label>
  <input type="text" id="confirmPassword" name="confirmPassword">
  <input type="submit" value="Submit" />
  <p>Already have an account? <a href="/login.php">Log in</a>.
</form>

<?php
require "./base/bottom.php";
?>