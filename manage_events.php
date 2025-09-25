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

//  Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event'])) {
    $id = (int)$_POST['delete_event'];
    $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

//  Handle Add or Update
if (isset($_POST['save_event'])) {
    $title = trim($_POST['title']);
    $tag = trim($_POST['tag']);
    $description = trim($_POST['description']);
    $time = trim($_POST['time']);
    $location = trim($_POST['location']);
    $registered = (int)$_POST['registered'];
    $capacity = (int)$_POST['capacity'];

    if ($title && $tag && $time && $location && $capacity >= 0 && $registered >= 0) {
        if (!empty($_POST['event_id'])) {
            $id = (int)$_POST['event_id'];
            $stmt = $conn->prepare("UPDATE events SET title=?, tag=?, description=?, time=?, location=?, registered=?, capacity=? WHERE id=?");
            $stmt->bind_param("ssssssii", $title, $tag, $description, $time, $location, $registered, $capacity, $id);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("INSERT INTO events (title, tag, description, time, location, registered, capacity) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $title, $tag, $description, $time, $location, $registered, $capacity);
            $stmt->execute();
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

//  Fetch All Events
$result = $conn->query("SELECT * FROM events");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Events</title>
  <link rel="stylesheet" href="dashboard.css">
  <script>
    function openEditModal(event) {
      document.getElementById('edit_id').value = event.id;
      document.getElementById('edit_title').value = event.title;
      document.getElementById('edit_tag').value = event.tag;
      document.getElementById('edit_description').value = event.description;
      document.getElementById('edit_time').value = event.time;
      document.getElementById('edit_location').value = event.location;
      document.getElementById('edit_registered').value = event.registered;
      document.getElementById('edit_capacity').value = event.capacity;

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
  <button onclick="openAddModal()">+ Add Event</button>
</div>

<div id="modalOverlay" onclick="closeModal()"></div>

<!--  Add Modal -->
<div id="addModal" class="modal">
  <h3>Add New Event</h3>
  <form method="POST">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="tag" placeholder="Tag" required>
    <textarea name="description" placeholder="Description"></textarea>
    <input type="text" name="time" placeholder="Time" required>
    <input type="text" name="location" placeholder="Location" required>
    <input type="number" name="registered" placeholder="Registered" required min="0">
    <input type="number" name="capacity" placeholder="Capacity" required min="0">
    <button type="submit" name="save_event">Add Event</button>
    <button type="button" onclick="closeModal()">Cancel</button>
  </form>
</div>

<!--  Edit Modal -->
<div id="editModal" class="modal">
  <h3>Edit Event</h3>
  <form method="POST">
    <input type="hidden" name="event_id" id="edit_id">
    <input type="text" name="title" id="edit_title" placeholder="Title" required>
    <input type="text" name="tag" id="edit_tag" placeholder="Tag" required>
    <textarea name="description" id="edit_description" placeholder="Description"></textarea>
    <input type="text" name="time" id="edit_time" placeholder="Time" required>
    <input type="text" name="location" id="edit_location" placeholder="Location" required>
    <input type="number" name="registered" id="edit_registered" placeholder="Registered" required min="0">
    <input type="number" name="capacity" id="edit_capacity" placeholder="Capacity" required min="0">
    <button type="submit" name="save_event">Update Event</button>
    <button type="button" onclick="closeModal()">Cancel</button>
  </form>
</div>

<!--  Event Table -->
<table border="1" cellpadding="8" cellspacing="0">
  <tr>
    <th>ID</th>
    <th>Title</th>
    <th>Tag</th>
    <th>Description</th>
    <th>Time</th>
    <th>Location</th>
    <th>Registered</th>
    <th>Capacity</th>
    <th>Action</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
    <tr id="row-<?= $row['id'] ?>">
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['title']) ?></td>
      <td><?= htmlspecialchars($row['tag']) ?></td>
      <td><?= htmlspecialchars($row['description']) ?></td>
      <td><?= htmlspecialchars($row['time']) ?></td>
      <td><?= htmlspecialchars($row['location']) ?></td>
      <td><?= $row['registered'] ?></td>
      <td><?= $row['capacity'] ?></td>
      <td>
  <div class="action-buttons">
    <button class="edit-btn" onclick='openEditModal(<?= json_encode($row) ?>)'>Edit</button>
    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
      <input type="hidden" name="delete_event" value="<?= $row['id'] ?>">
      <button type="submit" class="delete-btn">Delete</button>
    </form>
  </div>
</td>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>