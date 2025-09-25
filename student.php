<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['student_name'])) {
    header("Location: login.php");
    exit();
}

// Fetch session message if exists
$message = '';
$message_type = ''; // Can be 'success', 'warning', or 'error'
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    if (str_contains($message, '✅')) {
        $message_type = 'success';
    } elseif (str_contains($message, '⚠️')) {
        $message_type = 'warning';
    } else {
        $message_type = 'error';
    }
    unset($_SESSION['message']); // Clear after displaying
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "skillpro");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch all courses
$courses = [];
$result = $conn->query("SELECT id, title FROM courses ORDER BY title ASC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="student.css">
  <style>
    /* Session message styles */
    .session-message {
        padding: 12px 20px;
        margin-bottom: 15px;
        border-radius: 5px;
        font-weight: 500;
    }
    .session-message.success { background-color: #d4edda; color: #155724; }
    .session-message.warning { background-color: #fff3cd; color: #856404; }
    .session-message.error { background-color: #f8d7da; color: #721c24; }
  </style>
</head>
<body>
  <div class="container">

    <?php if ($message): ?>
      <div class="session-message <?= $message_type ?>" id="sessionMessage">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <h1>Welcome, <?= htmlspecialchars($_SESSION['student_name']) ?> 👋</h1>

    <!-- Course Enrollment -->
    <section class="card">
      <h2>📚 Enroll in a Course</h2>
      <form action="enroll_course.php" method="POST">
        <select name="course_id" required>
          <option value="">-- Select a Course --</option>
          <?php foreach ($courses as $course): ?>
            <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit">Enroll</button>
      </form>
    </section>
  </div>

  <script>
    // Redirect after 2s if it's a success message
    const messageDiv = document.getElementById('sessionMessage');
    if (messageDiv && messageDiv.classList.contains('success')) {
        setTimeout(() => {
            window.location.href = 'course.html';
        }, 3000); // 2000ms = 2 seconds
    }
  </script>
</body>
</html>
