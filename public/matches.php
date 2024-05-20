<?php $header = "
<link rel=\" stylesheet\" href=\"/css/matches.css\">
<link rel=\" stylesheet\" href=\"/css/widget.css\">
<script src=\"/js/match.js\" defer></script>
";
require "./includes/top.php";

$match = null;
$home = $_GET["home"] ?? null;
$away = $_GET["away"] ?? null;
if (isset($home) && isset($away)) {
  $stmt = $conn->prepare("SELECT
  matches.*,
  home.logo_url AS home_logo,
  home.stadium AS stadium,
  away.logo_url AS away_logo,
  DATE_FORMAT(date, '%a %D %b') AS date_format,
  (SELECT base_price FROM price_bands WHERE (home.size + away.size) BETWEEN min_size AND max_size) AS base_price
  FROM matches JOIN teams AS home ON matches.home_team = home.name
  JOIN teams AS away ON matches.away_team = away.name
  WHERE home_team = ? AND away_team = ?");
  $stmt->bind_param("ss", $home, $away);
  $stmt->execute();
  $result = $stmt->get_result();
  $match = $result->fetch_assoc();
  $valid = $result->num_rows > 0;

  $stmt = $conn->prepare("SELECT
  matches.*,
  home.logo_url AS home_logo,
  away.logo_url AS away_logo,
  DATE_FORMAT(date, '%a %D %b') AS date_format
  FROM matches
  JOIN teams AS home ON matches.home_team = home.name
  JOIN teams AS away ON matches.away_team = away.name
  WHERE home_team = ? OR home_team = ? OR away_team = ? OR away_team = ? ORDER BY RAND() LIMIT 5");
  $stmt->bind_param("ssss", $home, $home, $away, $away);
  $stmt->execute();
  $result = $stmt->get_result();
  $otherMatches = $result->fetch_all(MYSQLI_ASSOC);

  $stmt = $conn->prepare("SELECT row FROM bookings WHERE home_team = ? AND away_team = ?");
  $stmt->bind_param("ss", $home, $away);
  $stmt->execute();
  $result = $stmt->get_result();

  $matches = $result->fetch_all(MYSQLI_ASSOC);
  $takenRows = array();
  foreach ($matches as $row) {
    array_push($takenRows, $row["row"]);
  }
}
?>

<?php if ($valid && (isset($home) && isset($away))): ?>
  <h1><?php echo $match["home_team"] ?> vs <?php echo $match["away_team"] ?> tickets</h1>
  <div class="widget-container">
    <div class="aside">
      <div class="widget match-info">
        <div class="logos">
          <img src="<?php echo $match["home_logo"] ?>">
          <h1>vs</h1>
          <img src="<?php echo $match["away_logo"] ?>">
        </div>
        <hr>
        <span class="field">
          <p>Location</p>
          <h2><?php echo $match["stadium"] ?></h2>
        </span>
        <span class="field">
          <p>Date</p>
          <h2><?php echo $match["date_format"] ?></h2>
        </span>
        <span class="field">
          <p>Referee</p>
          <h2><?php echo $match["referee"] ?></h2>
        </span>
      </div>
      <div class="widget other-matches">
        <h2>Other Matches</h2>
        <div class="matches-list">
          <?php foreach ($otherMatches as $omatch): ?>
            <a class="match"
              href="/matches.php?home=<?php echo urlencode($omatch["home_team"]) ?>&away=<?php echo urlencode($omatch["away_team"]) ?>">
              <span class="image-stack">
                <img src="<?php echo $omatch["home_logo"] ?>">
                <img src="<?php echo $omatch["away_logo"] ?>">
              </span>
              <div>
                <p>
                  <?php
                  $text = $omatch["home_team"] . " vs " . $omatch["away_team"];
                  if (strlen($text) > 18) {
                    $text = substr($text, 0, 18) . "...";
                  }
                  echo $text;
                  ?>
                </p>
                <p><?php echo $omatch["date_format"] ?></p>
              </div>
            </a>
          <?php endforeach ?>
        </div>
      </div>
    </div>
    <div class="widget main">
      <div class="main-row">
        <div class="row-select">
          <h2>Row</h2>
          <div class="rows">
            <?php for ($i = 0; $i < 24; $i++): ?>
              <?php
              $row = array("A", "B", "C")[floor($i / 8)] . $i % 8 + 1;
              ?>
              <div class="row<?php if (in_array($row, $takenRows))
                echo " taken" ?>"><?php echo $row; ?></div>
            <?php endfor; ?>
          </div>
        </div>
        <hr class="vertical">
        <div class="ticket-type">
          <h2>Ticket Type</h2>
          <?php
          $options = array("Standard", "Padded", "Deluxe");
          $prices = array(
            $match["base_price"],
            $match["base_price"] + 15,
            round($match["base_price"] * 1.75)
          );
          for ($i = 0; $i < 3; $i++): ?>
            <div class="ticket-group">
              <h2>£<?php echo $prices[$i] ?></h2>
              <h3><?php echo $options[$i] ?></h3>
              <button value="<?php echo $options[$i] ?>" class="button <?php "selected" ? $i == 1 : "" ?>">Select</button>
            </div>
          <?php endfor; ?>
        </div>
      </div>
      <div class="submit-row">
        <h2>Personal Information</h2>
        <label for="first-name">First Name</label>
        <input type="text" id="first-name" placeholder="First Name...">
        <label for="last-name">Last Name</label>
        <input type="text" id="last-name" placeholder="Last Name...">
        <span>
          <input id="terms" type="checkbox">I agree to the terms and conditions
        </span>
        <?php if (isset($_SESSION["username"])): ?>
          <button id="buy">Purchase</button>
        <?php else: ?>
          <a href="/login.php">Log in to buy tickets.</a>
        <?php endif ?>
      </div>
    </div>
  </div>
<?php else: ?>
  <h1>Invalid match</h1>
<?php endif ?>