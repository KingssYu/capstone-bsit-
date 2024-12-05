<?php
$employee_no = $_GET['employee_no'];

$queryPayroll = "SELECT employee_no, time_in, time_out FROM attendance_report WHERE employee_no = '$employee_no' AND is_paid = 0";

$result = mysqli_query($conn, $queryPayroll);

$total_hours = 0;
$total_hours_ot = 0; // For overtime

$lunch_break_duration = 3600;

while ($row = mysqli_fetch_assoc($result)) {
  $time_in = $row['time_in'];
  $time_out = $row['time_out'];

  $time_in_obj = strtotime($time_in);
  $time_out_obj = strtotime($time_out);

  if (date('H:i', $time_in_obj) < '08:30') {
    $adjusted_time_in = strtotime(date('Y-m-d', $time_in_obj) . ' 08:00:00');
  } else {
    $adjusted_time_in = strtotime(date('Y-m-d', $time_in_obj) . ' 09:00:00');
  }

  $regular_hours = 0;
  $ot_hours = 0;

  $regular_end_time = strtotime(date('Y-m-d', $time_out_obj) . ' 17:00:00'); // 5:00 PM
  if ($time_out_obj >= $regular_end_time && $time_out_obj < strtotime(date('Y-m-d', $time_out_obj) . ' 19:00:00')) {
    $time_out_obj = $regular_end_time; // Treat it as 5:00 PM
  }

  if ($time_in_obj < $regular_end_time) {
    $worked_regular_hours = ($time_out_obj - $adjusted_time_in) / 3600; // Convert seconds to hours
    $worked_regular_hours -= 1; // Subtract 1 hour for lunch break
    $worked_regular_hours = max(0, $worked_regular_hours); // Ensure it’s not negative

    $regular_hours = $worked_regular_hours;
  }

  $ot_start_time = strtotime(date('Y-m-d', $time_out_obj) . ' 19:00:00'); // OT starts at 7:00 PM
  if ($time_out_obj > $ot_start_time) {
    $ot_hours = ($time_out_obj - $ot_start_time) / 3600; // Overtime hours
  }

  if ($time_out_obj >= strtotime('19:00:00')) {
    $ot_hours += 1;
  }

  if ($time_out_obj >= strtotime('19:00:00') && $time_out_obj < strtotime('23:59:59')) {
    $regular_hours -= 1;
  }

  $total_hours += $regular_hours - $ot_hours;

  $total_hours_ot += $ot_hours;
}
?>

