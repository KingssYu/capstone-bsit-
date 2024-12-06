<?php
// Start the session
include '../connection/connections.php';
// Check if employee is logged in
if (!isset($_SESSION['employee'])) {
  header("Location: login.php");
  exit();
}

$employee = $_SESSION['employee'];
$employee_no = $_SESSION['employee']['employee_no'];

// Query to get employee details
$queryDestination = "SELECT * FROM adding_employee WHERE employee_no = '$employee_no'";
$resultDestination = mysqli_query($conn, $queryDestination);

if ($resultDestination && mysqli_num_rows($resultDestination) > 0) {
  $rowDestination = mysqli_fetch_assoc($resultDestination);

  // Check if employee stats are not "Regular"
  if ($rowDestination['employee_stats'] !== 'Regular') {
    // Alert for non-regular employees and prevent access
    echo "<script>alert('You are not a regular employee and cannot access the Cash Advance Request.');</script>";
    // Optionally, redirect to another page
    echo "<script>window.location.href = 'employee_dashboard.php';</script>";
    exit();
  }
}
?>

<div class="sidebar">
  <div class="logo">
    <img src="../image/logobg.png" alt="Company Logo">
  </div>
  <ul class="nav-links">
    <li><a href="employee_dashboard.php">Home</a></li>
    <li><a href="my_profile.php">My Profile</a></li>

    <?php if ($rowDestination['employee_stats'] === 'Regular'): ?>
      <li><a href="cash_advance_request.php">Cash Advance Request</a></li>
    <?php else: ?>
      <li><a href="#"
          onclick="alert('You are not a regular employee and cannot access the Cash Advance Request.'); return false;">Cash
          Advance Request</a></li>
    <?php endif; ?>

    <li><a href="timesheet.php">Timesheet</a></li>
    <li><a href="payslip.php">Payslip Record</a></li>
    <li><a href="changepass_module.php">Change Password</a></li>
    <!-- <li><a href="#">Leave Request</a></li> -->
  </ul>
  <div class="logout">
    <a href="logout.php" class="logout-button">Logout</a>
  </div>
</div>