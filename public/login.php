<?php include ("./base/top.php") ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
  <label for="username">Username</label>
  <input type="text" id="username" name="username" />
  <label for="password">Password</label>
  <input type="text" id="password" name="password">
  <p>Don't have an account? <a href="/register.php">Sign up</a>.
</form>

<?php include ("./base/bottom.php") ?>