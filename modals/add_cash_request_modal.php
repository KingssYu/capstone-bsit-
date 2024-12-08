<?php
// Ensure session is started

// Check if the session contains employee data
if (!isset($_SESSION['employee']) || !isset($_SESSION['employee']['employee_no'])) {
  die("User is not logged in.");
}

// Include database connection
include '../connection/connections.php';

// Get the employee_no from the session
$employee_no = $_SESSION['employee']['employee_no'];

// Sanitize the input to prevent SQL injection
$employee_no = mysqli_real_escape_string($conn, $employee_no);

// Fetch employee data from the database
$sql = "SELECT * FROM adding_employee WHERE employee_no = '$employee_no'";
$resultCategory = mysqli_query($conn, $sql);

if (!$resultCategory) {
  die("Query failed: " . mysqli_error($conn));
}

// Fetch the employee data as an associative array
$employeeData = mysqli_fetch_assoc($resultCategory);

if (!$employeeData) {
  die("Employee not found.");
}
?>

<!-- Modal (Bootstrap) -->
<div class="modal fade" id="cashRequestModal" tabindex="-1" aria-labelledby="cashRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cashRequestModalLabel">Cash Advance Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="cash_advance_process.php">
          <!-- Employee ID -->
          <div class="mb-3">
            <input type="hidden" class="form-control" id="id" name="id"
              value="<?php echo htmlspecialchars($employeeData['id']); ?>" readonly>
          </div>
          <div class="mb-3">
            <label for="employee_no" class="form-label">Employee ID</label>
            <input type="text" class="form-control" id="employee_no" name="employee_no"
              value="<?php echo htmlspecialchars($employeeData['employee_no']); ?>" readonly>
          </div>

          <!-- Requested Amount -->
          <div class="mb-3">
            <label for="requested_amount" class="form-label">Requested Amount</label>
            <input type="number" class="form-control" id="requested_amount" name="requested_amount"
              placeholder="Enter amount" required>
          </div>

          <!-- Months -->
          <div class="mb-3">
            <label for="months" class="form-label">Months</label>
            <input type="number" class="form-control" id="months" name="months" placeholder="Enter number of months"
              required>
          </div>

          <!-- Computed Monthly Payment -->
          <div class="mb-3">
            <label for="monthly_payment" class="form-label">Monthly Payment</label>
            <input type="text" class="form-control" id="monthly_payment" name="monthly_payment"
              placeholder="Computation here" readonly>
          </div>

          <input type="hidden" name="submit_cash_advance" value="1">

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit Request</button>

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>


    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const amountInput = document.getElementById("requested_amount");
    const monthsInput = document.getElementById("months");
    const monthlyPaymentInput = document.getElementById("monthly_payment");

    // Define the maximum values
    const maxAmount = <?php echo json_encode($maximum_cash_advance); ?>;
    const maxMonths = <?php echo json_encode($row['cashloan_maximum_month']); ?>;

    function enforceLimits(input, maxValue) {
      // Prevent entering values greater than the maximum
      if (parseFloat(input.value) > maxValue) {
        input.value = maxValue;
      }
    }

    function calculateMonthlyPayment() {
      const amount = parseFloat(amountInput.value) || 0;
      const months = parseInt(monthsInput.value) || 0;

      // Check if months > 0 to avoid division by zero
      if (months > 0) {
        const monthlyPayment = (amount / months).toFixed(2); // Keep 2 decimal places
        monthlyPaymentInput.value = monthlyPayment;
      } else {
        monthlyPaymentInput.value = ""; // Clear field if months is invalid
      }
    }

    // Add event listeners to enforce limits and calculate payments
    amountInput.addEventListener("input", function() {
      enforceLimits(amountInput, maxAmount);
      calculateMonthlyPayment();
    });

    monthsInput.addEventListener("input", function() {
      enforceLimits(monthsInput, maxMonths);
      calculateMonthlyPayment();
    });
  });
</script>