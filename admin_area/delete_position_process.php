<?php
// Include database connection
include '../connection/connections.php';

// Get the rate_id from the query parameter
$rate_id = isset($_GET['rate_id']) ? intval($_GET['rate_id']) : 0;

if ($rate_id > 0) {
  // Execute the DELETE query directly
  $query = "DELETE FROM rate_position WHERE rate_id = $rate_id";
  $result = mysqli_query($conn, $query);

  if ($result) {
    // Success: alert and redirect
    echo "
            <script>
                alert('Position deleted successfully.');
                window.location.href = 'position.php'; // Change to your actual page
            </script>
        ";
  } else {
    // Error: alert and redirect
    echo "
            <script>
                alert('Failed to delete the position. Please try again.');
                window.location.href = 'position.php'; // Change to your actual page
            </script>
        ";
  }
} else {
  // Invalid ID: alert and redirect
  echo "
        <script>
            alert('Invalid position ID.');
            window.location.href = 'position.php'; // Change to your actual page
        </script>
    ";
}
