<?php
$conn = mysqli_connect("localhost", "root", "", "journal");
if (!$conn)
    die("Connection failed: " . mysqli_connect_error());

// Get parameters
$view = $_GET['view'] ?? 'day';
$offset = intval($_GET['offset'] ?? 0); // Number of days/weeks/months moved

// Base date
$today = date('Y-m-d');
$baseDate = date('Y-m-d', strtotime("$offset $view", strtotime($today)));

// Determine date range
if ($view === 'day') {
    $start = $baseDate;
    $end = $baseDate;
} elseif ($view === 'week') {
    $start = date('Y-m-d', strtotime("monday this week", strtotime($baseDate)));
    $end = date('Y-m-d', strtotime("sunday this week", strtotime($baseDate)));
} else { // month
    $start = date('Y-m-01', strtotime($baseDate));
    $end = date('Y-m-t', strtotime($baseDate));
}

// Fetch tasks from database
$query = "SELECT * FROM tasks WHERE task_date BETWEEN '$start' AND '$end' ORDER BY task_date ASC";
$result = mysqli_query($conn, $query);

$tasks_by_date = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tasks_by_date[$row['task_date']][] = $row;
}

// Generate dates for display
$dates = [];
$current = strtotime($start);
while ($current <= strtotime($end)) {
    $dates[] = date('Y-m-d', $current);
    $current = strtotime('+1 day', $current);
}

// Calendar rendering
foreach ($dates as $date):
    $formattedDate = date('M d, Y', strtotime($date));
    echo "<div class='day-box'>";
    echo "<div class='day-title'>$formattedDate</div>";

    if (!empty($tasks_by_date[$date])) {
        foreach ($tasks_by_date[$date] as $task) {
            $doneClass = $task['task_status'] === 'Done' ? 'task-done' : '';
            echo "<div class='task-item $doneClass'>📝 " . htmlspecialchars($task['task_text']) . "</div>";
        }
    } else {
        echo "<div class='text-muted small'>No tasks</div>";
    }

    echo "</div>";
endforeach;
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
    <title>Calendar</title>
    <link rel="shortcut icon" type="image/png" href="<?php echo BASE_URL; ?>assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/styles.min.css" />


    <style>
        /* Calendar Layout */
        .calendar {
            display: grid;
            gap: 15px;
            margin-top: 20px;
        }

        .calendar.day {
            grid-template-columns: repeat(1, 1fr);
        }

        .calendar.week {
            grid-template-columns: repeat(7, 1fr);
        }

        .calendar.month {
            grid-template-columns: repeat(7, 1fr);
        }

        /* Box Style */
        .day-box {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            padding: 15px;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .day-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.12);
        }

        .day-title {
            font-weight: 600;
            color: #0d6efd;
            margin-bottom: 10px;
            font-size: 15px;
            text-align: center;
        }

        .task-item {
            font-size: 0.9rem;
            border-left: 3px solid #0d6efd;
            background: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 6px;
            padding: 4px 8px;
        }

        .task-done {
            text-decoration: line-through;
            color: gray;
            background: #e9ecef;
        }

        .filter-btn.active {
            background-color: #0d6efd !important;
            color: white !important;
        }

        .nav-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .nav-buttons button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: #0d6efd;
            color: white;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .nav-buttons button:hover {
            background: #0b5ed7;
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


                        <div class="container py-5">
                            <h2 class="text-center mb-4">🗓 Task Calendar View</h2>

                            <!-- Filter Buttons -->
                            <div class="d-flex justify-content-center gap-3 mb-3">
                                <button class="btn btn-outline-primary filter-btn active" data-view="day">Day</button>
                                <button class="btn btn-outline-primary filter-btn" data-view="week">Week</button>
                                <button class="btn btn-outline-primary filter-btn" data-view="month">Month</button>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="nav-buttons">
                                <button id="prevBtn">←</button>
                                <span id="viewLabel" class="fw-semibold fs-5"></span>
                                <button id="nextBtn">→</button>
                            </div>

                            <!-- Calendar Display -->
                            <div id="calendarContainer" class="calendar day"></div>
                        </div>

                      


                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

    <?php include BASE_PATH . 'include/pages/bottom.php'; ?>

    <script>
        $(document).ready(function () {
            loadTasks('day'); // Default view

            // Switch view on button click
            $('.filter-btn').on('click', function () {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');
                let view = $(this).data('view');
                loadTasks(view);
            });

            function loadTasks(viewType) {
                $.ajax({
                    url: 'calendar.php',
                    type: 'GET',
                    data: { view: viewType },
                    success: function (response) {
                        $('#calendarContainer').html(response);
                        $('#calendarContainer')
                            .addClass(viewType);
                    }
                });
            }
        });
    </script>

</body>

</html>