<!-- Payroll System Section -->
<div id="payrollSystem" class="info-section-content">
  <form action="payroll_process.php" method="POST" id="payrollForm">

    <div class="payroll-container">

      <!-- Earnings Section -->
      <div class="payroll-box earnings">
        <input type="hidden" id="employee_no" name="employee_no" readonly
          value="<?php echo isset($_GET['employee_no']) ? htmlspecialchars($_GET['employee_no']) : ''; ?>">

        <h4>EARNINGS</h4>
        <label>Rate per Hour/Overtime:</label>
        <input type="text" id="ratePerHour" name="ratePerHour" readonly
          value="<?php echo htmlspecialchars($employee['rate_per_hour']); ?>">

        <label>Basic per Day:</label>
        <input type="text" id="basicPerDay" name="basicPerDay" readonly
          value="<?php echo htmlspecialchars($employee['rate_per_day']); ?>">

        <label>No. of Days:</label>
        <input type="text" id="numberOfDays" name="numberOfDays" readonly
          value="<?php echo $attendance_data['summary']['total_present'] + $attendance_data['summary']['total_late']; ?>">

        <label>Total No. Regular Hours:</label>
        <input type="text" id="totalHours" name="totalHours" readonly
          value="<?php echo number_format($total_hours, 2); ?>">

        <label>Overtime Total No. Hours:</label>
        <input type="text" id="totalOverTime" name="totalOverTime" readonly
          value="<?php echo number_format($total_hours_ot, 2); ?>">

        <label>Gross Pay:</label>
        <input type="text" id="grossPay" name="grossPay" readonly value="<?php
        // Calculate the gross pay correctly
        $totalWorkHours = number_format($total_hours, 2);
        $grossPayCalculation = number_format($totalWorkHours, 2) + number_format($total_hours_ot, 2);

        $grossPay = $grossPayCalculation * $employee['rate_per_hour'];
        // Ensure precision with rounding or formatting
        echo number_format($grossPay, 2);
        ?>">


      </div>


      <!-- Other Deductions/Government Section -->
      <div class="payroll-box deductions">
        <h4>OTHER DEDUCTION/GOVERNMENT</h4>
        <label>SSS:</label>
        <input type="number" id="sss" name="sss" value="500" placeholder="Enter amount" readonly>

        <label>PhilHealth:</label>
        <input type="number" id="philhealth" name="philhealth" value="200" placeholder="Enter amount" readonly>

        <label>PAGIBIG:</label>
        <input type="number" id="pagibig" name="pagibig" value="250" placeholder="Enter amount" readonly>

        <label>Total:</label>
        <input type="text" id="totalDeductions" name="totalDeductions" readonly>
      </div>

      <script>
        function updateDeductions() {
          const sssInput = document.getElementById('sss');
          const philhealthInput = document.getElementById('philhealth');
          const pagibigInput = document.getElementById('pagibig');
          const totalInput = document.getElementById('totalDeductions');

          const today = new Date();
          const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate(); // Get the last day of the current month
          const isEndOfMonth = today.getDate() === lastDayOfMonth;

          // Update deductions only on the last day of the month
          if (isEndOfMonth) {
            sssInput.value = 500;
            philhealthInput.value = 200;
            pagibigInput.value = 250;
          } else {
            sssInput.value = 0;
            philhealthInput.value = 0;
            pagibigInput.value = 0;
          }

          // Calculate and set total deductions
          const total = parseFloat(sssInput.value) + parseFloat(philhealthInput.value) + parseFloat(pagibigInput.value);
          totalInput.value = total;
        }


        // Call the function on page load
        updateDeductions();
      </script>



      <?php
      $emp = isset($_GET['employee_no']) ? htmlspecialchars($_GET['employee_no']) : '';

      // Query to fetch the approved cash advance
      $sql = "SELECT * FROM cash_advance WHERE employee_no = '$emp' AND `status` = 'Approved' LIMIT 1";
      $result = mysqli_query($conn, $sql);

      if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $requested_amount = $row['requested_amount'];
        $remaining_balance = floor($row['remaining_balance']);
      } else {
        $requested_amount = 0;
        $remaining_balance = 0;
      }
      ?>

      <!-- Loan/Advances Section -->
      <div class="payroll-box loans">
        <h4>LOAN/ADVANCES</h4>

        <label>Cash Advance:</label>
        <input type="number" id="cashAdvance" name="cashAdvance" placeholder="Enter amount"
          value="<?php echo $requested_amount; ?>" readonly>

        <label>Remaining Balance:</label>
        <input type="text" id="remaining_balance" name="remaining_balance"
          value="<?php echo number_format($remaining_balance, 2); ?>" readonly>


        <label>Payment:</label>
        <input type="number" id="cashAdvancePay" name="cashAdvancePay" placeholder="Enter amount"
          value="<?php echo $row['monthly_payment'] ?>" onchange="calculateBalance()">
      </div>



    </div>

    <!-- Net Pay Section -->
    <div class="payroll-box net-pay">
      <h4>NET PAY</h4>
      <input type="text" id="netPay" name="netPay" readonly>
    </div>

    <!-- Hidden Payment Date -->
    <input type="hidden" id="payment_date" name="payment_date" value="<?php echo date('F j, Y'); ?>">

    <!-- Proceed Button -->
    <input type="hidden" name="submit_payroll" value="1">

    <button type="button" class="print-btn" onclick="validateAndProceed()">PROCEED</button>
  </form>


  <script>
    function submitPayroll() {
      // Select the specific form by its id (e.g., 'payrollForm')
      var form = document.getElementById('payrollForm'); // Make sure the form has this id

      if (form) {
        form.submit(); // This will submit the selected form to payroll_process.php
      }
    }
    // Function to validate the dates and proceed
    function validateAndProceed() {
      showPayslipModal();
    }
  </script>
</div>


<!-- Payslip Modal -->
<div id="payslipModal" class="modal" style="display: none;">
  <div class="modal-content">
    <div id="payslipContent">
      <!-- Payslip content will be dynamically inserted here -->
    </div>
    <div class="modal-buttons">
      <button onclick="submitPayroll()">Submit</button>

      <button onclick="printPayslip()">Print</button>
      <button onclick="downloadPDF()">Download PDF</button>
      <button onclick="closePayslipModal()">Close</button>
    </div>
  </div>
</div>

