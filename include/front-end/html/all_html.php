<?php
$conn = mysqli_connect("localhost", "root", "", "journal");
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // 🧠 Detect if this is UPDATE or INSERT
  $id = $_POST['id'] ?? '';

  // Common fields
  $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
  $note = mysqli_real_escape_string($conn, $_POST['note'] ?? '');
  $code = mysqli_real_escape_string($conn, $_POST['code'] ?? '');
  $style = mysqli_real_escape_string($conn, $_POST['style'] ?? '');
  $table_json = mysqli_real_escape_string($conn, $_POST['tableData'] ?? '[]');
  $url = mysqli_real_escape_string($conn, $_POST['url'] ?? '');

  // --- Handle image uploads ---
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

  // ✅ IF updating existing record
  if (!empty($id)) {

    // Fetch old images so we can merge
    $existing_images = [];
    $res = mysqli_query($conn, "SELECT images FROM notebook WHERE id='$id'");
    if ($res && mysqli_num_rows($res) > 0) {
      $row = mysqli_fetch_assoc($res);
      $existing_images = json_decode($row['images'], true) ?: [];
    }

    // Merge old + new
    $final_images = array_merge($existing_images, $uploaded_files);
    $images_json = mysqli_real_escape_string($conn, json_encode($final_images));

    // Update query
    $sql = "
      UPDATE notebook 
      SET 
        title='$title',
        note='$note',
        code='$code',
        style='$style',
        table_array='$table_json',
        images='$images_json'
      WHERE id='$id'
    ";

    echo mysqli_query($conn, $sql)
      ? "success"
      : "error: " . mysqli_error($conn);

  } else {
    // ✅ ELSE insert new record

    if (empty($title)) {
      echo "error: Title required to insert record.";
      exit;
    }

    $images_json = mysqli_real_escape_string($conn, json_encode($uploaded_files));

    $sql = "INSERT INTO notebook (`title`, `note`, `code`, `style`, `table_array`, `images`, `url`)
            VALUES ('$title', '$note', '$code', '$style', '$table_json', '$images_json', '$url')";

    echo mysqli_query($conn, $sql)
      ? "success"
      : "error: " . mysqli_error($conn);
  }

  exit;
}
?>


<?php
define('BASE_PATH', __DIR__ . '/../../');
define('BASE_URL', '/Journal/');


