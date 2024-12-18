<?php
// Include database connection
include '../connection/connections.php';

// Get today's date dynamically
$paymentDate = date('Y-m-d');
$todayDay = date('j'); // Get the day of the month (1-31)
$lastDayOfMonth = date('t'); // Get the last day of the current month

// // Check if today is the 15th or the last day of the month
// if ($todayDay != 15 && $todayDay != $lastDayOfMonth) {
//   echo "<script>
//           alert('Payroll can only be processed on the 15th and the last day of the month.');
//           window.location.href = document.referrer; // Redirect to the previous page
//         </script>";
//   exit(); // Stop further execution
// }
// Get employee_no from URL

if (isset($_POST['submit_payroll'])) {
  // Retrieve values from the form
  $employee_no = isset($_POST['employee_no']) ? $_POST['employee_no'] : null;
  $rate_per_hour = isset($_POST['ratePerHour']) ? $_POST['ratePerHour'] : null;
  $basic_per_day = isset($_POST['basicPerDay']) ? $_POST['basicPerDay'] : null;
  $number_of_days = isset($_POST['numberOfDays']) ? $_POST['numberOfDays'] : null;
  $total_hours = isset($_POST['totalHours']) ? $_POST['totalHours'] : null;
  $gross_pay = isset($_POST['grossPay']) ? $_POST['grossPay'] : null;
  $sss = isset($_POST['sss']) ? $_POST['sss'] : null;
  $philhealth = isset($_POST['philhealth']) ? $_POST['philhealth'] : null;
  $pagibig = isset($_POST['pagibig']) ? $_POST['pagibig'] : null;
  $total_deductions = isset($_POST['totalDeductions']) ? $_POST['totalDeductions'] : null;
  $cash_advance = isset($_POST['cashAdvance']) ? $_POST['cashAdvance'] : null;
  $cash_advance_pay = isset($_POST['cashAdvancePay']) ? $_POST['cashAdvancePay'] : null;
  $net_pay = isset($_POST['netPay']) ? $_POST['netPay'] : null;

  // Start a transaction
  mysqli_begin_transaction($conn);

  try {
    // Create the SQL query to insert the data into the payroll table
    $sql = "INSERT INTO payroll (
                employee_no,
                rate_per_hour,
                basic_per_day,
                number_of_days,
                total_hours,
                gross_pay,
                sss,
                philhealth,
                pagibig,
                total_deductions,
                cash_advance,
                cash_advance_pay,
                net_pay,
                payment_date,
                payment_notification
            ) VALUES (
                '$employee_no',
                '$rate_per_hour',
                '$basic_per_day',
                '$number_of_days',
                '$total_hours',
                '$gross_pay',
                '$sss',
                '$philhealth',
                '$pagibig',
                '$total_deductions',
                '$cash_advance',
                '$cash_advance_pay',
                '$net_pay',
                '$paymentDate',
                'Unread'
            )";

    // Execute the payroll query
    if (!mysqli_query($conn, $sql)) {
      throw new Exception("Error inserting payroll record: " . mysqli_error($conn));
    }

    if ($cash_advance_pay > 0) {
      // Fetch the current remaining balance first
      $balanceCheckQuery = "SELECT remaining_balance FROM cash_advance WHERE employee_no = '$employee_no' AND status = 'Approved'";
      $balanceResult = mysqli_query($conn, $balanceCheckQuery);

      if ($balanceResult) {
        $row = mysqli_fetch_assoc($balanceResult);
        $current_balance = $row['remaining_balance'];

        if ($cash_advance_pay > $current_balance) {
          // Show error alert and stop execution
          echo "<script>
                  alert('Error: Cash advance payment exceeds the remaining balance.');
                  window.history.back(); // Go back to the previous page
                </script>";
          exit(); // Stop further execution
        }

        // Calculate the new balance and prepare the status
        $new_balance = $current_balance - $cash_advance_pay;
        $new_status = ($new_balance == 0) ? 'Paid' : 'Approved';

        // Update cash advance record
        $updateQuery = "
                UPDATE cash_advance 
                SET 
                    remaining_balance = $new_balance,
                    paid_amount = paid_amount + $cash_advance_pay,
                    status = '$new_status'
                WHERE 
                    employee_no = '$employee_no'
                    AND status = 'Approved'";

        if (!mysqli_query($conn, $updateQuery)) {
          throw new Exception("Error updating cash_advance record: " . mysqli_error($conn));
        }
      } else {
        throw new Exception("Error fetching balance: " . mysqli_error($conn));
      }
    }


    // Update the attendance_report table
    $attendanceUpdateQuery = "
        UPDATE attendance_report
        SET is_paid = 1
        WHERE employee_no = '$employee_no'
        AND date <= '$paymentDate'
        AND is_paid = 0";

    if (!mysqli_query($conn, $attendanceUpdateQuery)) {
      throw new Exception("Error updating attendance_report: " . mysqli_error($conn));
    }

    // Commit the transaction
    mysqli_commit($conn);

    // Success message and redirect
    echo "<script>
            alert('Payroll record added, cash advance updated, and attendance marked as paid successfully.');
            window.location.href = document.referrer; // Redirects to the previous page
          </script>";
  } catch (Exception $e) {
    // Rollback the transaction on error
    mysqli_rollback($conn);
    echo "Transaction failed: " . $e->getMessage();
  }
}
