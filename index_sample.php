<?php
// ============================
// PHP TECHNICAL EXAM
// ============================

// This array holds user information, including name, email, age, score, and status.
$users = [
    ["name" => "John Doe", "email" => "john@example.com", "age" => 34, "score" => 82, "status" => "active"],
    ["name" => "Alice Lee", "email" => "alice@example.com", "age" => 24, "score" => 60, "status" => "inactive"],
    ["name" => "Bob Smith", "email" => "bob@example.com", "age" => 28, "score" => 90, "status" => "active"],
    ["name" => "Carla Tran", "email" => "carla@example.com", "age" => 30, "score" => 77, "status" => "inactive"],
    ["name" => "David Yuen", "email" => "david@example.com", "age" => 22, "score" => 69, "status" => "active"],
    ["name" => "Emma White", "email" => "emma@example.com", "age" => 27, "score" => 88, "status" => "active"],
    ["name" => "Frank Zhou", "email" => "frank@example.com", "age" => 26, "score" => 71, "status" => "inactive"],
    ["name" => "Grace Park", "email" => "grace@example.com", "age" => 29, "score" => 95, "status" => "active"],
    ["name" => "Henry Ford", "email" => "henry@example.com", "age" => 31, "score" => 55, "status" => "inactive"],
    ["name" => "Ivy Chen", "email" => "ivy@example.com", "age" => 33, "score" => 78, "status" => "active"],
    ["name" => "Jake Lin", "email" => "jake@example.com", "age" => 21, "score" => 62, "status" => "inactive"],
    ["name" => "Kara Moon", "email" => "kara@example.com", "age" => 25, "score" => 80, "status" => "active"],
    ["name" => "Leo Hart", "email" => "leo@example.com", "age" => 35, "score" => 73, "status" => "active"]
];

// Used to style the score column: green if pass, red if fail.
$passing_score = 75;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Table Display Task</title>

    <!-- ============================
    BASIC PAGE STYLES
    ============================ -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f8f9fa;
        }

        h1 {
            text-align: center;
        }

        .controls {
            text-align: center;
            margin-bottom: 20px;
        }

        select {
            padding: 5px 10px;
            margin: 0 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th,
        td {
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        tr:hover {
            background: #f9f9f9;
        }

        /* Green text for passing scores */
        .pass {
            color: green;
            font-weight: bold;
        }

        /* Red text for failing scores */
        .fail {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>User Table Display Task</h1>

    <!-- ============================
     SORTING CONTROLS
     ============================ -->
    <div class="controls">
        <label>Sort by:</label>
        <select id="sortColumn">
            <option value="name">Name</option>
            <option value="age">Age</option>
            <option value="score">Score</option>
        </select>
        <select id="sortOrder">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select>
    </div>

    <!-- ============================
    USER TABLE DISPLAY
    ============================ -->
    <table id="userTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Score</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Counter for numbering rows
            $count = 1;

            // Loop through each user and display table rows
            foreach ($users as $user): ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['age'] ?></td>

                    <!-- Conditionally apply 'pass' or 'fail' class -->
                    <td class="<?= $user['score'] >= $passing_score ? 'pass' : 'fail' ?>">
                        <?= $user['score'] ?>
                    </td>

                    <!-- Capitalize first letter of status -->
                    <td><?= ucfirst(htmlspecialchars($user['status'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- ============================
    JAVASCRIPT SORTING LOGIC
    ============================ -->

    <script>

        // Get table and dropdown elements
        const table = document.getElementById('userTable');
        const tbody = table.querySelector('tbody');
        const sortColumn = document.getElementById('sortColumn');
        const sortOrder = document.getElementById('sortOrder');

        // Function: Sort the table rows based on column and order
        function sortTable() {
            const column = sortColumn.value; // selected column name
            const order = sortOrder.value;   // 'ascending' or 'descending'
            const rows = Array.from(tbody.querySelectorAll('tr')); // convert rows to array

            const columnIndex = getcolumnIndex(column); // get column index to sort by

            // Sorting logic
            rows.sort((first, second) => {
                const firstValue = first.cells[columnIndex].innerText.trim();
                const secondValue = second.cells[columnIndex].innerText.trim();

                // Sort alphabetically if 'name', numerically otherwise
                if (column === 'name') {
                    return order === 'ascending'
                        ? firstValue.localeCompare(secondValue)
                        : secondValue.localeCompare(firstValue);
                } else {
                    return order === 'ascending'
                        ? firstValue - secondValue
                        : secondValue - firstValue;
                }
            });

            // Clear table and reinsert sorted rows
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        }

        // Function: Get column index based on selected column
        // (Note: Column numbering starts at 0)
        function getcolumnIndex(column) {
            if (column === 'name') return 1;  // 2nd column
            if (column === 'age') return 3;   // 4th column
            if (column === 'score') return 4; // 5th column
        }

        // Event listeners: Trigger sorting when dropdowns change
        sortColumn.addEventListener('change', sortTable);
        sortOrder.addEventListener('change', sortTable);

    </script>

</body>

</html>