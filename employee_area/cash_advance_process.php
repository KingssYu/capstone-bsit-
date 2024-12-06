<?php
// Include database connection
include '../connection/connections.php';
session_start();

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

  // Query to get employee's salary and cash advance configuration
  $check_sql = "
      SELECT 
          ae.employee_stats,
          up.rate_per_hour,
          cac.cashloan_percentage,
          cac.cashloan_maximum_month
      FROM 
          adding_employee ae 
      LEFT JOIN 
          under_position up ON ae.rate_id = up.rate_id 
      LEFT JOIN 
          cash_advance_configuration cac ON ae.employee_no = cac.employee_no
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

  // Retrieve the cashloan_maximum_month and validate against the submitted months
  $cashloan_maximum_month = $employee['cashloan_maximum_month'];

  // Check if the requested months exceed the maximum allowed months
  if ($months > $cashloan_maximum_month) {
    echo "<script>
              alert('Requested months exceed the maximum allowed months of $cashloan_maximum_month.');
              window.history.back();
            </script>";
    exit;
  }

  // Calculate the maximum cash advance based on the salary and cashloan_percentage
  $rate_per_hour = $employee['rate_per_hour'];
  $cashloan_percentage = $employee['cashloan_percentage'];

  // Monthly salary calculation
  $monthly_salary = $rate_per_hour * 8 * 22; // Assuming 8 hours/day, 22 days/month
  $max_cash_advance = ($monthly_salary * $cashloan_percentage) / 100;

  // Adjust the maximum cash advance by the cashloan_maximum_month
  $adjusted_max_cash_advance = $max_cash_advance * $cashloan_maximum_month;

  // Validate requested amount against the maximum allowable cash advance (considering months)
  if ($requested_amount > $adjusted_max_cash_advance) {
    echo "<script>
              alert('Requested amount exceeds the maximum cash advance for $months months.');
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
              id, employee_no, requested_amount, months, remaining_balance, monthly_payment, notification_status
          ) VALUES (
              '$id', '$employee_no', '$requested_amount', '$months', '$remaining_balance', '$monthly_payment', 'Unread'
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
