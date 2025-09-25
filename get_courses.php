<?php
header('Content-Type: application/json');

// Connect to database
$conn = new mysqli("localhost", "root", "", "skillpro");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Fetch courses with their IDs
$result = $conn->query("SELECT id, title, category, duration, location, description FROM courses ORDER BY title ASC");

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

$conn->close();
echo json_encode($courses);
?>