// Get current full URL
$currentURL = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $_SERVER['REQUEST_URI'] ?></title>
  <link rel="shortcut icon" type="image/png" href="<?php echo BASE_URL; ?>assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/styles.min.css" />

  <style>
    /* Optional: add smooth horizontal scrolling and hide scrollbar on some browsers */
    #imagePreviewContainer {
      scroll-behavior: smooth;
    }

    #imagePreviewContainer::-webkit-scrollbar {
      height: 8px;
    }

    #imagePreviewContainer::-webkit-scrollbar-thumb {
      background: #ccc;
      border-radius: 4px;
    }

    #imagePreview img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 8px;
      border: 2px solid #dee2e6;
    }


    .image-gallery-container {
      overflow-x: auto;
      white-space: nowrap;
      padding: 10px;
      border-top: 1px solid #dee2e6;
    }

    .image-gallery {
      display: flex;
      gap: 20px;
    }

    .image-item img {
      width: 400px;
      /* ✅ Bigger image size */
      height: 400px;
      object-fit: cover;
      border-radius: 10px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .image-item img:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }


    <?php
    // Query all notebook names
    $query = "SELECT * FROM notebook WHERE url = '" . mysqli_real_escape_string($conn, $currentURL) . "'";
    $style_result = mysqli_query($conn, $query);

    // Check if query succeeded
    if (!$style_result) {
      die("Query failed: " . mysqli_error($conn));
    }

    // Display buttons
    while ($style = mysqli_fetch_assoc($style_result)) {

      if (!empty($style['style'])) {
        echo $style['style'];
      }
    }
    ?>
  </style>

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
          <!--  Row 1 -->
          <!-- Button Row -->
          <div class="row mb-5">
            <div class="col-lg-10">
              <!-- Container where new button will be added -->
              <div class="d-flex flex-wrap gap-3 justify-content-start" id="buttonContainer">

                <?php
                // Connect to database
                $conn = mysqli_connect("localhost", "root", "", "journal");

                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }



                // Query all notebook names
                $query = "SELECT * FROM notebook WHERE url = '" . mysqli_real_escape_string($conn, $currentURL) . "'";
                $result = mysqli_query($conn, $query);

                // Check if query succeeded
                if (!$result) {
                  die("Query failed: " . mysqli_error($conn));
                }

                // Display buttons
                while ($row = mysqli_fetch_assoc($result)) {

                  $title = $row['title'];

                  // ✅ Clean and make unique ID
                  $cleanName = preg_replace('/\s+/', '', $title);
                  $cleanName = preg_replace('/[^A-Za-z0-9_-]/', '', $cleanName);
                  $cleanName .= '_' . $row['id']; // ensure unique id
                
                  // ✅ Button to scroll to section
                  echo '<button 
            id="btn_' . $cleanName . '" 
            name="' . $cleanName . '" 
            onclick="scrollToSection(\'' . $cleanName . '\')" 
            class="btn btn-warning btn-sm m-1">'
                    . htmlspecialchars($title) .
                    '</button>';
                }
                ?>

              </div>
            </div>

            <div class="col-lg-2">
              <div class="d-flex flex-wrap gap-3 justify-content-end">
                <button id="addBtn" class="btn btn-success btn-sm w-50 w-md-25 w-lg-25">ADD</button>
              </div>
            </div>
          </div>

          <form id="uploadForm" enctype="multipart/form-data">

            <!-- INSERT TITLE (Hidden Initially) -->
            <div class="row" id="insertTitleRow" style="display: none;">
              <div class="col-lg-12">
                <div class="card w-100">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-6">
                        <h4 class="card-title">Insert Title</h4>
                        <input type="text" name="title" id="title" class="form-control w-100" placeholder="Enter title"
                          required />
                        <input type="hidden" name="url" id="url" />
                      </div>
                      <div class="col-lg-6">
                        <div class="d-flex flex-wrap gap-3 justify-content-center mb-2">
                          <button id="unhideNote" class="btn btn-success btn-sm">Add Note</button>
                          <button id="unhideImages" class="btn btn-success btn-sm">Add Images</button>
                          <button id="unhideTable" class="btn btn-success btn-sm">Add Table</button>
                          <button id="unhideCode" class="btn btn-success btn-sm">Add Code</button>
                          <button id="unhideStyle" class="btn btn-success btn-sm">Add Style</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- TABLE CARD -->
            <div class="row mt-3">
              <div class="col-lg-12" id="tableCard" style="display: none;">
                <div class="card w-100">
                  <div class="card-body position-relative">

                    <!-- Minus Button -->
                    <button type="button" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 text-danger"
                      id="hideTableBtn" title="Hide Table">
                      <i class="ti ti-minus"></i>
                    </button>

                    <h4 class="card-title">Insert Table</h4>
                    <p class="card-subtitle">Create a table and add rows or columns dynamically</p>

                    <!-- Table Container -->
                    <div id="editableTableContainer" class="table-responsive mt-3">
                      <!-- ✅ Add Column Buttons -->
                      <div class="d-flex justify-content-end gap-2 mt-2">
                        <button type="button" class="btn btn-success btn-sm d-flex align-items-center add-col-btn">
                          <i class="ti ti-plus me-1"></i> Add Column
                        </button>
                      </div>
                      <table id="editableTable" class="table table-bordered text-center align-middle">
                        <thead>
                          <tr>
                            <th contenteditable="true">Column 1</th>

                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td contenteditable="true">Header</td>
                          </tr>
                          <tr>
                            <td contenteditable="true">Value</td>
                          </tr>
                        </tbody>
                      </table>
                      <!-- ✅ Add Row Buttons -->
                      <div class="d-flex justify-content-end gap-2 mt-2">
                        <button type="button" class="btn btn-success btn-sm d-flex align-items-center add-row-btn">
                          <i class="ti ti-plus me-1"></i> Add Row
                        </button>
                      </div>
                    </div>


                  </div>
                </div>
              </div>
            </div>


            <!-- NOTE & IMAGES -->
            <div class="row mt-3">
              <!-- NOTE -->
              <div class="col-lg-6" id="noteCard" style="display: none;">
                <div class="card w-100">
                  <div class="card-body position-relative">
                    <!-- Minus Button -->
                    <button type="button" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 text-danger"
                      id="hideNoteBtn" title="Hide Note">
                      <i class="ti ti-minus"></i>
                    </button>

                    <h4 class="card-title">Insert Note</h4>
                    <p class="card-subtitle">Add important information here</p>
                    <textarea name="note" id="note" class="form-control w-100" style="height: 155px;"
                      required></textarea>
                  </div>
                </div>
              </div>

              <!-- IMAGES -->
              <div class="col-lg-6" id="imagesCard" style="display: none;">
                <div class="card overflow-hidden">
                  <div class="card-body pb-0 position-relative">
                    <!-- Minus Button -->
                    <button type="button" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 text-danger"
                      id="hideImagesBtn" title="Hide Images">
                      <i class="ti ti-minus"></i>
                    </button>

                    <h4 class="card-title">Upload More Images</h4>
                    <p class="card-subtitle">Accepted formats: JPEG, JPG, PNG</p>
                    <hr />

                    <div class="mt-4 pb-3 d-flex align-items-center" style="height: 100px;">
                      <label class="btn btn-primary rounded-circle round-48 hstack justify-content-center mb-0">
                        <i class="ti ti-upload fs-6"></i>
                        <input type="file" id="imageInput" name="images[]" accept="image/*" multiple hidden />
                      </label>
                      <div class="ms-3">
                        <h5 class="mb-0 fw-bolder fs-4">Choose Files</h5>
                        <span class="text-muted fs-3">Click to upload or drag images here</span>
                      </div>
                      <div class="ms-auto">
                        <i class="ti ti-plus"></i>
                      </div>
                    </div>

                    <!-- Scrollable preview -->
                    <div id="imagePreviewContainer"
                      style="overflow-x: auto; white-space: nowrap; padding: 10px; border-top: 1px solid #dee2e6;">
                      <div id="imagePreview" style="display: inline-flex; gap: 10px;"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- CODE -->
            <div class="row mt-3">
              <div class="col-lg-6" id="codeCard" style="display: none;">
                <div class="card w-100">
                  <div class="card-body position-relative">
                    <!-- Minus Button -->
                    <button type="button" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 text-danger"
                      id="hideCodeBtn" title="Hide Code">
                      <i class="ti ti-minus"></i>
                    </button>

                    <h4 class="card-title">Insert Code</h4>
                    <p class="card-subtitle">Add important code here</p>
                    <textarea name="code" id="code" class="form-control w-100" style="height: 100px;"
                      required></textarea>
                  </div>
                </div>
              </div>

              <div class="col-lg-6" id="styleCard" style="display: none;">
                <div class="card w-100">
                  <div class="card-body position-relative">
                    <!-- Minus Button -->
                    <button type="button" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 text-danger"
                      id="hideStyleBtn" title="Hide Style">
                      <i class="ti ti-minus"></i>
                    </button>

                    <h4 class="card-title">Insert Style</h4>
                    <p class="card-subtitle">Add important style here</p>
                    <textarea name="style" id="style" class="form-control w-100" style="height: 100px;"
                      required></textarea>
                  </div>
                </div>
              </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="row mt-3" id="submitRow" style="display: none;">
              <div class="col-12 d-flex justify-content-end">
                <button type="button" id="submit" class="btn btn-success btn-sm w-25">SUBMIT</button>
              </div>
            </div>


          </form>


          <?php

          // Query notebook entries matching the current page URL
          $newquery = "SELECT * FROM notebook WHERE url = '" . mysqli_real_escape_string($conn, $currentURL) . "'";
          $newresult = mysqli_query($conn, $newquery);

          if ($newresult && mysqli_num_rows($newresult) > 0) {
            while ($note = mysqli_fetch_assoc($newresult)) {

              $title = $note['title'];

              // ✅ Clean & unique section ID
              $titleNoSpaces = preg_replace('/\s+/', '', $title);
              $titleNoSpaces = preg_replace('/[^A-Za-z0-9_-]/', '', $titleNoSpaces);
              $titleNoSpaces .= '_' . $note['id'];
              ?>
              <!-- Editable Section -->
              <section id="<?= htmlspecialchars($titleNoSpaces) ?>" class="mt-5 p-5 border rounded bg-light">
                <form id="form_<?= $note['id'] ?>" enctype="multipart/form-data">
                  <!-- ✅ Title -->
                  <h1 class="mb-5"><?= htmlspecialchars($title) ?></h1>

                  <input type="hidden" name="note_id" value="<?= $note['id'] ?>">

                  <!-- ✅ Editable Title -->
                  <div class="mb-4">
                    <label class="form-label fw-bold">Title:</label>
                    <input type="text" name="title_<?= $note['id'] ?>" class="form-control"
                      value="<?= htmlspecialchars($note['title']) ?>">
                  </div>

                  <!-- ✅ Editable Note -->
                  <?php if (!empty($note['note'])) { ?>
                    <div class="mb-4">
                      <label class="form-label fw-bold">Note:</label>
                      <textarea name="note_<?= $note['id'] ?>" rows="4"
                        class="form-control"><?= htmlspecialchars($note['note']) ?></textarea>
                    </div>
                  <?php } ?>

                  <!-- ✅ Editable Code and Style -->
                  <?php if (!empty($note['code']) || !empty($note['style'])) { ?>
                    <div class="row mt-3">
                      <div class="col-lg-6">
                        <div class="card w-100 h-100">
                          <div class="card-body">
                            <h4 class="card-title fw-bold">Code</h4>
                            <textarea name="code_<?= $note['id'] ?>" class="form-control w-100"
                              rows="8"><?= htmlspecialchars($note['code']) ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="card w-100 h-100">
                          <div class="card-body">
                            <h4 class="card-title fw-bold">Style</h4>
                            <textarea name="style_<?= $note['id'] ?>" class="form-control w-100"
                              rows="8"><?= htmlspecialchars($note['style']) ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>

                  <!-- CODE PRESENTATION -->
                  <?php if (!empty($note['code'])) { ?>
                    <div class="row mt-3">
                      <div class="col-lg-12">
                        <div class="card w-100 h-100">
                          <div class="card-body position-relative">
                            <h3 class="card-title">Code Presentation</h3>
                            <pre class="card-subtitle m-4 bg-light p-3 rounded"><code><?= $note['code']; ?></code></pre>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>

                  <!-- ✅ Editable Images -->
                  <?php
                  $images = json_decode($note['images'], true);
                  if (!empty($images)) { ?>
                    <div class="row mt-3">
                      <div class="col-lg-12">
                        <div class="card w-100">
                          <div class="card-body">
                            <h4 class="card-title fw-bold">Images</h4>
                            <div class="d-flex flex-wrap gap-3 mb-3">
                              <?php foreach ($images as $img) { ?>
                                <div class="border rounded p-2 text-center">
                                  <img src="<?= htmlspecialchars($img); ?>" alt="Image" class="rounded shadow-sm"
                                    style="max-width: 150px; max-height: 150px;">
                                </div>
                              <?php } ?>
                            </div>
                            <label class="form-label">Change Images:</label>
                            <input type="file" name="images_<?= $note['id'] ?>[]" multiple class="form-control">
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>

                  <!-- ✅ Editable Table -->
                  <?php if ($note['table_array'] != '[["Header"],["Value"]]'):
                    $tableData = json_decode($note['table_array'], true);
                    if (is_array($tableData) && count($tableData) > 0):
                      $headers = $tableData[0];
                      $rows = array_slice($tableData, 1);
                      ?>
                      <div class="row mt-4">
                        <div class="col-lg-12">
                          <div class="card w-100">
                            <div class="card-body">
                              <h4 class="card-title fw-bold">Editable Table</h4>

                              <div class="table-responsive mt-3">
                                <table class="table table-bordered text-center align-middle">
                                  <thead class="table-light">
                                    <tr>
                                      <?php foreach ($headers as $i => $header): ?>
                                        <th>
                                          <input type="text" class="form-control text-center"
                                            name="table_header_<?= $note['id'] ?>[]" value="<?= htmlspecialchars($header); ?>">
                                        </th>
                                      <?php endforeach; ?>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php foreach ($rows as $rIndex => $row): ?>
                                      <tr>
                                        <?php foreach ($row as $cell): ?>
                                          <td>
                                            <input type="text" class="form-control text-center"
                                              name="table_row_<?= $note['id'] ?>[<?= $rIndex ?>][]"
                                              value="<?= htmlspecialchars($cell); ?>">
                                          </td>
                                        <?php endforeach; ?>
                                      </tr>
                                    <?php endforeach; ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endif;
                  endif; ?>

                  <!-- ✅ Save Button -->
                  <div class="text-end mt-4">
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveNote(<?= $note['id'] ?>)">💾 Save
                      Changes</button>
                  </div>

                </form>
              </section>
              <?php
            } // end while
          } else {
            echo "<p class='text-muted text-center mt-5'>No notebook entries found for this page.</p>";
          }
          ?>


        </div>
      </div>


    </div>
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
  <script>

    document.addEventListener("DOMContentLoaded", function () {
      // Auto-fill URL field with current path
      document.getElementById("url").value = window.location.href;

      // Image preview
      const imageInput = document.getElementById('imageInput');
      const imagePreview = document.getElementById('imagePreview');
      imageInput.addEventListener('change', () => {
        imagePreview.innerHTML = '';
        Array.from(imageInput.files).forEach(file => {
          const reader = new FileReader();
          reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = "100px";
            img.style.height = "100px";
            img.style.objectFit = "cover";
            img.style.borderRadius = "10px";
            imagePreview.appendChild(img);
          };
          reader.readAsDataURL(file);
        });
      });

      document.getElementById('submit').addEventListener('click', function () {
        const form = document.getElementById('uploadForm');
        const table = document.getElementById('editableTable');
        const tableData = [];

        table.querySelectorAll('tbody tr').forEach(row => {
          const rowData = [];
          row.querySelectorAll('td').forEach(cell => {
            rowData.push(cell.textContent.trim());
          });
          tableData.push(rowData);
        });

        let hidden = document.getElementById('tableData');
        if (!hidden) {
          hidden = document.createElement('input');
          hidden.type = 'hidden';
          hidden.name = 'tableData';
          hidden.id = 'tableData';
          form.appendChild(hidden);
        }
        hidden.value = JSON.stringify(tableData);

        const formData = new FormData(form);
        fetch('all_html.php', {
          method: 'POST',
          body: formData
        })
          .then(response => response.text())
          .then(data => {
            console.log('Server response:', data);
            if (data.trim() === 'success') {
              alert('✅ Record added successfully!');
              form.reset();
              location.reload();
            } else {
              alert(data);
            }
          })
          .catch(error => console.error('Error:', error));
      });

    });
  </script>




  <script>
    const addBtn = document.getElementById("addBtn");
    const buttonContainer = document.getElementById("buttonContainer");
    const insertTitleRow = document.getElementById("insertTitleRow");
    const titleInput = document.getElementById("title");

    addBtn.addEventListener("click", () => {
      // Check if "New title" button already exists
      let newBtn = document.getElementById("newBtnName");

      if (!newBtn) {
        // Create the button only once
        newBtn = document.createElement("button");
        newBtn.id = "newBtnName";
        newBtn.className = "btn btn-warning btn-sm";
        newBtn.textContent = "New title";
        buttonContainer.appendChild(newBtn);
      }

      // Show the Insert Title section
      insertTitleRow.style.display = "block";

      // Focus on title input automatically
      titleInput.focus();
    });

    // When typing in the title input, update button text automatically
    titleInput.addEventListener("input", () => {
      const newBtn = document.getElementById("newBtnName");
      if (newBtn) {
        newBtn.textContent = titleInput.value.trim() !== "" ? titleInput.value : "New title";
      }
    });
  </script>



  <script>
    // Buttons to unhide
    const unhideNote = document.getElementById("unhideNote");
    const unhideCode = document.getElementById("unhideCode");
    const unhideStyle = document.getElementById("unhideStyle");
    const unhideImages = document.getElementById("unhideImages");
    const unhideTable = document.getElementById("unhideTable");

    // Cards
    const noteCard = document.getElementById("noteCard");
    const codeCard = document.getElementById("codeCard");
    const styleCard = document.getElementById("styleCard");
    const imagesCard = document.getElementById("imagesCard");
    const tableCard = document.getElementById("tableCard");
    const submitRow = document.getElementById("submitRow");

    // Hide buttons
    const hideNoteBtn = document.getElementById("hideNoteBtn");
    const hideCodeBtn = document.getElementById("hideCodeBtn");
    const hideStyleBtn = document.getElementById("hideStyleBtn");
    const hideImagesBtn = document.getElementById("hideImagesBtn");
    const hideTableBtn = document.getElementById("hideTableBtn");

    // Helper function to check visibility
    function updateSubmitVisibility() {
      if (
        noteCard.style.display === "none" &&
        codeCard.style.display === "none" &&
        styleCard.style.display === "none" &&
        imagesCard.style.display === "none" &&
        tableCard.style.display === "none"
      ) {
        submitRow.style.display = "none"; // Hide submit if all closed
      } else {
        submitRow.style.display = "flex"; // Show submit if any open
      }
    }

    // Function to unhide specific section
    function showSection(section) {
      section.style.display = "block";
      updateSubmitVisibility();
    }

    // Function to hide specific section
    function hideSection(section) {
      section.style.display = "none";
      updateSubmitVisibility();
    }

    // Unhide buttons
    unhideNote.addEventListener("click", () => showSection(noteCard));
    unhideCode.addEventListener("click", () => showSection(codeCard));
    unhideStyle.addEventListener("click", () => showSection(styleCard));
    unhideImages.addEventListener("click", () => showSection(imagesCard));
    unhideTable.addEventListener("click", () => showSection(tableCard));

    // Hide buttons
    hideNoteBtn.addEventListener("click", () => hideSection(noteCard));
    hideCodeBtn.addEventListener("click", () => hideSection(codeCard));
    hideStyleBtn.addEventListener("click", () => hideSection(styleCard));
    hideImagesBtn.addEventListener("click", () => hideSection(imagesCard));
    hideTableBtn.addEventListener("click", () => hideSection(tableCard));


    function scrollToSection(sectionId) {
      const section = document.getElementById(sectionId);
      if (section) {
        section.scrollIntoView({ behavior: "smooth", block: "start" });

        // Optional highlight effect
        section.style.transition = "background-color 0.5s ease";
        section.style.backgroundColor = "#fff3cd"; // light yellow
        setTimeout(() => {
          section.style.backgroundColor = "";
        }, 1200);
      } else {
        console.warn("Section not found:", sectionId);
      }
    }
  </script>


  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const table = document.getElementById("editableTable");
      const addRowBtn = document.querySelector(".add-row-btn");
      const addColBtn = document.querySelector(".add-col-btn");

      // ➕ Add new row
      addRowBtn.addEventListener("click", () => {
        const tbody = table.querySelector("tbody");
        const cols = table.querySelector("thead tr").querySelectorAll("th[contenteditable]").length;
        const newRow = document.createElement("tr");

        for (let i = 0; i < cols; i++) {
          const td = document.createElement("td");
          td.contentEditable = "true";
          td.textContent = `Value ${tbody.children.length + 1}`;
          newRow.appendChild(td);
        }
        tbody.appendChild(newRow);
      });

      // ➕ Add new column (fixed)
      addColBtn.addEventListener("click", () => {
        const theadRow = table.querySelector("thead tr");
        const colCount = theadRow.querySelectorAll("th[contenteditable]").length + 1;

        // ✅ Add editable header at the end (no overwrite)
        const newHeader = document.createElement("th");
        newHeader.contentEditable = "true";
        newHeader.textContent = `Column ${colCount}`;
        theadRow.appendChild(newHeader);
        //  👈 Append instead of insertBefore

        // ✅ Add new cell for each existing row
        table.querySelectorAll("tbody tr").forEach((row, i) => {
          const newCell = document.createElement("td");
          newCell.contentEditable = "true";
          newCell.textContent = `Value ${i + 1}`;
          row.appendChild(newCell);
        });
      });

      // 🧠 Capture table (headers + rows) as JSON before submit
      document.getElementById("submit").addEventListener("click", function () {
        const tableData = {
          headers: [],
          rows: []
        };

        // Capture headers
        table.querySelectorAll("thead th[contenteditable]").forEach(th => {
          tableData.headers.push(th.textContent.trim());
        });

        // Capture rows
        table.querySelectorAll("tbody tr").forEach(row => {
          const rowData = [];
          row.querySelectorAll("td").forEach(cell => {
            rowData.push(cell.textContent.trim());
          });
          tableData.rows.push(rowData);
        });

        // Add hidden JSON field to form
        const form = document.getElementById("uploadForm");
        let hidden = document.getElementById("tableData");
        if (!hidden) {
          hidden = document.createElement("input");
          hidden.type = "hidden";
          hidden.name = "tableData";
          hidden.id = "tableData";
          form.appendChild(hidden);
        }
        hidden.value = JSON.stringify(tableData);
      });
    });
  </script>

  <script>
    function saveNote(noteId) {
      const formData = new FormData();

      ['title', 'note', 'code', 'style'].forEach(field => {
        const input = document.querySelector(`[name="${field}_${noteId}"]`);
        if (input) formData.append(field, input.value);
      });

      const headers = Array.from(document.querySelectorAll(`[name="table_header_${noteId}[]"]`)).map(i => i.value);
      const rows = [];
      document.querySelectorAll(`[name^="table_row_${noteId}["]`).forEach(input => {
        const rowIndex = input.name.match(/\[(\d+)\]/)[1];
        if (!rows[rowIndex]) rows[rowIndex] = [];
        rows[rowIndex].push(input.value);
      });
      if (headers.length) formData.append('tableData', JSON.stringify([headers, ...rows]));

      const imageInput = document.querySelector(`[name="images_${noteId}[]"]`);
      if (imageInput && imageInput.files.length > 0) {
        for (let file of imageInput.files) {
          formData.append('images[]', file);
        }
      }

      formData.append('id', noteId);

      fetch('all_html.php', {
        method: 'POST',
        body: formData
      })
        .then(res => res.text())
        .then(data => {
          alert(data.trim() === 'success' ? '✅ Updated successfully!' : data);
          location.reload();
        })
        .catch(err => console.error(err));
      location.reload();
    }
  </script>


</body>

</html>