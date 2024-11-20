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
              <div class="info-field"><?php echo htmlspecialchars($employee['department']); ?></div>
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
      <button onclick="printTimeRecord()">Print</button>
      <button onclick="downloadTimeRecordPDF()">Download PDF</button>
      <button onclick="closeTimeRecordModal()">Close</button>
    </div>
  </div>
</div>