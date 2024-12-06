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

if (isset($_POST['cash_advance_id'])) {
  $cash_advance_id = $_POST['cash_advance_id'];
  $sql = "SELECT * FROM cash_advance WHERE cash_advance_id = '$cash_advance_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="modal fade" id="updateCashAdvance" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-l" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update Cash Advance Employee ID: <?php echo $row['cash_advance_id']; ?></h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data" action="decline_cash_advance_process.php">
                <input type="hidden" name="cash_advance_id" value="<?php echo $row['cash_advance_id']; ?>">

                <h1>Cancel Cash Advance?</h1>
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