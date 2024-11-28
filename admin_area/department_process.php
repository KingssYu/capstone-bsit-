<?php
// Include database connection
include '../connection/connections.php';

// Check if the form is submitted
if (isset($_POST['add_position'])) {
  // Get the department name from the form and sanitize input
  $department_name = mysqli_real_escape_string($conn, $_POST['department_name']);

  // Check if the department name is empty
  if (!empty($department_name)) {
    // Insert into the department table
    $sql = "INSERT INTO department (department_name) VALUES ('$department_name')";

    if (mysqli_query($conn, $sql)) {
      // Success: alert and redirect back to the admin area
      echo "
                <script>
                    alert('Department added successfully.');
                    window.location.href = 'department.php'; // Change to your actual page
                </script>
            ";
    } else {
      // Error: alert and redirect back to the admin area
      echo "
                <script>
                    alert('Error adding department: " . mysqli_error($conn) . "');
                    window.location.href = 'department.php'; // Change to your actual page
                </script>
            ";
    }
  } else {
    // Error: empty department name
    echo "
            <script>
                alert('Department name cannot be empty.');
                window.location.href = 'department.php'; // Change to your actual page
            </script>
        ";
  }
} else {
  // Invalid access: alert and redirect
  echo "
        <script>
            alert('Invalid access.');
            window.location.href = 'department.php'; // Change to your actual page
        </script>
    ";
}
