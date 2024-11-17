<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_login";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected date from the calendar, default to today
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Fetch attendance report for the selected date
$sql = "SELECT * FROM attendance_report WHERE DATE(date) = ? ORDER BY time_in";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            color: #185519;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .date-time {
            color: #333;
            font-size: 16px;
        }

        .filters-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #e7f3f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .filters-container input[type="text"],
        .filters-container select,
        .filters-container input[type="date"] {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }

        .filters-container label {
            font-size: 14px;
            color: #333;
            margin-right: 10px;
        }

        .filter-item {
            flex: 1;
            margin-right: 20px;
        }



        .filter-item:last-child {
            margin-right: 0;
        }

        .date-navigation {
            display: flex;
            align-items: center;
        }

        .date-navigation button {
            background-color: #185519;
            color: white;
            border: none;
            padding: 10px;
            margin: 0 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .date-navigation button:hover {
            background-color: #133e14;
        }

        .date-display {
            font-size: 16px;
            font-weight: bold;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .attendance-table th,
        .attendance-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        .attendance-table th {
            background-color: #185519;
            color: white;
            font-size: 16px;
        }

        .attendance-table td {
            font-size: 14px;
            color: #333;
        }

        .attendance-table tr:hover {
            background-color: #f1f1f1;
        }

        .status-present {
            color: green;
            font-weight: bold;
        }

        .status-late {
            color: orange;
            font-weight: bold;
        }

        .status-absent {
            color: red;
            font-weight: bold;
        }

        @media screen and (max-width: 768px) {
            .filters-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-item {
                margin-right: 0;
                margin-bottom: 15px;
                width: 100%;
            }

            .filter-item:last-child {
                margin-bottom: 0;
            }

            .date-navigation {
                margin-top: 15px;
                justify-content: space-between;
            }
        }

        .status-present {
            background-color: #d4edda;
            color: #155724;
        }

        .status-late {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-absent {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="header-container">
        <h1>Daily Attendance Reports</h1>
        <div class="date-time" id="dateTime"></div>
    </div>

    <div class="filters-container">
        <div class="filter-item">
            <label for="search">Search Employee</label>
            <input type="text" id="search" placeholder="Enter employee name or number">
        </div>

        <div class="filter-item">
            <label for="statusFilter">Filter by Status</label>
            <select id="statusFilter">
                <option value="all">All</option>
                <option value="Present">Present</option>
                <option value="Late">Late</option>
                <option value="Absent">Absent</option>
            </select>
        </div>

        <div class="date-navigation">
            <button id="prevDay">&lt;</button>
            <input type="date" id="calendar" value="<?php echo $selected_date; ?>">
            <div class="date-display" id="displayDate"><?php echo date('F d, Y', strtotime($selected_date)); ?></div>
            <button id="nextDay">&gt;</button>
        </div>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>Employee Number</th>
                <th>Employee Name</th>
                <th>Status</th>
                <th>Date</th>
                <th>Time-in</th>
                <th>Time-Out</th>
                <th>Actual Time</th>
            </tr>
        </thead>
        <tbody id="attendanceTable">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $status_class = strtolower($row["status"]);
                    echo "<tr class='status-{$status_class}'>";
                    echo "<td>" . htmlspecialchars($row["employee_no"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["employee_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["time_in"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["time_out"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["actual_time"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No attendance records found for this date.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        function updateDateTime() {
            const dateTimeElement = document.getElementById("dateTime");
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            dateTimeElement.textContent = now.toLocaleDateString('en-US', options);
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        const displayDate = document.getElementById("displayDate");
        const calendar = document.getElementById("calendar");

        function updateAttendanceTable(date) {
            window.location.href = 'attendance_reports.php?date=' + date;
        }

        document.getElementById("prevDay").addEventListener("click", function() {
            const currentDate = new Date(calendar.value);
            currentDate.setDate(currentDate.getDate() - 1);
            updateAttendanceTable(currentDate.toISOString().split('T')[0]);
        });

        document.getElementById("nextDay").addEventListener("click", function() {
            const currentDate = new Date(calendar.value);
            currentDate.setDate(currentDate.getDate() + 1);
            updateAttendanceTable(currentDate.toISOString().split('T')[0]);
        });

        calendar.addEventListener("change", function() {
            updateAttendanceTable(calendar.value);
        });

        const searchInput = document.getElementById('search');
        const statusFilter = document.getElementById('statusFilter');
        const tableRows = document.querySelectorAll('#attendanceTable tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();

            tableRows.forEach(row => {
                const employeeNo = row.cells[0].textContent.toLowerCase();
                const employeeName = row.cells[1].textContent.toLowerCase();
                const status = row.cells[2].textContent.toLowerCase();

                const matchesSearch = employeeNo.includes(searchTerm) || employeeName.includes(searchTerm);
                const matchesStatus = statusValue === 'all' || status === statusValue;

                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
    </script>
</body>

</html>