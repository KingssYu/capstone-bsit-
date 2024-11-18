<?php
// Include database connection
include '../connection/connections.php';

if (isset($_POST['edit_cash_advance'])) {
  // Retrieve values from the form
  $employee_no = isset($_POST['employee_no']) ? $_POST['employee_no'] : null;
  $status = 'Approved';  // Set the status to 'Approved'

  // Create the SQL query to update the data
  $sql = "UPDATE cash_advance SET status = '$status' WHERE employee_no = '$employee_no'";

  // Execute the query
  if (mysqli_query($conn, $sql)) {
    // If the update was successful, show an alert and redirect
    echo "<script>
            alert('Cash Advance Updated successfully.');
            window.location.href = document.referrer; // Redirects to the previous page
          </script>";
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
}
?>