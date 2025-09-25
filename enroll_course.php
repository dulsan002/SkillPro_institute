<?php
session_start();

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['course_id'])) {
    $course_id  = intval($_POST['course_id']);
    $student_id = intval($_SESSION['student_id']);

    $conn = new mysqli("localhost", "root", "", "skillpro");
    if ($conn->connect_error) {
        $_SESSION['message'] = "❌ Database connection failed: " . $conn->connect_error;
        header("Location: student.php");
        exit();
    }

    // Prevent duplicate enrollment
    $check = $conn->prepare("SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?");
    $check->bind_param("ii", $student_id, $course_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['message'] = "⚠️ You are already enrolled in this course.";
    } else {
        $stmt = $conn->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("ii", $student_id, $course_id);
            if ($stmt->execute()) {
                $_SESSION['message'] = "✅ Successfully enrolled in the course!";
            } else {
                $_SESSION['message'] = "❌ Enrollment failed: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "❌ Statement preparation failed: " . $conn->error;
        }
    }

    $check->close();
    $conn->close();

    header("Location: student.php");
    exit();
} else {
    $_SESSION['message'] = "❌ Invalid request.";
    header("Location: student.php");
    exit();
}
