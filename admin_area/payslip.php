<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: ./../employee_area/portal.php");

  exit;
}

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "admin_login";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch total number of employees
$sql_total = "SELECT COUNT(*) AS total_employees FROM adding_employee LEFT JOIN rate_position ON adding_employee.rate_id = rate_position.rate_id";
$result_total = $conn->query($sql_total);
$total_employees = 0;
if ($result_total->num_rows > 0) {
  $row_total = $result_total->fetch_assoc();
  $total_employees = $row_total['total_employees'];
}


// Fetch departments and positions
$departments = [];
$positions = [];
$sql = "SELECT DISTINCT department FROM adding_employee";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $departments[] = $row['department'];
  }
}
$sql = "SELECT DISTINCT * FROM adding_employee LEFT JOIN rate_position ON adding_employee.rate_id = rate_position.rate_id";
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
  if (!empty($_GET['department']) && $_GET['department'] != 'all-departments') {
    $department = $conn->real_escape_string($_GET['department']);
    $where_clause .= empty($where_clause) ? " WHERE" : " AND";
    $where_clause .= " department = '$department'";
  }
  if (!empty($_GET['rate_position']) && $_GET['rate_position'] != 'all-positions') {
    $rate_position = $conn->real_escape_string($_GET['rate_position']);
    $where_clause .= empty($where_clause) ? " WHERE" : " AND";
    $where_clause .= " rate_position = '$rate_position'";
  }
}

// Fetch employees
$sql = "SELECT * FROM adding_employee LEFT JOIN rate_position ON adding_employee.rate_id = rate_position.rate_id" . $where_clause;
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
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
      <h1>Payslip</h1>
    </div>

    <!-- Table with Employee Data -->
    <table class="directory-table" name="payslip_table" id="payslip_table">
      <thead>
        <tr>
          <th>Payslip ID</th>
          <th>Net Pay</th>
          <th>Date From</th>
          <th>Date To</th>
        </tr>
      </thead>
    </table>
  </div>

  <link rel="stylesheet" type="text/css" href="../datatables/datatables.min.css" />
  <script type="text/javascript" src="../datatables/datatables.min.js"></script>
  <script>
    var payslip_table = $('#payslip_table').DataTable({
      "pagingType": "numbers",
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "./payslip_table.php",
        "data": function(d) {
          d.date_from = $('#dateFrom').val();
          d.date_to = $('#dateTo').val();
        }
      },
    });
  </script>

</body>

</html>