<?php
include '../connection/connections.php';

session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: ./../employee_area/portal.php");

  exit;
}

// Get the `employee_no` from the URL
$employee_no = $_GET['employee_no'];

// Query the database for the cash advance configuration
$query = "SELECT * FROM cash_advance_configuration WHERE employee_no = '$employee_no'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
} else {
  $row = ['employee_no' => '', 'cashloan_percentage' => '', 'cashloan_maximum_month' => ''];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Cash Advance</title>
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
      <h1>Cash Advance Configuration for </h1>
    </div>

    <!-- Position -->
    <div class="mb-3">
      <label for="employee_no" class="form-label">Employee No</label>
      <input type="text" class="form-control" id="employee_no" name="employee_no"
        value="<?php echo $row['employee_no']; ?>" readonly>
    </div>

    <div class="mb-3">
      <label for="cashloan_percentage" class="form-label">Cash Loan Max Salary Percentage</label>
      <input type="number" class="form-control" id="cashloan_percentage" name="cashloan_percentage"
        placeholder="Maximum Month" value="<?php echo $row['cashloan_percentage']; ?>" readonly>
    </div>

    <!-- Rate Per Day -->
    <div class="mb-3">
      <label for="cashloan_maximum_month" class="form-label">Max Month</label>
      <input type="number" class="form-control" id="cashloan_maximum_month" name="cashloan_maximum_month"
        placeholder="Maximum Month" value="<?php echo $row['cashloan_maximum_month']; ?>" readonly>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="updateButton" data-bs-toggle="modal"
        data-bs-target="#updateModal">Update</button>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel">Update Cash Advance Configuration</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="update_cash_advance.php">
          <div class="modal-body">
            <!-- Employee No -->
            <div class="mb-3">
              <label for="modal_employee_no" class="form-label">Employee No</label>
              <input type="text" class="form-control" id="modal_employee_no" name="employee_no"
                value="<?php echo $row['employee_no']; ?>" readonly>
            </div>

            <div class="mb-3">
              <label for="modal_cashloan_percentage" class="form-label">Cash Loan Max Salary Percentage</label>
              <input type="text" class="form-control" id="modal_cashloan_percentage" name="modal_cashloan_percentage"
                value="<?php echo $row['employee_no']; ?>" readonly>
            </div>
            <!-- Maximum Month -->
            <div class="mb-3">
              <label for="modal_cashloan_maximum_month" class="form-label">Max Month</label>
              <input type="number" class="form-control" id="modal_cashloan_maximum_month" name="cashloan_maximum_month"
                value="<?php echo $row['cashloan_maximum_month']; ?>" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Add Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Add Bootstrap JS and Popper.js for Modal functionality -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>

</html>