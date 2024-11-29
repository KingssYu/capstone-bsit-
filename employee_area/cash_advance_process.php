<?php
// Include database connection
include '../connection/connections.php';
session_start();

//Change the servername IP ADDRESS base on your IP ADDRESS used
// $servername = "162.241.218.154";
// $username = "vssphcom_bsupayroll";
// $password = "Mybossrocks081677";
// $dbname = "vssphcom_bsupayroll";

// $conn = mysqli_connect($servername, $username, $password, $dbname);

// if (!$conn) {
//   die("Database connection failed: " . mysqli_connect_error());
// }

if (isset($_POST['submit_cash_advance'])) {
  // Retrieve form values
  $id = isset($_POST['id']) ? $_POST['id'] : null;
  $employee_no = $_SESSION['employee']['employee_no'];
  $requested_amount = isset($_POST['requested_amount']) ? $_POST['requested_amount'] : null;
  $months = isset($_POST['months']) ? $_POST['months'] : null;
  $remaining_balance = $requested_amount;
  $monthly_payment = isset($_POST['monthly_payment']) ? $_POST['monthly_payment'] : null;

  // Get today's date dynamically
  $paymentDate = date('Y-m-d');

  // Query to check employee status and calculate max allowed cash advance
  $check_sql = "
      SELECT 
          ae.employee_stats,
          up.rate_per_day,
          (up.rate_per_day * 11 * 0.50) AS max_cash_advance 
      FROM 
          adding_employee ae 
      LEFT JOIN 
          under_position up ON ae.rate_id = up.rate_id 
      WHERE 
          ae.employee_no = '$employee_no'
  ";

  $result = mysqli_query($conn, $check_sql);
  $employee = mysqli_fetch_assoc($result);

  if (!$employee || $employee['employee_stats'] !== 'Regular') {
    // If the employee is not regular, show an error and stop execution
    echo "<script>
              alert('Only regular employees can request a cash advance.');
              window.history.back();
            </script>";
    exit;
  }

  // Validate requested amount against the maximum allowable cash advance
  $max_cash_advance = $employee['max_cash_advance'];
  if ($requested_amount > $max_cash_advance) {
    echo "<script>
              alert('Requested amount exceeds 50% of your cutoff pay.');
              window.history.back();
            </script>";
    exit;
  }

  // Check for existing pending or approved cash advances
  $pending_check_sql = "
      SELECT * FROM cash_advance 
      WHERE employee_no = '$employee_no' 
      AND (status = 'Pending' OR status = 'Approved')
  ";
  $pending_result = mysqli_query($conn, $pending_check_sql);

  if (mysqli_num_rows($pending_result) > 0) {
    echo "<script>
              alert('You cannot request another cash advance as you have a pending or approved request.');
              window.history.back();
            </script>";
  } else {
    // Insert new cash advance request
    $insert_sql = "
          INSERT INTO cash_advance (
              id, employee_no, requested_amount, months, remaining_balance, monthly_payment
          ) VALUES (
              '$id', '$employee_no', '$requested_amount', '$months', '$remaining_balance', '$monthly_payment'
          )
      ";

    if (mysqli_query($conn, $insert_sql)) {
      echo "<script>
                  alert('Cash Advance submitted successfully.');
                  window.location.href = document.referrer;
                </script>";
    } else {
      echo "Error: " . $insert_sql . "<br>" . mysqli_error($conn);
    }
  }
}
