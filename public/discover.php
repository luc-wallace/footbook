<?php
$header = "
<link rel=\"stylesheet\" href=\"./css/discover.css\">
";
require "./includes/top.php";
?>

<h1>Discover Matches</h1>

<?php
$result = $conn->query("SELECT
  matches.*,
  home.logo_url AS home_logo,
  home.stadium AS stadium,
  away.logo_url AS away_logo,
  DATE_FORMAT(date, '%a %D %b') AS date_format,
  (SELECT base_price FROM price_bands WHERE (home.size + away.size) BETWEEN min_size AND max_size) AS base_price
  FROM matches
  JOIN teams AS home ON matches.home_team = home.name
  JOIN teams AS away ON matches.away_team = away.name
  ORDER BY date ASC
");

$matches = $result->fetch_all(MYSQLI_ASSOC);
?>

<table class="matches">
  <tr header>
    <th>Home Team</th>
    <th>Away Team</th>
    <th>Kick Off</th>
    <th>Stadium</th>
    <th>Price</th>
  </tr>
  <?php foreach ($matches as $match): ?>
        <tr
          data-href="/matches.php?home=<?php echo urlencode($match["home_team"]) ?>&away=<?php echo urlencode($match["away_team"]) ?>">
          <td>
            <img src="<?php echo $match["home_logo"] ?>" />
            <?php echo $match["home_team"]; ?>
          </td>
          <td>
            <img src="<?php echo $match["away_logo"] ?>" />
            <?php echo $match["away_team"]; ?>
          </td>
          <td>
            <?php echo $match["date_format"]; ?>
          </td>
          <td>
            <?php echo $match["stadium"]; ?>
          </td>
          <td>
            £<?php echo $match["base_price"] . "-" . $match["base_price"] + 35 ?>
          </td>
        </tr>
  <?php endforeach; ?>
</table>

<?php
require "./includes/bottom.php";
?>