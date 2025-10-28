<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "journal");

$sql = "SELECT * FROM sidebar WHERE type = 'header' ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

$headers = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $headers[] = [
            'id' => $row['id'],
            'name' => $row['name']
        ];
    }
}

echo json_encode(['success' => true, 'data' => $headers]);

mysqli_close($conn);
?>