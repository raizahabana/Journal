<?php
// ==============================
// ✅ Task Insert Section (AJAX Submit)
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_text'])) {
  $conn = new mysqli("localhost", "root", "", "journal");
  if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

  $task_text = $_POST['task_text'] ?? '';
  $task_date = $_POST['task_date'] ?? '';

  if (empty($task_text) || empty($task_date)) {
    echo "Please fill out all fields.";
    exit;
  }

  $today = date('Y-m-d');
  if ($task_date < $today) {
    echo "You cannot set a task in the past.";
    exit;
  }

  $sql = "INSERT INTO tasks (task_text, task_date, task_status)
          VALUES ('$task_text', '$task_date', 'Pending')";
  echo $conn->query($sql) ? "✅ Task added successfully!" : "❌ Error: " . $conn->error;
  $conn->close();
  exit;
}

// ==============================
// ✅ Update Task Status via AJAX
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task'])) {
  $task_id = $_POST['task_id'];
  $status = $_POST['status'];

  $conn = new mysqli("localhost", "root", "", "journal");
  if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

  $sql = "UPDATE tasks SET task_status='$status' WHERE id=$task_id";
  echo $conn->query($sql) ? "✅ Task updated!" : "❌ Error updating task.";
  $conn->close();
  exit;
}

// ==============================
// ✅ Fetch Tasks by Date
// ==============================
$conn = new mysqli("localhost", "root", "", "journal");
if ($conn->connect_error)
  die("Connection failed: " . $conn->connect_error);

$sql = "SELECT * FROM tasks ORDER BY task_date ASC, created_at DESC";
$result = $conn->query($sql);

$tasks_by_date = [];
while ($row = $result->fetch_assoc()) {
  $tasks_by_date[$row['task_date']][] = $row;
}
$conn->close();
?>

