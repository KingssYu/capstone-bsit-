<?php
// Include database connection
include '../connection/connections.php';

// Get the department_id from the query parameter
$department_id = isset($_GET['department_id']) ? intval($_GET['department_id']) : 0;

if ($department_id > 0) {
  // Execute the DELETE query directly
  $query = "DELETE FROM department WHERE department_id = $department_id";
  $result = mysqli_query($conn, $query);

  if ($result) {
    // Success: alert and redirect
    echo "
            <script>
                alert('Department deleted successfully.');
                window.location.href = 'department.php'; // Change to your actual page
            </script>
        ";
  } else {
    // Error: alert and redirect
    echo "
            <script>
                alert('Failed to delete the department. Please try again.');
                window.location.href = 'department.php'; // Change to your actual page
            </script>
        ";
  }
} else {
  // Invalid ID: alert and redirect
  echo "
        <script>
            alert('Invalid department ID.');
            window.location.href = 'department.php'; // Change to your actual page
        </script>
    ";
}
