<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "journal");

if ($conn->connect_error) {
  echo json_encode(["success" => false, "message" => "Database connection failed."]);
  exit;
}

$headerId = intval($_POST['headerId'] ?? 0);
if ($headerId === 0) {
  echo json_encode(["success" => false, "message" => "Invalid header ID."]);
  exit;
}

// --- STEP 1: Collect all hrefs (items and sublists under this header) ---
$hrefs = [];
$sql = "
  SELECT href FROM sidebar 
  WHERE parent_id = $headerId
     OR parent_id IN (SELECT id FROM sidebar WHERE parent_id = $headerId)
";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    if (!empty($row['href'])) {
      $hrefs[] = $row['href'];
    }
  }
}


// --- Step 2: Delete files and clean up folders ---
$baseDir = __DIR__; // Example: /var/www/html/project
$includeBase = realpath($baseDir . '/include'); // Keep this folder safe

foreach ($hrefs as $href) {
  $filePath = realpath($baseDir . '/' . $href);

  if ($filePath && file_exists($filePath)) {
    // 🗑️ Delete the file
    unlink($filePath);

    // Get the folder path (e.g. include/folder/container)
    $folderPath = dirname($filePath);

    // ✅ Remove folder and all its subfolders (but never remove include/)
    while ($folderPath && strpos($folderPath, $includeBase) === 0 && $folderPath !== $includeBase) {
      // Delete if folder is empty
      if (is_dir($folderPath) && count(glob($folderPath . '/*')) === 0) {
        rmdir($folderPath);
        $folderPath = dirname($folderPath);
      } else {
        // Try to recursively clean up non-empty folders if we created multiple subfolders
        $iterator = new RecursiveIteratorIterator(
          new RecursiveDirectoryIterator($folderPath, RecursiveDirectoryIterator::SKIP_DOTS),
          RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $item) {
          if ($item->isDir()) {
            @rmdir($item->getRealPath());
          } else {
            @unlink($item->getRealPath());
          }
        }

        // Finally try to remove the parent folder itself if now empty
        if (is_dir($folderPath) && count(glob($folderPath . '/*')) === 0) {
          rmdir($folderPath);
        }

        break;
      }
    }
  }
}

// --- STEP 3: Delete sidebar items + sublists from DB ---
$conn->query("DELETE FROM sidebar WHERE parent_id = $headerId OR parent_id IN (SELECT id FROM sidebar WHERE parent_id = $headerId)");

echo json_encode(["success" => true, "message" => "✅ Sidebar items, sublists, and folders deleted (include folder kept safe)."]);

$conn->close();
?>
