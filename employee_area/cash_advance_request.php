<?php
// Start the session
session_start();

// Check if employee is logged in
if (!isset($_SESSION['employee'])) {
  header("Location: login.php");
  exit();
}

// Get employee data from session
$employee = $_SESSION['employee'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?>'s Dashboard</title>
  <link rel="stylesheet" href="employee_styles/employee_dashboard.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

</head>

<body>
  <?php include './employee_navigation.php'; ?>
  <?php include '../modals/add_cash_request_modal.php'; ?>

  <div class="employee-greeting">
    <h1>Cash Advance</h1>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cashRequestModal">
      Request Cash Advance
    </button>
  </div>
  </div>

  <div class="employee-details-container">
    <table class="directory-table" name="cash_advance_table" id="cash_advance_table">
      <thead>
        <tr>
          <th>Employee #</th>
          <th>Requested Amount</th>
          <th>Date of Request</th>
          <th># of Months</th>
          <th>Remaining Balance</th>
          <th>Status</th>
        </tr>
      </thead>
    </table>
  </div>


</body>

</html>

<!-- Add Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Add Bootstrap JS and Popper.js for Modal functionality -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="../datatables/datatables.min.css" />
<script type="text/javascript" src="../datatables/datatables.min.js"></script>
<script>
  var cash_advance_table = $('#cash_advance_table').DataTable({
    "pagingType": "numbers",
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "./cash_advance_table.php",
      "data": function (d) {
        d.date_from = $('#dateFrom').val();
        d.date_to = $('#dateTo').val();
      }
    },
  });


</script>

<style>
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