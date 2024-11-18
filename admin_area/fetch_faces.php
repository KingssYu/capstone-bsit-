<?php
include '../connection/connections.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch face samples from the database
$query = "SELECT employee_no, face_samples FROM adding_employee";
$result = $conn->query($query);

$faces = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employee_no = $row['employee_no'];
        $face_samples = json_decode($row['face_samples'], true); // Decode JSON into an array
        foreach ($face_samples as $sample) {
            // You might need to extract the descriptor or perform preprocessing here
            $faces[] = [
                'employee_no' => $employee_no,
                'faceDescriptor' => extractFaceDescriptor($sample) // Implement this function
            ];
        }
    }
}

// Return face samples as JSON
echo json_encode($faces);

// Close the connection
$conn->close();

// Implement face descriptor extraction logic based on your storage format
function extractFaceDescriptor($sample)
{
    // Convert the face sample to a descriptor
    // This might involve loading the image and extracting its descriptor
}
?>