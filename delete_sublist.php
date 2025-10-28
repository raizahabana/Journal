<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "journal");

if ($conn->connect_error) {
  echo json_encode(["success" => false, "message" => "Database connection failed."]);
  exit;
}

$sublistId = intval($_POST['sublistId'] ?? 0);
if ($sublistId === 0) {
  echo json_encode(["success" => false, "message" => "Invalid sublist ID."]);
  exit;
}

// --- STEP 1: Get the file href for this sublist ---
$href = null;
$res = $conn->query("SELECT href FROM sidebar WHERE id = $sublistId LIMIT 1");
if ($res && $row = $res->fetch_assoc()) {
  $href = $row['href'];
}

// --- STEP 2: Delete the file only (no folders) ---
if (!empty($href)) {
  $baseDir = __DIR__;
  $filePath = realpath($baseDir . '/' . $href);

  // Delete the file only if it exists and is inside the include directory
  $includeBase = realpath($baseDir . '/include');
  if ($filePath && file_exists($filePath) && strpos($filePath, $includeBase) === 0) {
    unlink($filePath); // 🗑️ delete the file only
  }
}

// --- STEP 3: Delete the sublist record from the database ---
$conn->query("DELETE FROM sidebar WHERE id = $sublistId");

echo json_encode(["success" => true, "message" => "Sublist and its file deleted."]);

$conn->close();
?>
