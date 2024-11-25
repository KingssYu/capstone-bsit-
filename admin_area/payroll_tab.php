<!-- Payroll System Section -->
<div id="payrollSystem" class="info-section-content">
  <form action="payroll_process.php" method="POST" id="payrollForm">

    <div class="payroll-container">

      <!-- Earnings Section -->
      <div class="payroll-box earnings">
        <input type="hidden" id="employee_no" name="employee_no" readonly
          value="<?php echo htmlspecialchars($employee['employee_no']); ?>">
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

        <label>Total No. Hours:</label>
        <input type="text" id="totalHours" name="totalHours" readonly
          value="<?php echo number_format($attendance_data['summary']['total_hours'], 2); ?>">

        <label>Overtime Total No. Hours:</label>
        <input type="text" id="totalOverTime" name="totalOverTime" readonly
          value="<?php echo number_format($attendance_data['summary']['total_overtime'], 2); ?>">

        <label>Gross Pay:</label>
        <input type="text" id="grossPay" name="grossPay" readonly value="<?php
                                                                          $totalWorkHours = $attendance_data['summary']['total_hours'] + $attendance_data['summary']['total_overtime'];
                                                                          echo number_format($totalWorkHours * $employee['rate_per_hour'], 2);
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
        // Function to calculate and update values
        function updateDeductions() {
          const sssInput = document.getElementById('sss');
          const philhealthInput = document.getElementById('philhealth');
          const pagibigInput = document.getElementById('pagibig');
          const totalInput = document.getElementById('totalDeductions');

          const today = new Date();
          const day = today.getDate(); // Get the current day of the month
          const isPayday = day === 15 || day === new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate(); // 15th or end of month

          // Update deductions
          if (isPayday) {
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


      <!-- Loan/Advances Section -->
      <div class="payroll-box loans">
        <h4>LOAN/ADVANCES</h4>
        <?php
        $status = $employee['status'];  // Assuming you have a status field in your employee data
        $months = $employee['months'];

        $remaining_balance = $employee['remaining_balance'];  // Round the remaining balance
        $requestedAmount = $status !== 'Approved' ? 0 : $employee['requested_amount'];

        $monthlyPayment = $status !== 'Approved' ? 0 : $employee['monthly_payment'];  // Round the monthly payment

        // Check if $months is greater than 0 to avoid division by zero
        $existing_balance = ($months > 0) ? $requestedAmount / $months : 0;

        $updatedRequestedAmount = $requestedAmount - $monthlyPayment; // Calculate the remaining amount after deduction
        ?>

        <label>Cash Advance:</label>
        <input type="number" id="cashAdvance" name="cashAdvance" placeholder="Enter amount"
          value="<?php echo $requestedAmount; ?>" onchange="calculateBalance()" readonly>

        <label>Balance:</label>
        <input type="text" id="remaining_balance" name="remaining_balance"
          value="<?php echo number_format($existing_balance, 2); ?>" readonly>

        <label>Cash Advance Pay:</label>
        <input type="number" id="cashAdvancePay" name="cashAdvancePay" placeholder="Enter amount"
          value="<?php echo $monthlyPayment; ?>" onchange="calculateBalance()" readonly>
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

  function calculateNetPay() {
    // const grossPay = parseFloat(document.getElementById('grossPay').value);
    const totalOvertimeHours = parseFloat(document.getElementById('totalDeductions').value) || 0;

    const grossPay = parseFloat(document.getElementById('grossPay').value.replace(/,/g, ''));


    const totalDeductions = parseFloat(document.getElementById('totalDeductions').value) || 0;
    const cashAdvancePay = parseFloat(document.getElementById('cashAdvancePay').value) || 0;
    const netPay = grossPay - totalDeductions - cashAdvancePay;
    console.log(grossPay)
    document.getElementById('netPay').value = netPay.toFixed(2) + ' Pesos';
  }


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
                    <p><strong>Department:</strong> <?php echo $employee['department']; ?></p>
                </div>
              </div>
            <hr>

            <div class="payslip-details">
                <div class="earnings">
                    <h2>Earnings</h2>
                    <p><strong>Rate per Hours:</strong> ${document.getElementById('ratePerHour').value}</p>
                    <p><strong>Basic per Day:</strong> ${document.getElementById('basicPerDay').value}</p>
                    <p><strong>Number of Days:</strong> ${document.getElementById('numberOfDays').value}</p>
                    <p><strong>Total No. of Hours:</strong> <?php echo $attendance_data['summary']['total_hours']; ?></p>
                    <p><strong>Gross Pay:</strong> <?php echo number_format($attendance_data['summary']['total_hours'] * 68.75, 2); ?></p>
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
      callback: function(doc) {
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