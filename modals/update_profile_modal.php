<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333;
    /* Darker label color */
    font-weight: bolder;
  }
</style>

<?php
include '../connection/connections.php';



if (isset($_SESSION['employee'])) {
  $employee_no = $employee_no = $_SESSION['employee']['employee_no'];

  $sql = "SELECT * FROM adding_employee WHERE employee_no = '$employee_no'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $profile_picture = basename($row['profile_picture']);
?>
      <div class="modal fade" id="updateProfile" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-l" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update Employee ID: <?php echo $row['employee_no']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" action="update_employee_process.php" enctype="multipart/form-data">

                <input type="hidden" name="employee_no" value="<?php echo $row['employee_no']; ?>">

                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="profile_picture">Upload Profile Picture:</label>
                    <input type="file" class="form-control" id="profile_picture" name="fileToUpload">
                    <div class="file-info">
                      <?php if (!empty($profile_picture) && file_exists('../uploads/' . $profile_picture)): ?>
                        <p><strong>Current Image:</strong> <img src="../uploads/<?php echo $profile_picture; ?>" alt="Profile Picture" style="max-width: 100px;"></p>
                      <?php else: ?>
                        <p>No image available.</p>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="category_name">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter your First Name" value="<?php echo $row['first_name']; ?>" required>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter your Last Name" value="<?php echo $row['last_name']; ?>">
                  </div>

                  <div class="form-group col-md-12">
                    <label for="contact">Phone:</label>
                    <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter your contact no." value="<?php echo $row['contact']; ?>">
                  </div>

                  <div class="form-group col-md-12">
                    <label for="address">Address:</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter your address" value="<?php echo $row['address']; ?>">
                  </div>

                  <div class="form-group col-md-12">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="<?php echo $row['email']; ?>">
                  </div>

                  <hr>

                  <h2>Emergency Contact</h2>

                  <div class="form-group col-md-12">
                    <label for="emergency_contact_name">Emergency Contact Name:</label>
                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" placeholder="Enter Emergency Contact Name" value="<?php echo $row['emergency_contact_name']; ?>">
                  </div>

                  <div class="form-group col-md-12">
                    <label for="emergency_contact_number">Emergency Contact Phone:</label>
                    <input type="text" class="form-control" id="emergency_contact_number" name="emergency_contact_number" placeholder="Enter Emergency Contact Number" value="<?php echo $row['emergency_contact_number']; ?>">
                  </div>
                </div>

                <input type="hidden" name="update_profile" value="1">

                <!-- Modal Footer with Buttons -->
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="saveCategoryButton">Save</button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>

<?php
    }
  }
}
?>