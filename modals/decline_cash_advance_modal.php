<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333;
    /* Darker label color */
    font-weight: bolder;
  }
</style>

<?php
include './../connection/connections.php';

if (isset($_POST['employee_no'])) {
  $employee_no = $_POST['employee_no'];
  $sql = "SELECT * FROM cash_advance WHERE employee_no = '$employee_no'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="modal fade" id="updateCashAdvance" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-l" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update Cash Advance Employee ID: <?php echo $row['employee_no']; ?></h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data" action="decline_cash_advance_process.php">
                <input type="hidden" name="employee_no" value="<?php echo $row['employee_no']; ?>">
                <h1>Decline Cash Advance?</h1>
                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="edit_cash_advance" value="1">

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="saveCategoryButton">Yes</button>
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

<script>
  // Close modal programmatically
  document.getElementById('closeModalButton').addEventListener('click', function () {
    var myModal = new bootstrap.Modal(document.getElementById('updateCashAdvance'));
    myModal.hide(); // Hide the modal
  });
</script>