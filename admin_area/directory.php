<?php
include '../connection/connections.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ./../employee_area/portal.php");
    exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total number of employees
$sql_total = "SELECT COUNT(*) AS total_employees FROM adding_employee LEFT JOIN under_position ON adding_employee.rate_id = under_position.rate_id";
$result_total = $conn->query($sql_total);
$total_employees = 0;
if ($result_total->num_rows > 0) {
    $row_total = $result_total->fetch_assoc();
    $total_employees = $row_total['total_employees'];
}


// Fetch departments and positions
$departments = [];
$positions = [];
$sql = "SELECT * FROM adding_employee
        LEFT JOIN department ON adding_employee.department_id = department.department_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row['department_name'];
    }
}
$sql = "SELECT DISTINCT * FROM adding_employee 
        LEFT JOIN under_position ON adding_employee.rate_id = under_position.rate_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $positions[] = $row['rate_position'];
    }
}

// Handle filtering
$where_clause = "";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $where_clause .= " WHERE (last_name LIKE '%$search%' OR first_name LIKE '%$search%' OR middle_name LIKE '%$search%')";
    }
    if (!empty($_GET['department_name']) && $_GET['department_name'] != 'all-departments') {
        $department_name = $conn->real_escape_string($_GET['department_name']);
        $where_clause .= empty($where_clause) ? " WHERE" : " AND";
        $where_clause .= " department_name = '$department_name'";
    }
    if (!empty($_GET['rate_position']) && $_GET['rate_position'] != 'all-positions') {
        $rate_position = $conn->real_escape_string($_GET['rate_position']);
        $where_clause .= empty($where_clause) ? " WHERE" : " AND";
        $where_clause .= " rate_position = '$rate_position'";
    }
}

// Fetch employees
$sql = "SELECT * FROM adding_employee 
        LEFT JOIN under_position ON adding_employee.rate_id = under_position.rate_id
        LEFT JOIN department ON adding_employee.department_id = department.department_id
        " . $where_clause;
$result = $conn->query($sql);
$employees = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directory</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Navigation */
        .sidenav {
            height: 100vh;
            width: 250px;
            background-color: #D5ED9F;
            color: #fff;
            rate_position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        /* Logo Section */
        .sidenav .logo {
            padding: 20px;
            text-align: center;
            background-color: #D5ED9F;
            border-bottom: 1px solid #D5ED9F;
        }

        .sidenav .logo img {
            max-width: 80%;
            height: auto;
        }

        /* Menu Buttons */
        .sidenav .menu {
            flex: 1;
        }

        .sidenav .menu button {
            width: 100%;
            padding: 15px;
            border: none;
            background: #185519;
            color: #fff;
            text-align: left;
            font-size: 16px;
            cursor: pointer;
            border-bottom: 1px solid #ffffff;
            transition: background-color 0.3s ease;
        }

        .sidenav .menu button:hover {
            background: #00712D;
        }

        /* Footer Buttons */
        .sidenav .footer {
            padding: 20px;
            background-color: #D5ED9F;
            text-align: center;
        }

        .sidenav .footer button {
            width: 100%;
            padding: 10px;
            border: none;
            background: #185519;
            border-radius: 50px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sidenav .footer button:hover {
            background: #00712D;
        }

        /* Directory Container */
        .directory-container {
            padding: 20px;
            width: calc(100% - 250px);
            background-color: #f4f4f9;
            height: 100vh;
            overflow: auto;
        }

        .directory-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }

        .directory-header h1 {
            font-size: 24px;
            color: #333;
        }

        /* Search Bar Section */
        .search-container {
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: space-between;
        }

        /* Dropdowns */
        .search-container select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
            color: #333;
        }

        /* Search input */
        .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 25px;
            width: 100%;
            max-width: 500px;
            font-size: 14px;
            color: #333;
            margin: 0 10px;
        }

        /* Search Button */
        .search-container button {
            padding: 10px 20px;
            background-color: #185519;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        /* Directory Table */
        .directory-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .directory-table th,
        .directory-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .directory-table th {
            background-color: #f9f9f9;
            color: #333;
        }

        .directory-table td {
            background-color: #fff;
        }

        /* Profile image */
        .directory-table img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .directory-table .name-cell {
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include './header.php'; ?>


    <!-- Directory Section -->
    <div class="directory-container">
        <div class="directory-header">
            <h1>Directory</h1>
        </div>
        <form method="GET" action="" class="search-container">
            <span style="padding: 10px; font-size: 14px; color: #333;"><?php echo $total_employees; ?> People</span>
            <input type="text" name="search" id="searchInput" placeholder="Search Directory"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                oninput="this.form.submit()">
            <select name="department_name" onchange="this.form.submit()">
                <option value="all-departments">All Departments</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?php echo htmlspecialchars($dept); ?>" <?php echo (isset($_GET['department_name']) && $_GET['department_name'] == $dept) ? 'selected' : ''; ?>><?php echo htmlspecialchars($dept); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="rate_position" onchange="this.form.submit()">
                <option value="all-positions">All Positions</option>
                <?php foreach ($positions as $pos): ?>
                    <option value="<?php echo htmlspecialchars($pos); ?>" <?php echo (isset($_GET['rate_position']) && $_GET['rate_position'] == $pos) ? 'selected' : ''; ?>><?php echo htmlspecialchars($pos); ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- Table with Employee Data -->
        <table class="directory-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td class="name-cell">
                            <?php
                            // Check if the `face_descriptors` blob exists and is not empty
                            if (!empty($employee['face_descriptors'])) {
                                // Base64 encode the binary data for direct embedding in the src attribute of the <img> tag
                                $employee_image_path = 'data:image/jpeg;base64,' . base64_encode($employee['face_descriptors']);
                            } else {
                                // Use a default image if the blob is empty
                                $employee_image_path = "employee_profile/default.png";
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars($employee_image_path); ?>" alt="Profile">
                            <?php echo htmlspecialchars($employee['last_name'] . ', ' . $employee['first_name'] . ' ' . $employee['middle_name']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($employee['contact']); ?></td>
                        <td><?php echo htmlspecialchars($employee['department_name']); ?></td>
                        <td><?php echo htmlspecialchars($employee['rate_position']); ?></td>
                        <td><?php echo htmlspecialchars($employee['address']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var searchParams = new URLSearchParams(formData);
            var newUrl = window.location.pathname + '?' + searchParams.toString();
            fetch(newUrl)
                .then(response => response.text())
                .then(html => {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');
                    document.querySelector('.directory-table').outerHTML = doc.querySelector('.directory-table').outerHTML;
                    history.pushState(null, '', newUrl);
                });
        });
    </script>
</body>

</html>

<!-- Add Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Add Bootstrap JS and Popper.js for Modal functionality -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>