<?php
// Include database connection
include '../connection/connections.php';

if (isset($_POST['submit_cash_advance'])) {
  // Retrieve values from the form
  // $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : null;
  $id = isset($_POST['employee_no']) ? $_POST['id'] : null;
  $employee_no = isset($_POST['employee_no']) ? $_POST['employee_no'] : null;
  $requested_amount = isset($_POST['requested_amount']) ? $_POST['requested_amount'] : null;
  $months = isset($_POST['months']) ? $_POST['months'] : null;
  $remaining_balance = isset($_POST['remaining_balance']) ? $_POST['remaining_balance'] : null;

  // Get today's date dynamically
  $paymentDate = date('Y-m-d'); // This will return the current date in 'YYYY-MM-DD' format

  // Create the SQL query to insert the data into the database
  $sql = "INSERT INTO cash_advance (
                id,
                employee_no,
                requested_amount,
                months,
                remaining_balance
            ) VALUES (
                '$id',
                '$employee_no',
                '$requested_amount',
                '$months',
                '$remaining_balance'
            )";

  // Execute the query
  if (mysqli_query($conn, $sql)) {
    // If the insert was successful, show an alert and redirect
    echo "<script>
            alert('Cash Advance submitted successfully.');
            window.location.href = document.referrer; // Redirects to the previous page
          </script>";

  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
}
?>