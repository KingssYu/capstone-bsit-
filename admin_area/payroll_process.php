<?php
// Include database connection
include '../connection/connections.php';

if (isset($_POST['submit_payroll'])) {
  // Retrieve values from the form
  // $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : null;
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
  $payment_date = isset($_POST['payment_date']) ? $_POST['payment_date'] : null;

  // Get today's date dynamically
  $paymentDate = date('Y-m-d'); // This will return the current date in 'YYYY-MM-DD' format

  // Create the SQL query to insert the data into the database
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
                payment_date
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
                '$paymentDate'  -- Dynamic payment date
            )";

  // Execute the query
  if (mysqli_query($conn, $sql)) {
    // If the insert was successful, show an alert and redirect
    echo "<script>
            alert('Payroll record added successfully.');
            window.location.href = document.referrer; // Redirects to the previous page
          </script>";

  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
}
?>