<?php
// ==============================
// ✅ Page Layout Section (HTML)
// ==============================
define('BASE_PATH', __DIR__ . '/./');
define('BASE_URL', '/Journal/');
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>To Do</title>
  <link rel="shortcut icon" type="image/png" href="<?php echo BASE_URL; ?>assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/styles.min.css" />


  <style>
    /* --- Modern Task Card --- */
    .task-card {
      border: 1px solid #e0e0e0;
      border-radius: 14px;
      padding: 20px;
      margin-bottom: 20px;
      background: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    .task-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
    }

    /* --- Task Item Layout --- */
    .task-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .task-item:last-child {
      border-bottom: none;
    }

    /* --- Custom Checkbox --- */
    .custom-checkbox {
      position: relative;
      cursor: pointer;
      user-select: none;
      font-size: 16px;
    }

    .custom-checkbox input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
    }

    .checkmark {
      height: 20px;
      width: 20px;
      border-radius: 50%;
      border: 2px solid #bfbfbf;
      display: inline-block;
      margin-right: 10px;
      position: relative;
      transition: all 0.3s ease;
    }

    /* When checked - fill color */
    .custom-checkbox input:checked~.checkmark {
      border-color: #6c757d;
      /* gray border */
      background-color: #cfe2ff;
      /* light blue inside */
    }

    /* Inner dot (animated) */
    .custom-checkbox input:checked~.checkmark::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      width: 8px;
      height: 8px;
      background-color: #0d6efd;
      /* Bootstrap blue */
      border-radius: 50%;
      transform: translate(-50%, -50%) scale(1);
      transition: all 0.3s ease;
    }

    .custom-checkbox .checkmark::after {
      content: "";
      position: absolute;
      transform: translate(-50%, -50%) scale(0);
      transition: all 0.3s ease;
    }

    /* --- Text for completed tasks --- */
    .task-done {
      text-decoration: line-through;
      color: #9e9e9e;
    }

    .task-text {
      color: #212529;
      transition: color 0.3s ease;
    }

    /* --- Badge Styling --- */
    .badge {
      font-size: 0.8rem;
      padding: 6px 10px;
      border-radius: 8px;
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
      </div>
    </div>

    <!-- Sidebar Start -->
    <?php include BASE_PATH . 'include/navbar-part/sidebar.php'; ?>
    <!-- Sidebar End -->

    <!-- Main Wrapper -->
    <div class="body-wrapper">

      <!-- Header Start -->
      <?php include BASE_PATH . 'include/navbar-part/header.php'; ?>
      <!-- Header End -->

      <!-- Pages Start -->
      <div class="body-wrapper-inner">
        <div class="container-fluid">
          <div class="row mb-4">

            <div class="col-lg-10"></div>

            <div class="col-lg-2">
              <!-- ADD BUTTON -->
              <div class="d-flex flex-wrap gap-3 justify-content-end">
                <button id="addBtn" class="btn btn-success btn-sm w-25 w-md-25 w-lg-25" data-bs-toggle="modal"
                  data-bs-target="#addTaskModal"><i class="ti ti-plus"></i></button>
              </div>
            </div>
          </div>

          <!-- ADD TASK MODAL -->
          <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form id="addTaskForm">
                  <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <!-- Task Title -->
                    <div class="mb-3">
                      <label for="task_text" class="form-label">Task Title</label>
                      <input type="text" id="task_text" name="task_text" class="form-control" required>
                    </div>

                    <!-- Task Date -->
                    <div class="mb-3">
                      <label for="task_date" class="form-label">Task Date</label>
                      <input type="date" id="task_date" name="task_date" class="form-control" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Task</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- DISPLAY ALL THE TASKS -->
          <?php if (!empty($tasks_by_date)): ?>
            <?php foreach ($tasks_by_date as $date => $tasks): ?>
              <div class="task-card">
                <h5 class="text-primary mb-3 d-flex align-items-center gap-2">
                  <iconify-icon icon="mdi:calendar"></iconify-icon>
                  <?= date("F d, Y", strtotime($date)); ?>
                </h5>

                <?php foreach ($tasks as $task): ?>
                  <div class="task-item">
                    <label class="custom-checkbox d-flex align-items-center mx-3 mb-0">
                      <input type="checkbox" class="task-checkbox" data-id="<?= $task['id']; ?>"
                        <?= $task['task_status'] === 'Done' ? 'checked' : ''; ?>>
                      <span class="checkmark"></span>
                      <span class="task-text <?= $task['task_status'] === 'Done' ? 'task-done' : ''; ?>">
                        <?= htmlspecialchars($task['task_text']); ?>
                      </span>
                    </label>

                    <span class="badge <?= $task['task_status'] === 'Done' ? 'bg-success' : 'bg-warning'; ?>">
                      <?= $task['task_status']; ?>
                    </span>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="alert alert-info">No tasks found. Add one to get started!</div>
          <?php endif; ?>




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
      <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

      <?php include BASE_PATH . 'include/pages/bottom.php'; ?>

      <script>
        $(document).ready(function () {
          // 🗓️ Restrict past dates (only today and future)
          const today = new Date().toISOString().split('T')[0];
          $('#task_date').attr('min', today);
          $('#task_date').val(today); // Default to today

          // 🧾 Handle form submission via AJAX
          $('#addTaskForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
              url: 'to-do.php',
              type: 'POST',
              data: formData,
              success: function (response) {
                alert(response);
                $('#addTaskModal').modal('hide');
                $('#addTaskForm')[0].reset();
                $('#task_date').val(today);
              },
              error: function () {
                alert('Error adding task. Please try again.');
              }
            });
          });

          // Update Task Status
          $('.task-checkbox').on('change', function () {
            const id = $(this).data('id');
            const status = $(this).is(':checked') ? 'Done' : 'Pending';
            $.ajax({
              url: 'to-do.php',
              type: 'POST',
              data: { update_task: true, task_id: id, status: status },
              success: function (response) {
                console.log(response);
                location.reload();
              }
            });
          });
        });
      </script>

</body>

</html>