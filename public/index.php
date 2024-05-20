<?php
$header = "
<link rel=\" stylesheet\" href=\"/css/home.css\">
<link rel=\" stylesheet\" href=\"/css/widget.css\">";
require "./includes/top.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
  http_response_code(401);
  echo json_encode(array("message" => "You must be logged in to access this."));
  exit;
}

$username = $_SESSION["username"];
$stmt = $conn->prepare("SELECT 
  username,
  email,
  DATE_FORMAT(created_at, '%D %M %Y') AS date_format
  FROM users
  WHERE username = ?
");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<h1>Home</h1>
<div class="widget-container">
  <div class="aside">
    <div class="widget user-info">
      <h2>User Info</h2>
      <span class="field">
        <p>Username</p>
        <h2><?php echo $user["username"] ?></h2>
      </span>
      <span class="field">
        <p>Email</p>
        <h2><?php echo $user["email"] ?></h2>
      </span>
      <span class="field">
        <p>Member Since</p>
        <h2><?php echo $user["date_format"] ?></h2>
      </span>
    </div>
    <div class="widget favourite-teams">
      <h2>Favourite Teams</h2>
    </div>
  </div>
  <div class="widget main">
    <h2>My Bookings</h2>
  </div>
</div>

<?php require "./includes/bottom.php" ?>