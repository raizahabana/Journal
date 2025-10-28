<?php
$conn = new mysqli("localhost", "root", "", "your_database");
$id = $_GET['id'];
$conn->query("DELETE FROM sidebar WHERE id = '$id' OR parent_id = '$id'");
echo json_encode(['success' => true]);
?>
