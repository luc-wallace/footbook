<?php
$header = "
<link rel=\"stylesheet\" href=\"./css/matches.css\">
";
require "./base/top.php";
?>

<h1>Matches</h1>

<?php
// $result = $conn->query("SELECT
//   (SELECT logo_url FROM teams WHERE name = away_team) AS away_team_logo,
//   matches.*,
//   teams.*,
//   min_price
//   DATE_FORMAT(date, '%a %D %b') as date_format
//   FROM matches, teams, price_bands
//   WHERE (SELECT * FROM teams WHERE name = home_team) AS home, home_team = name
//   ORDER BY date ASC;
// ");

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
  <tr>
    <th>Home Team</th>
    <th>Away Team</th>
    <th>Kick Off</th>
    <th>Stadium</th>
    <th>Price</th>
  </tr>
  <?php foreach ($matches as $match): ?>
    <tr>
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
require "./base/bottom.php";
?>