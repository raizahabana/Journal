<?php
$conn = mysqli_connect("localhost", "root", "", "journal");
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $note = $_POST['note'];
  $code = $_POST['code'];
  $unique_code = uniqid('UC_'); // Generate unique code

  $imagePaths = [];

  // Handle multiple image uploads
  if (!empty($_FILES['images']['name'][0])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
      $fileName = time() . '_' . basename($_FILES['images']['name'][$key]);
      $targetPath = $uploadDir . $fileName;

      if (move_uploaded_file($tmpName, $targetPath)) {
        $imagePaths[] = $targetPath;
      }
    }
  }

  // Convert image paths to JSON for SQL storage
  $imagesJson = json_encode($imagePaths);

  // Insert into SQL (using mysqli, not PDO)
  $sql = "INSERT INTO code (title, note, code, images, unique_code)
          VALUES ('$title', '$note', '$code', '$imagesJson', '$unique_code')";

  $result = mysqli_query($conn, $sql);

  echo $result ? 'success' : 'error';
}
?>