<script>
  function calculateTotalDeductions() {
    const sss = parseFloat(document.getElementById('sss').value) || 0;
    const philhealth = parseFloat(document.getElementById('philhealth').value) || 0;
    const pagibig = parseFloat(document.getElementById('pagibig').value) || 0;
    const total = sss + philhealth + pagibig;
    document.getElementById('totalDeductions').value = total.toFixed(2);
    calculateNetPay();
  }

  // Function to calculate the net pay based on gross pay, deductions, and cash advance pay
  function calculateNetPay() {
    // Get the gross pay and remove any commas
    const grossPay = parseFloat(document.getElementById('grossPay').value.replace(/,/g, '')) || 0;

    // Get total deductions and cash advance payment
    const totalDeductions = parseFloat(document.getElementById('totalDeductions').value) || 0;
    const cashAdvancePay = parseFloat(document.getElementById('cashAdvancePay').value) || 0;

    // Calculate net pay
    const netPay = grossPay - totalDeductions - cashAdvancePay;

    // Store the calculated net pay in the dataset for future reference
    const netPayField = document.getElementById('netPay');
    netPayField.dataset.originalValue = netPay.toFixed(2);

    // Update the netPay field
    netPayField.value = netPay.toFixed(2) + ' Pesos';
  }

  // Function to update net pay when cash advance pay changes
  function calculateBalance() {
    // Get the cash advance payment
    const cashAdvancePay = parseFloat(document.getElementById('cashAdvancePay').value) || 0;

    // Retrieve the original gross pay and deductions
    const grossPay = parseFloat(document.getElementById('grossPay').value.replace(/,/g, '')) || 0;
    const totalDeductions = parseFloat(document.getElementById('totalDeductions').value) || 0;

    // Calculate the updated net pay after cash advance pay
    const updatedNetPay = grossPay - totalDeductions - cashAdvancePay;

    // Update the netPay field
    document.getElementById('netPay').value = updatedNetPay.toFixed(2) + ' Pesos';
  }



  // Store the original netPay when the page loads
  window.onload = function () {
    const netPayInput = document.getElementById('netPay');
    netPayInput.dataset.originalValue = netPayInput.value; // Store original value in a custom data attribute
  };


  function showPayslipModal() {
    const modal = document.getElementById('payslipModal');
    const content = document.getElementById('payslipContent');

    // Generate payslip content
    content.innerHTML = `
        <div class="payslip">
        
            <div class="payslip-header">
                <img src="../image/logobg.png" alt="Company Logo" class="logo">
                <h1>PAYSLIP</h1>
                <div class="payslip-info">
                    <p>MAPOL</p>
                </div>
            </div>
              <div class="payslip-details">

                <div class="employee-info">
                    <p><strong>Name:</strong> <?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></p>
                    <p><strong>Employee ID:</strong> <?php echo $employee['employee_no']; ?></p>
                </div>
                <div class="employee-info">
                    <p><strong>Position:</strong> <?php echo $employee['rate_position']; ?></p>
                    <p><strong>Department:</strong> <?php echo $employee['department_name']; ?></p>
                </div>
              </div>
            <hr>

            <div class="payslip-details">
                <div class="earnings">
                    <h2>Earnings</h2>
                    <p><strong>Rate per Hours:</strong> ${document.getElementById('ratePerHour').value}</p>
                    <p><strong>Basic per Day:</strong> ${document.getElementById('basicPerDay').value}</p>
                    <p><strong>Number of Days:</strong> ${document.getElementById('numberOfDays').value}</p>
                    <p><strong>Total No. of Hours:</strong> <?php echo number_format($total_hours, 2) ?></p>
                    <p><strong>Gross Pay:</strong> <?php
                    $totalWorkHours = number_format($total_hours, 2);
                    echo number_format($totalWorkHours * $employee['rate_per_hour'], 2);
                    ?></p>
                </div>
                <div class="deductions">
                    <h2>Government Deduction</h2>
                    <p><strong>SSS:</strong> ${document.getElementById('sss').value}</p>
                    <p><strong>Philhealth:</strong> ${document.getElementById('philhealth').value}</p>
                    <p><strong>Pag-Ibig:</strong> ${document.getElementById('pagibig').value}</p>
                    <p><strong>Total Deductions:</strong> ${document.getElementById('totalDeductions').value}</p>
                    <h2>Cash Advance</h2>
                    <p><strong>CA Balance:</strong> ${document.getElementById('cashAdvance').value}</p>
                    <p><strong>CA Pay:</strong> ${document.getElementById('cashAdvancePay').value}</p>
                </div>
            </div>
            <div class="payslip-total">
                <p><strong>TOTAL</strong></p>
                <p>${document.getElementById('grossPay').value}</p>
                <p>${document.getElementById('totalDeductions').value}</p>
            </div>
            <div class="payslip-netpay">
                <h2>NETPAY</h2>
                <p>${document.getElementById('netPay').value}</p>
            </div>
            <div class="payslip-footer">
                <p><strong>Payment Date:</strong> <?php echo date('F j, Y'); ?></p>

                <p><strong>Signature: _________________________</strong></p>
            </div>


        </div>

`;


    modal.style.display = 'block';
  }

  function closePayslipModal() {
    document.getElementById('payslipModal').style.display = 'none';
  }

  function printPayslip() {
    window.print();
  }

  function downloadPDF() {
    const {
      jsPDF
    } = window.jspdf;
    const doc = new jsPDF();
    const content = document.getElementById('payslipContent');

    doc.html(content, {
      callback: function (doc) {
        doc.save('payslip.pdf');
      },
      x: 10,
      y: 10,
      width: 190,
      windowWidth: 650
    });
  }

  // Initial calculations
  calculateTotalDeductions();
  calculateBalance();
  calculateNetPay();
</script>