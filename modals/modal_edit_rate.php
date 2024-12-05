<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Rates Modal</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <style>
    /* Custom CSS for label color */
    .modal-body label {
      color: #333;
      /* Darker label color */
      font-weight: bolder;
    }
  </style>
</head>

<body>

  <?php
  include './../connection/connections.php';

  if (isset($_POST['rate_id'])) {
    $rate_id = $_POST['rate_id'];
    $sql = "SELECT * FROM under_position WHERE rate_id = '$rate_id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="modal fade" id="editRates" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-l" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Update Position ID: <?php echo $row['rate_id']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <form method="post" action="update_rates_process.php" enctype="multipart/form-data">
                  <input type="hidden" class="form-control" id="rate_id" name="rate_id" value="<?php echo $rate_id; ?>"
                    required>

                  <!-- Position -->
                  <div class="mb-3">
                    <label for="rate_position" class="form-label">Position</label>
                    <input type="text" class="form-control" id="rate_position" name="rate_position"
                      placeholder="Enter Position Name" value="<?php echo $row['rate_position']; ?>" required>
                  </div>
                  <!-- Rate Per Hour -->
                  <div class="mb-3">
                    <label for="rate_per_hour" class="form-label">Rate Per Hour</label>
                    <input type="number" class="form-control" id="rate_per_hour" name="rate_per_hour"
                      placeholder="Enter Rate per hour" value="<?php echo $row['rate_per_hour']; ?>" required>
                  </div>
                  <!-- Rate Per Day -->
                  <div class="mb-3">
                    <label for="rate_per_day" class="form-label">Rate Per Day</label>
                    <input type="number" class="form-control" id="rate_per_day" name="rate_per_day"
                      placeholder="Enter Rate per day" value="<?php echo $row['rate_per_day']; ?>" required>
                  </div>

                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="saveCategoryButton">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <input type="hidden" name="update_rates" value="1">

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

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script to trigger the modal -->
  <script>
    $(document).ready(function () {
      // Automatically show the modal when the page loads if it's present
      $('#editRates').modal('show');

      // Ensure modal cleanup on close
      $('#editRates').on('hidden.bs.modal', function () {
        // Remove the backdrop if it remains
        $('.modal-backdrop').remove();
      });
    });
  </script>

</body>

</html>