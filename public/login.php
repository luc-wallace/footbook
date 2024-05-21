<?php
$header = "
<link rel=\"stylesheet\" href=\"./css/form.css\">
";
include ("./includes/top.php");
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: /");
  exit;
}

?>

<h1>Log in</h1>

<div class="error">
  <?php
  // https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    if ($stmt->execute()) {
      $result = $stmt->get_result();
      if ($result->num_rows == 0) {
        echo "Invalid email or password";
      } else {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
          session_start();
          $_SESSION["loggedin"] = true;
          $_SESSION["email"] = $user["email"];
          $_SESSION["username"] = $user["username"];
          header("location: /");
        } else {
          echo "Invalid email or password";
        }
      }
    }
  }
  ?>
</div>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
  <label for="email">Email</label>
  <input type="text" id="email" name="email" placeholder="Email..." />
  <label for="password">Password</label>
  <input type="password" id="password" name="password" placeholder="Password...">
  <input type="submit" value="Submit" />
  <a href="/register.php">Don't have an account? Sign up.</a>
</form>

<?php include ("./includes/bottom.php") ?>