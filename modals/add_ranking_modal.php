<?php
// Include database connection
include '../connection/connections.php';

$rate_id = isset($_GET['rate_id']) ? $_GET['rate_id'] : null;
?>

<!-- Modal (Bootstrap) -->
<div class="modal fade" id="rateRankingModal" tabindex="-1" aria-labelledby="rateRankingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rateRankingModalLabel">Add Position</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="ranking_process.php">

          <input type="hidden" class="form-control" id="position_id" name="position_id" value=" <?php echo $rate_id ?> "
            required>

          <!-- Requested Amount -->
          <div class="mb-3">
            <label for="rate_position" class="form-label">Position</label>
            <input type="text" class="form-control" id="rate_position" name="rate_position"
              placeholder="Enter Position Name" required>
          </div>
          <div class="mb-3">
            <label for="rate_per_hour" class="form-label">Rate Per Hour</label>
            <input type="number" class="form-control" id="rate_per_hour" name="rate_per_hour"
              placeholder="Enter Rate per hour" required>
          </div>
          <div class="mb-3">
            <label for="rate_per_day" class="form-label">Rate Per Day</label>
            <input type="number" class="form-control" id="rate_per_day" name="rate_per_day"
              placeholder="Enter Rate per day" required>
          </div>
          <input type="hidden" name="add_ranking" value="1">

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit Position</button>

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>


    </div>
  </div>
</div>