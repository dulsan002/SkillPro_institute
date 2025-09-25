<?php
session_start();

// Redirect to login if admin is not logged in
if (!isset($_SESSION['admin_name']) || $_SESSION['account_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="container">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <h2>Admin Panel</h2>
      <ul>
        <li><a href="?page=courses">Manage Courses</a></li>
        <li><a href="?page=users">Manage Users</a></li>
        <li><a href="?page=enrollments">Manage Enrollments</a></li>
        <li><a href="?page=events">Manage Events</a></li> <!-- ✅ Added -->
      </ul>
    </div>
    <div>
      <a href="index.html" class="logout">Logout</a>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        if ($page === 'courses' && file_exists("manage_courses.php")) {
            include "manage_courses.php";
        } elseif ($page === 'users' && file_exists("manage_users.php")) {
            include "manage_users.php";
        } elseif ($page === 'enrollments' && file_exists("manage_enrollments.php")) {
            include "manage_enrollments.php";
        } elseif ($page === 'events' && file_exists("manage_events.php")) { 
            include "manage_events.php";
        } else {
            echo "<p>Page not found.</p>";
        }
    } else {
        echo "<h1>Welcome, " . htmlspecialchars($admin_name) . "!</h1>";
        echo "<p>Select an option from the sidebar to get started.</p>";
    }
    ?>
  </main>

</div>

<script>
function confirmDelete(id, type) {
  if (confirm("Are you sure you want to delete this record?")) {
    window.location.href = `?page=${type}&delete=${id}`;
  }
}
</script>

</body>
</html>