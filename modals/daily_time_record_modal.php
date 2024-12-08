<!-- Add this modal for the Daily Time Record just before the closing </body> tag -->
<div id="timeRecordModal" class="modal">
  <div class="modal-content" style="max-width: 800px;">
    <span class="close" onclick="closeTimeRecordModal()">&times;</span>
    <div id="timeRecordContent">
      <div class="daily-time-record" style="padding: 20px;">
        <div style="text-align: center; margin-bottom: 20px;">
          <img src="../image/logobg.png" alt="Company Logo" style="width: 60px; height: auto;">
          <h2 style="margin: 10px 0;">Daily Time Record</h2>
        </div>

        <div class="employee-details" style="margin-bottom: 20px;">
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
            <div>
              <label>Last Name:</label>
              <div class="info-field"><?php echo htmlspecialchars($employee['last_name']); ?></div>
            </div>
            <div>
              <label>First Name:</label>
              <div class="info-field"><?php echo htmlspecialchars($employee['first_name']); ?></div>
            </div>
            <div>
              <label>MI:</label>
              <div class="info-field">
                <?php echo htmlspecialchars($employee['middle_name'][0] ?? ''); ?>
              </div>
            </div>
          </div>
          <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 10px;">
            <div>
              <label>Department:</label>
              <div class="info-field"><?php echo htmlspecialchars($employee['department_name']); ?></div>
            </div>
            <div>
              <label>Position:</label>
              <div class="info-field"><?php echo htmlspecialchars($employee['rate_position']); ?>
              </div>
            </div>
          </div>
        </div>

        <table class="time-record-table" style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr>
              <th>Date</th>
              <th>MORNING<br>TIME IN</th>
              <th>AFTERNOON<br>TIME OUT</th>
              <th>OVERTIME<br>TIME OUT</th>
            </tr>
          </thead>
          <tbody id="timeRecordBody">
            <!-- Will be populated dynamically -->
          </tbody>
        </table>

        <div style="margin-top: 30px; text-align: right;">
          <div>EMPLOYEE SIGNATURE: _________________________</div>
        </div>
      </div>
    </div>
    <div class="modal-buttons" style="margin-top: 20px;">
      <button onclick="printTimeRecordRun()">Print</button>
      <button onclick="downloadTimeRecordPDF()">Download PDF</button>
      <button onclick="closeTimeRecordModal()">Close</button>
    </div>
  </div>
</div>

<script>
  function printTimeRecordRun() {
    // Get the modal content
    const modalContent = document.querySelector("#timeRecordContent").innerHTML;

    // Create a new window for printing
    const printWindow = window.open("", "_blank", "width=800,height=600");

    // Write the modal content into the new window
    printWindow.document.open();
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print Daily Time Record</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                .time-record-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .time-record-table th, .time-record-table td {
                    border: 1px solid black;
                    padding: 8px;
                    text-align: center;
                }
                .info-field {
                    border: 1px solid #ccc;
                    padding: 5px;
                    margin-top: 5px;
                }
                .modal-content h2 {
                    margin: 10px 0;
                }
            </style>
        </head>
        <body>
            ${modalContent}
        </body>
        </html>
    `);
    printWindow.document.close();

    // Trigger the print dialog
    printWindow.print();

    // Close the print window after printing
    printWindow.onafterprint = () => {
      printWindow.close();
    };
  }
</script>