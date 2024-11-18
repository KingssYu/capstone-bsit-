<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'] ?? '';
    $camera_index = $_POST['camera_index'] ?? '0';

    if (empty($employee_id)) {
        echo json_encode(['success' => false, 'error' => 'Employee ID is required']);
        exit;
    }

    $command = "python face_capture.py " . escapeshellarg($employee_id) . " " . escapeshellarg($camera_index);
    $output = shell_exec($command);
    $result = json_decode($output, true);

    if ($result && isset($result['num_samples']) && $result['num_samples'] > 0) {
        include '../connection/connections.php';

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            echo json_encode(['success' => false, 'error' => 'Database connection failed']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE adding_employee SET face_samples = ? WHERE employee_no = ?");
        $stmt->bind_param("is", $result['num_samples'], $employee_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'num_samples' => $result['num_samples']]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update database']);
        }

        $stmt->close();
        $conn->close();
    } else {
        $error = isset($result['error']) ? $result['error'] : 'Unknown error occurred';
        echo json_encode(['success' => false, 'error' => $error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}