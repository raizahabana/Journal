<?php
$conn = new mysqli("localhost", "root", "", "journal");

$header_id = intval($_GET['header_id'] ?? 0);

$sql = "SELECT id, name FROM sidebar WHERE parent_id = $header_id AND type = 'item' ORDER BY id ASC";
$result = $conn->query($sql);

$items = [];
while ($row = $result->fetch_assoc()) {
  $items[] = $row;
}

echo json_encode($items);
?>
