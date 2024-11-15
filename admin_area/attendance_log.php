<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function logError($message) {
    $logFile = 'attendance_error.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

if ($action === 'start_camera') {
    try {
        $pythonScript = realpath(__DIR__ . '/face_recognition_attendance.py');
        $cameraType = $input['cameraType'] ?? 'web';
        $attendanceType = $input['attendanceType'] ?? 'in';
        $command = "python \"$pythonScript\" \"$cameraType\" \"$attendanceType\" 2>&1";
        $output = [];
        $returnVar = 0;
        
        logError("Executing command: $command");
        
        exec($command, $output, $returnVar);
        
        logError("Python script output: " . implode("\n", $output));
        logError("Python script return value: $returnVar");
        
        if ($returnVar !== 0) {
            throw new Exception("Python script execution failed: " . implode("\n", $output));
        }
        
        echo json_encode(['success' => true, 'message' => 'Camera initialized']);
    } catch (Exception $e) {
        logError("Error: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred while initializing the camera: ' . $e->getMessage()
        ]);
    }
} elseif ($action === 'check_result') {
    if (file_exists('attendance_result.json')) {
        $json_content = file_get_contents('attendance_result.json');
        $result = json_decode($json_content, true);
        if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
            logError("Invalid JSON in result file: " . $json_content);
            echo json_encode(['success' => false, 'error' => 'Invalid JSON in result file']);
        } else {
            if (isset($result['employee_no'])) {
                echo json_encode([
                    'success' => true,
                    'message' => $result['message'],
                    'employee_no' => $result['employee_no'],
                    'employee_name' => $result['employee_name']
                ]);
                unlink('attendance_result.json'); // Remove the result file
            } elseif (isset($result['error'])) {
                echo json_encode(['success' => false, 'error' => $result['error']]);
                unlink('attendance_result.json'); // Remove the result file
            } else {
                echo json_encode(['success' => false, 'error' => 'Face recognition in progress']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Face recognition in progress']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
}
?>