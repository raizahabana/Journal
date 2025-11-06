<?php

// ===============================
// DB CONNECTION
// ===============================
$conn = mysqli_connect("localhost", "root", "", "journal");
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// ===============================
// HANDLE AJAX SUBMIT
// ===============================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $note = mysqli_real_escape_string($conn, $_POST['note']);
  $code = mysqli_real_escape_string($conn, $_POST['code']);

  // Handle uploaded images
  $uploaded_files = [];
  if (!empty($_FILES['images']['name'][0])) {
    if (!is_dir("uploads"))
      mkdir("uploads");
    foreach ($_FILES['images']['name'] as $key => $filename) {
      $tmp = $_FILES['images']['tmp_name'][$key];
      $target = "uploads/" . time() . "_" . basename($filename);
      if (move_uploaded_file($tmp, $target)) {
        $uploaded_files[] = $target;
      }
    }
  }

  $images_json = json_encode($uploaded_files);

  $url = mysqli_real_escape_string($conn, $_POST['url']);
  // Insert into database

  $sql = "INSERT INTO notebook (title, note, code, images, url) 
        VALUES ('$title', '$note', '$code', '$images_json', '$url')";
  if (mysqli_query($conn, $sql)) {
    echo "success";
  } else {
    echo "error";
  }
  exit;
}
?>