<?php
header('Content-Type: application/json');

$host = "localhost";
$username = "root";
$password = "";
$database = "admin_login";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$employee_no = $_GET['employee_no'] ?? '';
$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('n');

if (!$employee_no) {
    die(json_encode(['error' => 'Employee number is required']));
}

$query = "
    SELECT 
        DATE(date) as date,
        MIN(CASE WHEN HOUR(time_in) < 12 THEN TIME(time_in) END) as morning_in,
        MAX(CASE WHEN HOUR(time_out) >= 12 AND HOUR(time_out) < 17 THEN TIME(time_out) END) as afternoon_out,
        MAX(CASE WHEN HOUR(time_out) >= 17 THEN TIME(time_out) END) as overtime_out
    FROM attendance_report
    WHERE employee_no = ? 
    AND YEAR(date) = ? 
    AND MONTH(date) = ?
    GROUP BY DATE(date)
    ORDER BY date
";

$stmt = $conn->prepare($query);
$stmt->bind_param("sii", $employee_no, $year, $month);
$stmt->execute();
$result = $stmt->get_result();

$records = [];
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

echo json_encode(['records' => $records]);

$stmt->close();
$conn->close();
?>