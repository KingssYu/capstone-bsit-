<?php
// Include database connection
include '../connection/connections.php';

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
  // Retrieve values from the form
  // $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : null;
  $id = isset($_POST['employee_no']) ? $_POST['id'] : null;
  $employee_no = isset($_POST['employee_no']) ? $_POST['employee_no'] : null;
  $requested_amount = isset($_POST['requested_amount']) ? $_POST['requested_amount'] : null;
  $months = isset($_POST['months']) ? $_POST['months'] : null;
  $remaining_balance = isset($_POST['requested_amount']) ? $_POST['requested_amount'] : null;
  $monthly_payment = isset($_POST['monthly_payment']) ? $_POST['monthly_payment'] : null;


  // Get today's date dynamically
  $paymentDate = date('Y-m-d'); // This will return the current date in 'YYYY-MM-DD' format

  // Create the SQL query to insert the data into the database
  $sql = "INSERT INTO cash_advance (
                id,
                employee_no,
                requested_amount,
                months,
                remaining_balance,
                monthly_payment
            ) VALUES (
                '$id',
                '$employee_no',
                '$requested_amount',
                '$months',
                '$remaining_balance',
                '$monthly_payment'
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