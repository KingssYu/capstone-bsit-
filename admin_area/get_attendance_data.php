<?php
include '../connection/connections.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get attendance data for a specific date
function getAttendanceData($date)
{
    global $conn;
    $sql = "SELECT * FROM attendance_reports WHERE DATE(date) = ? ORDER BY employee_name";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Handle AJAX request for attendance data
if (isset($_POST['action']) && $_POST['action'] == 'getAttendance') {
    $date = $_POST['date'];
    $attendanceData = getAttendanceData($date);
    echo json_encode($attendanceData);
    exit;
}
?>