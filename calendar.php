<?php
$conn = new mysqli("localhost", "root", "", "journal");
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

// --- Handle Date Range (from AJAX or default) ---
$filter = $_GET['filter'] ?? 'week'; // default = week
$baseDate = $_GET['date'] ?? date('Y-m-d');
$base = new DateTime($baseDate);

// Calculate start and end range based on filter
switch ($filter) {
    case 'day':
        $startDate = $base->format('Y-m-d');
        $endDate = $base->format('Y-m-d');
        break;
    case 'week':
        $startDate = $base->modify('monday this week')->format('Y-m-d');
        $endDate = (new DateTime($startDate))->modify('+6 days')->format('Y-m-d');
        break;
    case 'month':
        $startDate = $base->format('Y-m-01');
        $endDate = $base->format('Y-m-t');
        break;
    default:
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');
}

// --- Fetch tasks for this range ---
$sql = "SELECT * FROM tasks WHERE task_date BETWEEN '$startDate' AND '$endDate' ORDER BY task_date ASC";
$result = $conn->query($sql);

$tasks_by_date = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks_by_date[$row['task_date']][] = $row;
    }
}
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
        /* --- Layout --- */
        .calendar-container {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* Each day box */
        .day-column {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background: #f8f9fa;
            min-height: 150px;
            padding: 10px;
            flex: 1 1 calc(100% / 7 - 10px);
            /* ✅ 7 equal-width columns per row */
            display: flex;
            flex-wrap: wrap;
            flex-direction: column;
            box-sizing: border-box;
            transition: all 0.2s ease-in-out;
        }

        .day-column:hover {
            background: #eef6ff;
            transform: translateY(-2px);
        }

        /* Header of each day */
        .day-header {
            font-weight: 600;
            text-align: center;
            margin-bottom: 8px;
        }

        /* Task item inside each day */
        .task-item {
            background: #fff;
            border-radius: 8px;
            padding: 8px;
            margin-bottom: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            font-size: 0.9rem;
        }

        .task-done {
            text-decoration: line-through;
            color: #0d6efd;
        }

        /* --- Filter Buttons --- */
        .filter-btns .btn {
            border-radius: 50px;
            font-weight: 500;
        }

        .filter-btns .btn.active {
            background-color: #0d6efd;
            color: #fff;
        }

        /* --- Navigation Buttons --- */
        .nav-btns button {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
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



                        <div class="container py-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="filter-btns btn-group">
                                    <button class="btn btn-outline-primary filter" data-filter="day">Day</button>
                                    <button class="btn btn-outline-primary filter active"
                                        data-filter="week">Week</button>
                                    <button class="btn btn-outline-primary filter" data-filter="month">Month</button>
                                </div>

                                <div class="nav-btns d-flex gap-2">
                                    <button id="prevBtn" class="btn btn-outline-secondary">&lt;</button>
                                    <button id="nextBtn" class="btn btn-outline-secondary">&gt;</button>
                                </div>
                            </div>

                            <div class="calendar-container d-flex gap-2 flex-wrap">
                                <?php
                                // Generate days dynamically
                                $period = new DatePeriod(
                                    new DateTime($startDate),
                                    new DateInterval('P1D'),
                                    (new DateTime($endDate))->modify('+1 day')
                                );

                                foreach ($period as $day) {
                                    $dayKey = $day->format('Y-m-d');
                                    echo "<div class='day-column'>";
                                    echo "<div class='day-header'>" . $day->format('M d (D)') . "</div>";

                                    if (isset($tasks_by_date[$dayKey])) {
                                        foreach ($tasks_by_date[$dayKey] as $task) {
                                            $done = $task['task_status'] === 'Done' ? 'task-done' : '';
                                            echo "<div class='task-item $done'>" . htmlspecialchars($task['task_text']) . "</div>";
                                        }
                                    } else {
                                        echo "<div class='text-muted small text-center mt-4'>No tasks</div>";
                                    }

                                    echo "</div>";
                                }
                                ?>
                            </div>
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
        let currentFilter = 'week';
        let currentDate = '<?= date('Y-m-d', strtotime($baseDate)) ?>';

        // Load new range dynamically
        function loadCalendar(filter, date) {
            $.get('<?= basename(__FILE__) ?>', { filter, date }, function (html) {
                const newHtml = $(html).find('.calendar-container').html();
                $('.calendar-container').html(newHtml);
            });
        }

        $('.filter').on('click', function () {
            $('.filter').removeClass('active');
            $(this).addClass('active');
            currentFilter = $(this).data('filter');
            loadCalendar(currentFilter, currentDate);
        });

        $('#prevBtn').on('click', function () {
            shiftDate(-1);
        });

        $('#nextBtn').on('click', function () {
            shiftDate(1);
        });

        function shiftDate(direction) {
            let d = new Date(currentDate);
            if (currentFilter === 'day') d.setDate(d.getDate() + direction);
            else if (currentFilter === 'week') d.setDate(d.getDate() + (7 * direction));
            else if (currentFilter === 'month') d.setMonth(d.getMonth() + direction);

            currentDate = d.toISOString().split('T')[0];
            loadCalendar(currentFilter, currentDate);
        }
    </script>


</body>

</html>