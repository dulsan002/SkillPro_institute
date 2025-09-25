<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "skillpro");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode(['error' => 'Invalid course ID']);
    exit();
}

// Fetch full course info
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$course = $result->fetch_assoc();
if ($course) {
    echo json_encode($course);
} else {
    echo json_encode(['error' => 'Course not found']);
}

$stmt->close();
$conn->close();
?>
