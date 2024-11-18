<?php
include '../connection/connections.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_employee') {
    $employee_no = $_POST['employee_no'];
    $fields = ['last_name', 'first_name', 'middle_name', 'birthdate', 'gender', 'nationality', 'address', 'contact', 'email', 'emergency_contact_name', 'emergency_contact_number'];

    $updates = [];
    $types = '';
    $values = [];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $updates[] = "$field = ?";
            $types .= 's'; // Assuming all fields are strings. Adjust if needed.
            $values[] = $_POST[$field];
        }
    }

    if (!empty($updates)) {
        $sql = "UPDATE adding_employee SET " . implode(', ', $updates) . " WHERE employee_no = ?";
        $types .= 's'; // For the employee_no in WHERE clause
        $values[] = $employee_no;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'No fields to update']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}

$conn->close();
?>