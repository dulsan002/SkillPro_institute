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

// Add or Update User
if (isset($_POST['save_user'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $account_type = $_POST['account_type'];

    if (!empty($_POST['user_id'])) {
        $id = (int)$_POST['user_id'];
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, password=?, account_type=? WHERE id=?");
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $password, $account_type, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, account_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $account_type);
        $stmt->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete User via POST
if (isset($_POST['delete_user'])) {
    $id = (int)$_POST['delete_user'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch Users
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="dashboard.css">
    <script>
        function openAddModal() {
            document.getElementById('userForm').reset();
            document.getElementById('user_id').value = '';
            document.getElementById('formTitle').innerText = 'Add New User';
            document.getElementById('submitBtn').innerText = 'Add User';
            document.getElementById('userModal').style.display = 'block';
            document.getElementById('modalOverlay').style.display = 'block';
        }

        function openEditModal(user) {
            document.getElementById('user_id').value = user.id;
            document.getElementById('first_name').value = user.first_name;
            document.getElementById('last_name').value = user.last_name;
            document.getElementById('email').value = user.email;
            document.getElementById('password').value = '';
            document.getElementById('account_type').value = user.account_type;
            document.getElementById('formTitle').innerText = 'Edit User';
            document.getElementById('submitBtn').innerText = 'Update User';
            document.getElementById('userModal').style.display = 'block';
            document.getElementById('modalOverlay').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('userModal').style.display = 'none';
            document.getElementById('modalOverlay').style.display = 'none';
        }
    </script>
</head>
<body>

<h2>Manage Users</h2>

<div class="top-actions">
    <button onclick="openAddModal()">+ Add User</button>
</div>

<div id="modalOverlay" onclick="closeModal()"></div>

<!-- Add/Edit User Modal -->
<div id="userModal" class="modal">
    <h3 id="formTitle">Add New User</h3>
    <form method="POST" id="userForm">
        <input type="hidden" name="user_id" id="user_id">
        <input type="text" name="first_name" id="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" id="last_name" placeholder="Last Name" required>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <select name="account_type" id="account_type" required>
            <option value="">Select Role</option>
            <option value="student">Student</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit" name="save_user" id="submitBtn">Add User</button>
        <button type="button" onclick="closeModal()">Cancel</button>
    </form>
</div>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Created At</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= (int)$row['id'] ?></td>
            <td><?= htmlspecialchars($row['first_name']) ?></td>
            <td><?= htmlspecialchars($row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['account_type']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td>
                <button onclick='openEditModal(<?= json_encode($row) ?>)'>Edit</button>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    <input type="hidden" name="delete_user" value="<?= (int)$row['id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>