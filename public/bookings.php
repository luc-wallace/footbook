<?php
require "./includes/base.php";
header("Content-type: application/json");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false || $_SERVER['REQUEST_METHOD'] != "POST") {
  http_response_code(401);
  echo json_encode(array("message" => "You must be logged in to access this."));
  exit;
}

$firstName = $_POST["firstName"];
$lastName = $_POST["lastName"];
$row = $_POST["row"];
$seatType = $_POST["seatType"];
$home = $_POST["homeTeam"];
$away = $_POST["awayTeam"];

$stmt = $conn->prepare("INSERT INTO bookings VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $home, $away, $row, $_SESSION["username"], $seatType);
if ($stmt->execute()) {
  http_response_code(201);
  echo json_encode(array("message" => "Success."));
} else {
  http_response_code(500);
  echo json_encode(array("message" => "Something went wrong, please try again later."));
}
