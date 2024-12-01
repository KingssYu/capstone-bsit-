<?php
// Include database connection
include '../connection/connections.php';

// Get employee_no and status from POST request
$employee_no = isset($_POST['employee_no']) ? $conn->real_escape_string($_POST['employee_no']) : '';
$employee_status = isset($_POST['employeeStatus']) ? $conn->real_escape_string($_POST['employeeStatus']) : '';

// Check if employee_no and employee_status are not empty
if (empty($employee_no)) {
  echo "<script>
            alert('Employee number is missing.');
            window.history.back(); // Go back to the previous page
          </script>";
  exit; // Stop the script if employee_no is missing
}

if (empty($employee_status)) {
  echo "<script>
            alert('Employee status is missing.');
            window.history.back(); // Go back to the previous page
          </script>";
  exit; // Stop the script if employee_status is missing
}

if (!empty($employee_no) && !empty($employee_status)) {
  // Update the employee status in the database
  $sql = "UPDATE adding_employee SET employee_stats = '$employee_status' WHERE employee_no = '$employee_no'";

  if ($conn->query($sql) === TRUE) {
    echo "<script>
                    alert('Employee status updated successfully.');
                    window.location.href = document.referrer; // Redirects to the previous page
                  </script>";
  } else {
    echo "<script>
                    alert('Error updating employee status: " . $conn->error . "');
                    window.history.back(); // Go back to the previous page
                  </script>";
  }
}
