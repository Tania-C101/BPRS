<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Service_ID, Service_Name, Service_Category, Image_ID FROM services";
$result = $conn->query($sql);

$services = array();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $services[] = $row;
  }
} else {
  echo json_encode([]);
}

$conn->close();

echo json_encode($services);

