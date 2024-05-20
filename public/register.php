<?php $header = "
<link rel=\" stylesheet\" href=\"./css/form.css\">
  ";
require "./includes/top.php";
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: /");
  exit;
}
?>

<h1>Register</h1>

<div class="error">
  <?php
  // https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $usernameErr = $emailErr = $passwordErr = "";

    if (!preg_match("/^[a-zA-Z0-9_]{4,}$/", $username)) {
      $usernameErr = "Username must only contain letters and numbers, and must be at least 4 characters";
    }
    if (!preg_match("/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/", $email)) {
      $emailErr = "Invalid email format";
    }
    if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-\.\/\\\]).{8,}$/", $password)) {
      $passwordErr = "Password must between 8 and 40 characters and contain at least: one uppercase letter, one lowercase
  letter, one digit and one special character";
    }
    if ($password !== $confirmPassword) {
      $passwordErr = "Passwords are not equal";
    }
    if (empty($usernameErr) && empty($emailErr) && empty($passwordErr)) {
      $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
      $stmt->bind_param("s", $email);
      if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows() == 1) {
          $usernameErr = "This username is already taken.";
        } else {
          $passwordHash = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
          $stmt->bind_param("sss", $username, $email, $passwordHash);
          if ($stmt->execute()) {
            header("location: login.php");
          } else {
            echo "Something went wrong. Please try again later.";
          }
        }
      }
    } else {
      echo join("<br>", array($emailErr, $usernameErr, $passwordErr));
    }
  }
  ?>
</div>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <label for="email">Email</label>
  <input type="text" id="email" name="email" value="<?php if (isset($email))
    echo $email ?>" placeholder="Email..." />
    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?php if (isset($username))
    echo $username ?>" placeholder="Username...">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Password...">
    <label for="password">Confirm Password</label>
    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password...">
    <input type="submit" value="Submit" />
    <a href="/login.php">Already have an account? Log in.</a>
  </form>

  <?php
  require "./includes/bottom.php";
  ?>