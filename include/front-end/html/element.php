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

define('BASE_PATH', __DIR__ . '/../../');
define('BASE_URL', '/Journal/');
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>element</title>
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
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
      border: 2px solid #dee2e6;
    }
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
          <!--  Row 1 -->
          <!-- Button Row -->
          <div class="row mb-5">
            <div class="col-lg-10">
              <!-- Container where new button will be added -->
              <div class="d-flex flex-wrap gap-3 justify-content-start" id="buttonContainer"></div>
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
                        <input type="text" name="title" id="title" class="form-control w-50" placeholder="Enter title"
                          required />
                        <input type="hidden" name="url" id="url" />
                      </div>
                      <div class="col-lg-6">
                        <div class="d-flex flex-wrap gap-3 justify-content-center mb-2">
                          <button id="unhideNote" class="btn btn-success btn-sm">Add Note</button>
                          <button id="unhideCode" class="btn btn-success btn-sm">Add Code</button>
                          <button id="unhideImages" class="btn btn-success btn-sm">Add Images</button>
                        </div>
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
            <div class="row mt-3" id="codeCard" style="display: none;">
              <div class="col-lg-12">
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
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="row mt-3" id="submitRow" style="display: none;">
              <div class="col-12 d-flex justify-content-end">
                <button type="button" id="submit" class="btn btn-success btn-sm w-25">SUBMIT</button>
              </div>
            </div>


          </form>



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
    // =============================
    // IMAGE PREVIEW (unchanged)
    // =============================

    // Auto-fill URL field with current localhost path
    document.addEventListener("DOMContentLoaded", function () {
      const urlInput = document.getElementById("url");
      urlInput.value = window.location.href; // automatically sets full URL
    });

    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', () => {
      imagePreview.innerHTML = '';
      Array.from(imageInput.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
          const img = document.createElement('img');
          img.src = e.target.result;
          imagePreview.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
    });

    // =============================
    // AJAX SUBMIT FORM
    // =============================
    document.getElementById('submit').addEventListener('click', function () {
      const formData = new FormData(document.getElementById('uploadForm'));

      fetch('element.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.text())
        .then(data => {
          if (data.trim() === 'success') {
            alert('Record added successfully!');
            document.getElementById('uploadForm').reset();
            imagePreview.innerHTML = '';
            location.reload();

          } else {
            alert('Error adding record.');
            location.reload();

          }
        })
        .catch(error => console.error('Error:', error));
      location.reload();

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
    const unhideImages = document.getElementById("unhideImages");

    // Cards
    const noteCard = document.getElementById("noteCard");
    const codeCard = document.getElementById("codeCard");
    const imagesCard = document.getElementById("imagesCard");
    const submitRow = document.getElementById("submitRow");

    // Hide buttons
    const hideNoteBtn = document.getElementById("hideNoteBtn");
    const hideCodeBtn = document.getElementById("hideCodeBtn");
    const hideImagesBtn = document.getElementById("hideImagesBtn");

    // Helper function to check visibility
    function updateSubmitVisibility() {
      if (
        noteCard.style.display === "none" &&
        codeCard.style.display === "none" &&
        imagesCard.style.display === "none"
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
    unhideImages.addEventListener("click", () => showSection(imagesCard));

    // Hide buttons
    hideNoteBtn.addEventListener("click", () => hideSection(noteCard));
    hideCodeBtn.addEventListener("click", () => hideSection(codeCard));
    hideImagesBtn.addEventListener("click", () => hideSection(imagesCard));
  </script>



</body>

</html>