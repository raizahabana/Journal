<?php
$conn = new mysqli("localhost", "root", "", "journal");

$sql = "SELECT id, name FROM sidebar WHERE type = 'header' ORDER BY id ASC";
$result = $conn->query($sql);

$headers = [];
while ($row = $result->fetch_assoc()) {
  $headers[] = $row;
}

echo json_encode($headers);
?>
