<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$database = "skillpro";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Enrollment
if (isset($_POST['add_enroll'])) {
    $student_id = (int)$_POST['student_id'];
    $course_id = (int)$_POST['course_id'];

    $check = $conn->prepare("SELECT id FROM enrollments WHERE student_id=? AND course_id=?");
    $check->bind_param("ii", $student_id, $course_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $student_id, $course_id);
        $stmt->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete Enrollment via POST
if (isset($_POST['delete_enrollment'])) {
    $id = (int)$_POST['delete_enrollment'];
    $stmt = $conn->prepare("DELETE FROM enrollments WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch Enrollments
$result = $conn->query("
    SELECT 
        e.id AS enrollment_id,
        CONCAT(u.first_name, ' ', u.last_name) AS full_name,
        c.title AS course_title,
        e.enrolled_at
    FROM enrollments e
    JOIN users u ON e.student_id = u.id
    JOIN courses c ON e.course_id = c.id
");

// Fetch Students and Courses for dropdowns
$students = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM users WHERE account_type='student'");
$courses = $conn->query("SELECT id, title FROM courses");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Enrollments</title>
    <link rel="stylesheet" href="dashboard.css">
    <script>
        function openAddModal() {
            document.getElementById('enrollForm').reset();
            document.getElementById('enrollModal').style.display = 'block';
            document.getElementById('modalOverlay').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('enrollModal').style.display = 'none';
            document.getElementById('modalOverlay').style.display = 'none';
        }
    </script>
</head>
<body>

<h2>Manage Enrollments</h2>

<div class="top-actions">
    <button onclick="openAddModal()">+ Enroll Student</button>
</div>

<div id="modalOverlay" onclick="closeModal()"></div>

<!-- Add Enrollment Modal -->
<div id="enrollModal" class="modal">
    <h3>Enroll Student in Course</h3>
    <form method="POST" id="enrollForm">
        <select name="student_id" required>
            <option value="">Select Student</option>
            <?php while ($u = $students->fetch_assoc()): ?>
                <option value="<?= (int)$u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <select name="course_id" required>
            <option value="">Select Course</option>
            <?php while ($c = $courses->fetch_assoc()): ?>
                <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit" name="add_enroll">Enroll</button>
        <button type="button" onclick="closeModal()">Cancel</button>
    </form>
</div>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Student</th>
        <th>Course</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= (int)$row['enrollment_id'] ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['course_title']) ?></td>
            <td><?= htmlspecialchars($row['enrolled_at']) ?></td>
            <td>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this enrollment?');">
                    <input type="hidden" name="delete_enrollment" value="<?= (int)$row['enrollment_id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>