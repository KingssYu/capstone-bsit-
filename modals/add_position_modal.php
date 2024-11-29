<?php
// Include database connection
include '../connection/connections.php';

$sql = "SELECT * FROM department";
$result = mysqli_query($conn, $sql);

$department_names = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $department_names[] = $row;
  }
}
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

          <div class="mb-3">
            <label for="department_id" class="form-label">Select Department</label>
            <select class="form-control" id="department_id" name="department_id" required style="appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3E%3Cpolyline points=%276 9 12 15 18 9%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 10px center;">
              <option value="" disabled selected>Select Department</option>
              <?php foreach ($department_names as $supplier_rows) : ?>
                <option value="<?php echo $supplier_rows['department_id']; ?>">
                  <?php echo $supplier_rows['department_name']; ?>
                </option>
              <?php endforeach; ?>
            </select>
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