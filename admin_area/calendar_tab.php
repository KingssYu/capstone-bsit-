<?php
// Assuming the employee_no is passed as a GET parameter
$employee_no = $_GET['employee_no'];

// Create the query to get attendance data for the specific employee
$queryCalendar = "SELECT employee_no, time_in, time_out FROM attendance_report WHERE employee_no = '$employee_no' AND is_paid = 0";

// Execute the query
$result = mysqli_query($conn, $queryCalendar);

// Initialize total hours and total overtime hours
$total_hours = 0;
$total_hours_ot = 0; // For overtime

// Define the lunch break duration in seconds (1 hour)
$lunch_break_duration = 3600;

while ($row = mysqli_fetch_assoc($result)) {
  $time_in = $row['time_in'];
  $time_out = $row['time_out'];

  // Convert time_in and time_out to timestamp for easy comparison
  $time_in_obj = strtotime($time_in);
  $time_out_obj = strtotime($time_out);

  // Adjust time_in based on the condition
  if (date('H:i', $time_in_obj) < '08:30') {
    // If time_in is before 8:30 AM, set it to 8:00 AM
    $adjusted_time_in = strtotime(date('Y-m-d', $time_in_obj) . ' 08:00:00');
  } else {
    // If time_in is after 8:30 AM, set it to 9:00 AM
    $adjusted_time_in = strtotime(date('Y-m-d', $time_in_obj) . ' 09:00:00');
  }

  // Regular hours: Calculate hours only between 8:00 AM and 5:00 PM
  $regular_hours = 0;
  $ot_hours = 0;

  // Cap the time_out at 5:00 PM for regular hours if the employee clocks out between 5:00 PM and 6:59 PM
  $regular_end_time = strtotime(date('Y-m-d', $time_out_obj) . ' 17:00:00'); // 5:00 PM
  if ($time_out_obj >= $regular_end_time && $time_out_obj < strtotime(date('Y-m-d', $time_out_obj) . ' 19:00:00')) {
    $time_out_obj = $regular_end_time; // Treat it as 5:00 PM
  }

  // Calculate regular hours (between 8:00 AM and 5:00 PM)
  if ($time_in_obj < $regular_end_time) {
    $worked_regular_hours = ($time_out_obj - $adjusted_time_in) / 3600; // Convert seconds to hours
    $worked_regular_hours -= 1; // Subtract 1 hour for lunch break
    $worked_regular_hours = max(0, $worked_regular_hours); // Ensure it’s not negative

    // Add the regular hours to the total
    $regular_hours = $worked_regular_hours;
  }

  // OT Calculation: Overtime starts at 7:00 PM
  $ot_start_time = strtotime(date('Y-m-d', $time_out_obj) . ' 19:00:00'); // OT starts at 7:00 PM
  if ($time_out_obj > $ot_start_time) {
    // Calculate overtime hours
    $ot_hours = ($time_out_obj - $ot_start_time) / 3600; // Overtime hours
  }

  // Check if the employee clocks out between 5:00 PM and 6:59 PM and adjust the overtime
  if ($time_out_obj >= strtotime('19:00:00')) {
    // Assign 1 hour of overtime for the time between 5:00 PM and 6:59 PM
    $ot_hours += 1;
  }

  // Check if the employee clocks out at 7:00 AM onwards and adjust regular hours
  if ($time_out_obj >= strtotime('19:00:00') && $time_out_obj < strtotime('23:59:59')) {
    // Subtract 2 hours from regular time to avoid computing between 5 PM and 6:59 PM
    $regular_hours -= 1;
  }

  // Add regular hours to total regular hours (total_hours should only contain regular hours)
  $total_hours += $regular_hours;

  // Add overtime hours to total OT hours
  $total_hours_ot += $ot_hours;
}
?>


<div id="attendanceRecord" class="info-section-content">
  <h2>Attendance Record</h2>
  <div class="calendar-navigation">
    <a href="#" class="nav-button" id="prevMonth">&lt;&lt;&lt; Prev</a>
    <span class="current-date" id="currentDate">Curresnt Date:
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
      <h2 id="totalHours"><?php echo number_format($total_hours, 2); ?> hours</h2>
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