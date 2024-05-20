<?php
require "./includes/base.php";
?>

<!DOCTYPE html>
<html lang=" en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking System</title>
  <link rel="stylesheet" href="./css/index.css" />
  <script src="./js/index.js" defer></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    rel="stylesheet">
  <?php
  if (isset($header))
    echo $header;
  ?>
</head>

<body>
  <div id="sidebar">
    <a href="/" class="logo"><i class="fa-solid fa-futbol"></i>Footbook</a>
    <?php if (isset($_SESSION["username"])): ?>
      <p class="subtext">Welcome,</p>
      <h4><?php echo $_SESSION["username"]; ?></h4>
    <?php else: ?>
      <h4>Not logged in</h4>
    <?php endif; ?>
    <hr>
    <h3>Dashboard</h3>
    <ul class="navlinks">
      <a href="/"><i class="fa-solid fa-house"></i>Home<i class="fa-solid fa-angle-down"></i></a>
      <a href="/discover.php"><i class="fa-solid fa-compass"></i>Discover Matches<i
          class="fa-solid fa-angle-down"></i></a>
      <?php if (isset($_SESSION["username"])): ?>
        <a href="/history.php"><i class="fa-solid fa-clock-rotate-left"></i>Booking History<i
            class="fa-solid fa-angle-down"></i></a>
      <?php endif ?>
    </ul>
    <hr>
    <h3>My Account</h3>

    <ul class="navlinks">
      <?php if (isset($_SESSION["username"])): ?>
        <a href="/logout.php"><i class="fa-solid fa-right-from-bracket"></i>Log out<i
            class="fa-solid fa-angle-down"></i></a>
      <?php else: ?>
        <a href="/login.php"><i class="fa-solid fa-right-from-bracket"></i>Log in<i
            class="fa-solid fa-angle-down"></i></a>
      <?php endif; ?>
    </ul>
  </div>
  <main>