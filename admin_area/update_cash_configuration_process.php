<?php
// Include database connection
include '../connection/connections.php';
session_start(); // Ensure session is started

if (isset($_POST['update_cash_configuration'])) {
  // Sanitize inputs to avoid SQL injection
  $employee_no = isset($_POST['employee_no']) ? mysqli_real_escape_string($conn, $_POST['employee_no']) : null;
  $cashloan_percentage = isset($_POST['cashloan_percentage']) ? mysqli_real_escape_string($conn, $_POST['cashloan_percentage']) : null;
  $cashloan_maximum_month = isset($_POST['cashloan_maximum_month']) ? mysqli_real_escape_string($conn, $_POST['cashloan_maximum_month']) : null;

  // Validate required fields
  if ($employee_no && $cashloan_percentage && $cashloan_maximum_month) {
    // Check limits for cashloan_percentage and cashloan_maximum_month
    if ($cashloan_percentage > 50) {
      echo "<script>
              alert('Cash Loan Percentage cannot exceed 50%.');
              window.location.href = document.referrer;
            </script>";
      exit();
    }

    if ($cashloan_maximum_month > 12) {
      echo "<script>
              alert('Maximum Month cannot exceed 12.');
              window.location.href = document.referrer;
            </script>";
      exit();
    }

    // Check if the record exists
    $check_query = "SELECT * FROM cash_advance_configuration WHERE employee_no = '$employee_no'";
    $check_result = mysqli_query($conn, $check_query);

    if ($check_result && mysqli_num_rows($check_result) > 0) {
      // If the record exists, update it
      $update_query = "
        UPDATE cash_advance_configuration
        SET cashloan_percentage = '$cashloan_percentage',
            cashloan_maximum_month = '$cashloan_maximum_month'
        WHERE employee_no = '$employee_no'
      ";

      if (mysqli_query($conn, $update_query)) {
        echo "<script>
                alert('Configuration updated successfully.');
                window.location.href = document.referrer;
              </script>";
      } else {
        echo "<script>
                alert('Failed to update configuration. Error: " . mysqli_error($conn) . "');
                window.location.href = document.referrer;
              </script>";
      }
    } else {
      // If the record does not exist, insert a new one
      $insert_query = "
        INSERT INTO cash_advance_configuration (employee_no, cashloan_percentage, cashloan_maximum_month)
        VALUES ('$employee_no', '$cashloan_percentage', '$cashloan_maximum_month')
      ";

      if (mysqli_query($conn, $insert_query)) {
        echo "<script>
                alert('Configuration added successfully.');
                window.location.href = document.referrer;
              </script>";
      } else {
        echo "<script>
                alert('Failed to add configuration. Error: " . mysqli_error($conn) . "');
                window.location.href = document.referrer;
              </script>";
      }
    }
  } else {
    echo "<script>
            alert('All fields are required.');
            window.location.href = document.referrer;
          </script>";
  }
} else {
  echo "<script>
          alert('Invalid request.');
          window.location.href = document.referrer;
        </script>";
}
?>