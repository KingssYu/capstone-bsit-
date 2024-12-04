<?php
// Include database connection
include '../connection/connections.php';
session_start();

if (isset($_POST['add_position']) && $_POST['add_position'] == '1') {
  // Retrieve and sanitize input
  $position = isset($_POST['rate_position']) ? mysqli_real_escape_string($conn, trim($_POST['rate_position'])) : null;
  $department_id = isset($_POST['department_id']) ? mysqli_real_escape_string($conn, trim($_POST['department_id'])) : null;

  // Validate inputs
  if (!$position || !$department_id) {
    echo "<script>
                alert('Both position and department are required.');
                window.history.back();
              </script>";
    exit();
  }

  // Check if the position already exists in the database for the selected department
  $check_sql = "SELECT * FROM rate_position WHERE rate_position = '$position' AND department_id = '$department_id'";
  $result = mysqli_query($conn, $check_sql);

  if (mysqli_num_rows($result) > 0) {
    // If a duplicate is found, show an alert and stop further execution
    echo "<script>
                alert('This position already exists in the selected department.');
                window.history.back();
              </script>";
  } else {
    // Insert the new position into the database
    $insert_sql = "INSERT INTO rate_position (rate_position, department_id) VALUES ('$position', '$department_id')";

    if (mysqli_query($conn, $insert_sql)) {
      // If the insert was successful, show a success message and redirect
      echo "<script>
                    alert('Position added successfully.');
                    window.location.href = document.referrer;
                  </script>";
    } else {
      // Handle errors
      echo "Error: " . $insert_sql . "<br>" . mysqli_error($conn);
    }
  }
} else {
  echo "<script>
            alert('Invalid request.');
            window.history.back();
          </script>";
}
