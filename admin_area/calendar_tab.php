<div id="attendanceRecord" class="info-section-content">
  <h2>Attendance Record</h2>
  <div class="calendar-navigation">
    <a href="#" class="nav-button" id="prevMonth">&lt;&lt;&lt; Prev</a>
    <span class="current-date" id="currentDate">Current Date:
      <?php echo date('F Y', mktime(0, 0, 0, $current_month, 1, $current_year)); ?></span>
    <a href="#" class="nav-button" id="nextMonth">Next &gt;&gt;&gt;</a>
  </div>

  <div class="attendance-summary">
    <div>
      <span>Total Attendance</span>
      <h2 id="totalAttendance"><?php echo $attendance_data['summary']['total_present']; ?></h2>
    </div>
    <div>
      <span>Total Absent</span>
      <h2 id="totalAbsent"><?php echo $attendance_data['summary']['total_absent']; ?></h2>
    </div>
    <div>
      <span>Total Hours</span>
      <h2 id="totalHours"><?php echo $attendance_data['summary']['total_hours']; ?> hours</h2>
    </div>
  </div>

  <div class="calendar-container">
    <div class="calendar" id="calendarDays"></div>
  </div>

  <!-- Print Button -->
  <button class="print-button" onclick="printTimeRecord()">Print</button>

</div>

<script>


</script>