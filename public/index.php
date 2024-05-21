<?php
$header = "
<link rel=\" stylesheet\" href=\"/css/home.css\">
<link rel=\" stylesheet\" href=\"/css/widget.css\">";
require "./includes/top.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
  header("location: /login.php");
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

$stmt = $conn->prepare("SELECT
t.name AS name,
t.logo_url AS logo_url,
COUNT(*) AS count
FROM (
SELECT home_team AS team_name FROM bookings WHERE username = ?
UNION ALL
SELECT away_team AS team_name FROM bookings WHERE username = ?
) AS user_bookings
JOIN teams t ON user_bookings.team_name = t.name
GROUP BY t.name
ORDER BY count DESC
LIMIT 6;
");
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();
$favouriteTeams = $result->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT
home.name AS home_team,
away.name AS away_team,
home.logo_url AS home_logo,
away.logo_url AS away_logo,
home.stadium AS stadium,
DATE_FORMAT(_match.date, '%a %D %b') AS date_format,
DATE_FORMAT(_match.date, '%k:%i') AS time_format,
bookings.*
FROM bookings
JOIN teams AS home ON bookings.home_team = home.name
JOIN teams AS away ON bookings.away_team = away.name
JOIN matches AS _match ON bookings.home_team = _match.home_team AND bookings.away_team = _match.away_team
WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC)
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
      <span class="field">
        <p>Bookings Made</p>
        <h2><?php echo sizeof($bookings) ?></h2>
      </span>
    </div>
    <div class="widget">
      <h2>Favourite Teams</h2>
      <div class="favourite-teams">
        <?php foreach ($favouriteTeams as $team): ?>
          <div class="team">
            <img src="<?php echo $team["logo_url"] ?>" />
            <div class="team-details">
              <p class="team-name"><?php echo $team["name"] ?></p>
              <p class="count"><?php echo $team["count"] . " booking" . ($team["count"] > 1 ? "s" : "") ?></p>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
  <div class="widget main">
    <h2>My Bookings</h2>
    <div class="bookings">
      <?php foreach ($bookings as $booking): ?>
        <a href="/matches.php?home=<?php echo urlencode($booking["home_team"]) ?>&away=<?php echo urlencode($booking["away_team"]) ?>"
          class="booking">
          <span class="image-stack">
            <img src="<?php echo $booking["home_logo"] ?>">
            <img src="<?php echo $booking["away_logo"] ?>">
          </span>
          <h2 class="name"><?php echo $booking["home_team"] ?> vs <?php echo $booking["away_team"] ?></h2>
          <h2 class="date"><?php echo $booking["date_format"] ?></h2>
          <h2 class="time"><?php echo $booking["time_format"] ?></h2>
          <h2 class="stadium"><?php echo $booking["stadium"] ?></h2>
        </a>
        <hr class="divider">
      <?php endforeach ?>
    </div>
  </div>
</div>

<?php require "./includes/bottom.php" ?>