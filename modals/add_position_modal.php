<?php
// Ensure session is started

// Check if the session contains employee data
// if (!isset($_SESSION['employee']) || !isset($_SESSION['employee']['employee_no'])) {
//   die("User is not logged in.");
// }

// Include database connection
include '../connection/connections.php';

// // Get the employee_no from the session
// $employee_no = $_SESSION['employee']['employee_no'];

// // Sanitize the input to prevent SQL injection
// $employee_no = mysqli_real_escape_string($conn, $employee_no);

// // Fetch employee data from the database
// $sql = "SELECT * FROM adding_employee WHERE employee_no = '$employee_no'";
// $resultCategory = mysqli_query($conn, $sql);

// if (!$resultCategory) {
//   die("Query failed: " . mysqli_error($conn));
// }

// // Fetch the employee data as an associative array
// $employeeData = mysqli_fetch_assoc($resultCategory);

// if (!$employeeData) {
//   die("Employee not found.");
// }
?>

<!-- Modal (Bootstrap) -->
<div class="modal fade" id="positionModal" tabindex="-1" aria-labelledby="positionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="positionModalLabel">Add Position</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="position_process.php">

          <!-- Requested Amount -->
          <div class="mb-3">
            <label for="rate_position" class="form-label">Position</label>
            <input type="text" class="form-control" id="rate_position" name="rate_position"
              placeholder="Enter Position Name" required>
          </div>

          <input type="hidden" name="add_position" value="1">

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit Position</button>

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>


    </div>
  </div>
</div>