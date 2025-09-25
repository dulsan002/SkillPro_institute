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

// ✅ Handle Delete via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course'])) {
    $id = (int)$_POST['delete_course'];
    $stmt = $conn->prepare("DELETE FROM courses WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ✅ Handle Add or Update
if (isset($_POST['save_course'])) {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $duration = trim($_POST['duration']);
    $location = trim($_POST['location']);
    $cost = (float)$_POST['cost'];
    $modules = (int)$_POST['modules'];
    $lectures = (int)$_POST['lectures'];

    if ($title && $category && $duration && $location && $cost >= 0 && $modules >= 0 && $lectures >= 0) {
        if (!empty($_POST['course_id'])) {
            $id = (int)$_POST['course_id'];
            $stmt = $conn->prepare("UPDATE courses SET title=?, category=?, description=?, duration=?, location=?, cost=?, modules=?, lectures=? WHERE id=?");
            $stmt->bind_param("sssssdiii", $title, $category, $description, $duration, $location, $cost, $modules, $lectures, $id);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("INSERT INTO courses (title, category, description, duration, location, cost, modules, lectures) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssdii", $title, $category, $description, $duration, $location, $cost, $modules, $lectures);
            $stmt->execute();
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ✅ Fetch All Courses
$result = $conn->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="dashboard.css">
    <script>
        function openEditModal(course) {
            document.getElementById('edit_id').value = course.id;
            document.getElementById('edit_title').value = course.title;
            document.getElementById('edit_category').value = course.category;
            document.getElementById('edit_description').value = course.description;
            document.getElementById('edit_duration').value = course.duration;
            document.getElementById('edit_location').value = course.location;
            document.getElementById('edit_cost').value = course.cost;
            document.getElementById('edit_modules').value = course.modules;
            document.getElementById('edit_lectures').value = course.lectures;

            document.getElementById('editModal').style.display = 'block';
            document.getElementById('modalOverlay').style.display = 'block';
        }

        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
            document.getElementById('modalOverlay').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('modalOverlay').style.display = 'none';
        }
    </script>
</head>
<body>

<div class="top-actions">
    <button onclick="openAddModal()">+ Add Course</button>
</div>

<div id="modalOverlay" onclick="closeModal()"></div>

<!-- ✅ Add Modal -->
<div id="addModal" class="modal">
    <h3>Add New Course</h3>
    <form method="POST">
        <input type="text" name="title" placeholder="Title" required>
        <input type="text" name="category" placeholder="Category" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="text" name="duration" placeholder="Duration" required>
        <input type="text" name="location" placeholder="Location" required>
        <input type="number" name="cost" placeholder="Cost" required min="0" step="0.01">
        <input type="text" name="modules" placeholder="Modules">
        <input type="text" name="lectures" placeholder="Lectures">
        <button type="submit" name="save_course">Add Course</button>
        <button type="button" onclick="closeModal()">Cancel</button>
    </form>
</div>

<!-- ✅ Edit Modal -->
<div id="editModal" class="modal">
    <h3>Edit Course</h3>
    <form method="POST">
        <input type="hidden" name="course_id" id="edit_id">
        <input type="text" name="title" id="edit_title" placeholder="Title" required>
        <input type="text" name="category" id="edit_category" placeholder="Category" required>
        <textarea name="description" id="edit_description" placeholder="Description"></textarea>
        <input type="text" name="duration" id="edit_duration" placeholder="Duration" required>
        <input type="text" name="location" id="edit_location" placeholder="Location" required>
        <input type="number" name="cost" id="edit_cost" placeholder="Cost" required min="0" step="0.01">
        <input type="text" name="modules" id="edit_modules" placeholder="Modules">
        <input type="text" name="lectures" id="edit_lectures" placeholder="Lectures">
        <button type="submit" name="save_course">Update Course</button>
        <button type="button" onclick="closeModal()">Cancel</button>
    </form>
</div>

<!-- ✅ Course Table -->
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Category</th>
        <th>Description</th>
        <th>Duration</th>
        <th>Location</th>
        <th>Start Date</th>
        <th>Cost</th>
        <th>Modules</th>
        <th>Lectures</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr id="row-<?= $row['id'] ?>">
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['duration']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= htmlspecialchars($row['startDate']) ?></td>
            <td><?= htmlspecialchars($row['cost']) ?></td>
            <td><?= htmlspecialchars($row['modules']) ?></td>
            <td><?= htmlspecialchars($row['lectures']) ?></td>
            <td class="action_buttons">
                <button onclick='openEditModal(<?= json_encode($row) ?>)'>Edit</button>
                <form method="POST" class="action-form" onsubmit="return confirm('Are you sure you want to delete this course?');">
  <input type="hidden" name="delete_course" value="<?= $row['id'] ?>">
  <button type="submit" class="delete-btn">Delete</button>
</form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>