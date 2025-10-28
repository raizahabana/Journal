<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "journal");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $headerName = trim($_POST['headerName'] ?? '');

    if ($headerName === '') {
        echo json_encode(['success' => false, 'message' => 'Header name is required.']);
        exit;
    }

    // Prepare SQL safely
    $stmt = mysqli_prepare($conn, "INSERT INTO sidebar (parent_id, type, name) VALUES (?, ?, ?)");
    $parent_id = 0;
    $type = 'header';
    mysqli_stmt_bind_param($stmt, 'iss', $parent_id, $type, $headerName);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Header added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
