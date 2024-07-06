<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$image_id = $_GET['image_id'];
$query = "SELECT File FROM s_images WHERE Image_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $image_id);
$stmt->execute();
$stmt->bind_result($file);
$stmt->fetch();
$stmt->close();
$conn->close();

echo json_encode(['image_url' => $file]);

