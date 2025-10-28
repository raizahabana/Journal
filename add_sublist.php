<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "journal");
if ($conn->connect_error) {
  echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
  exit;
}

// Get form values
$item_id = intval($_POST['itemSelect'] ?? 0);
$sublist_name = trim($_POST['sublistName'] ?? '');
$sublist_link = trim($_POST['sidebarItemLink'] ?? ''); // e.g. "pages/reports/sales_report.php"

if ($item_id <= 0 || empty($sublist_name) || empty($sublist_link)) {
  echo json_encode(['success' => false, 'message' => 'Please fill in all fields (name and link).']);
  exit;
}

// ✅ --- File creation logic ---
$baseDir = __DIR__; // current folder where this PHP file is
$filePath = $baseDir . '/' . $sublist_link;

// Ensure the folder exists
$folderPath = dirname($filePath);
if (!is_dir($folderPath)) {
  if (!mkdir($folderPath, 0777, true)) {
    echo json_encode(['success' => false, 'message' => "Failed to create folder: $folderPath"]);
    exit;
  }
}

// Create the file if it doesn’t exist
if (!file_exists($filePath)) {
  $content = <<<PHP
<?php
// Auto-generated sublist page for: {$sublist_name}
?>
<?php
define('BASE_PATH', __DIR__ . '/../../');
define('BASE_URL', '/Journal/');
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$sublist_name}</title>
  <link rel="shortcut icon" type="image/png" href="<?php echo BASE_URL; ?>assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <!--  App Topstrip -->
    <div class="app-topstrip bg-dark py-6 px-3 w-100 d-lg-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center justify-content-center gap-5 mb-2 mb-lg-0">
        <a class="d-flex justify-content-center" href="#">
          <img src="<?php echo BASE_URL; ?>assets/images/logos/logo-wrappixel.svg" alt="" width="150">
        </a>


      </div>

      <div class="d-lg-flex align-items-center gap-2">
        <h3 class="text-white mb-2 mb-lg-0 fs-5 text-center">Raiza Habana</h3>
        <div class="d-flex align-items-center justify-content-center gap-2">

          <div class="dropdown d-flex">
            <!-- <a class="btn btn-primary d-flex align-items-center gap-1 " href="javascript:void(0)" id="drop4"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="ti ti-shopping-cart fs-5"></i>
              Buy Now
              <i class="ti ti-chevron-down fs-5"></i>
            </a> -->
          </div>
        </div>
      </div>

    </div>


    <!-- Sidebar Start -->
     <?php include BASE_PATH . '/../include/navbar-part/sidebar.php'; ?>
    <!--  Sidebar End -->


    <!--  Main wrapper -->
    <div class="body-wrapper">

      <!--  Header Start -->
      <?php include BASE_PATH . '/../include/navbar-part/header.php'; ?>
      <!--  Header End -->

      <div class="body-wrapper-inner">
        <div class="container-fluid">

          <!--  Pages Start -->
          <?php include BASE_PATH . '/../include/pages/dashboard.php'; ?>
          <!--  Pages End -->

        </div>
      </div>

    </div>
  </div>
  <script src="<?php echo BASE_URL; ?>assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/sidebarmenu.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/app.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/dashboard.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/navbar.js"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
          <?php include BASE_PATH . '/../include/pages/bottom.php'; ?>


</body>

</html>
PHP;

  if (file_put_contents($filePath, $content) === false) {
    echo json_encode(['success' => false, 'message' => "Failed to create file: $sublist_link"]);
    exit;
  }
}

// ✅ --- Insert into database ---
$stmt = $conn->prepare("INSERT INTO sidebar (parent_id, type, name, href) VALUES (?, 'sublist', ?, ?)");
$stmt->bind_param("iss", $item_id, $sublist_name, $sublist_link);
$result = $stmt->execute();

if ($result) {
  echo json_encode(['success' => true, 'message' => 'Sublist added and file created successfully!']);
} else {
  echo json_encode(['success' => false, 'message' => 'Database insert failed: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>
