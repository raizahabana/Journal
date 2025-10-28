<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "journal");

if ($conn->connect_error) {
  echo json_encode(["success" => false, "message" => "Database connection failed."]);
  exit;
}

$itemId = intval($_POST['itemId'] ?? 0);
if ($itemId === 0) {
  echo json_encode(["success" => false, "message" => "Invalid item ID."]);
  exit;
}

// --- STEP 1: Get href paths (for item + its sublists) ---
$hrefs = [];
$sql = "SELECT href FROM sidebar WHERE id = $itemId OR parent_id = $itemId";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    if (!empty($row['href'])) $hrefs[] = $row['href'];
  }
}

// --- STEP 2: Delete corresponding files and folders ---
$baseDir = __DIR__;
$includeBase = realpath($baseDir . '/include');

foreach ($hrefs as $href) {
  $filePath = realpath($baseDir . '/' . $href);

  if ($filePath && file_exists($filePath)) {
    // 🗑️ Delete the file
    unlink($filePath);

    // 🧹 Remove its immediate folder (like "container") if empty
    $folderPath = dirname($filePath);

    if (
      $folderPath &&
      strpos($folderPath, $includeBase) === 0 &&  // inside include/
      $folderPath !== $includeBase &&             // not include itself
      is_dir($folderPath)
    ) {
      // Delete only if folder becomes empty
      $filesLeft = glob($folderPath . '/*');
      if (empty($filesLeft)) {
        rmdir($folderPath);
      }
    }
  }
}

// --- STEP 3: Delete from database ---
$conn->query("DELETE FROM sidebar WHERE id = $itemId OR parent_id = $itemId");

echo json_encode(["success" => true, "message" => "Sidebar item, sublists, and related folder deleted."]);

$conn->close();
?>
