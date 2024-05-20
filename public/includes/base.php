<?php session_start();
$conn = mysqli_connect("localhost", "root", "", "booking_system");
if (!$conn) {
  die("Connection failed: " . $conn->connect_error);
}
