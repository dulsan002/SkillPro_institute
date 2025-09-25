<?php 
// ===== SkillPro Registration Handler =====

session_start();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // === Database Configuration ===
    $host     = 'localhost';
    $db       = 'skillpro';
    $user     = 'root';
    $pass     = '';
    $charset  = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    // === Collect and sanitize form data ===
    $firstName   = trim($_POST['firstName'] ?? '');
    $lastName    = trim($_POST['lastName'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $password    = $_POST['password'] ?? '';
    $accountType = $_POST['accountType'] ?? '';

    // === Validation ===
    if (!$firstName || !$lastName || !$email || !$password || !$accountType) {
        $errors[] = "All fields are required.";
    }

    // Email format validation
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password strength validation
    $passwordErrors = [];
    if ($password && strlen($password) < 8) {
        $passwordErrors[] = "at least 8 characters";
    }
    if ($password && !preg_match('/[A-Z]/', $password)) {
        $passwordErrors[] = "one uppercase letter";
    }
    if ($password && !preg_match('/[a-z]/', $password)) {
        $passwordErrors[] = "one lowercase letter";
    }
    if ($password && !preg_match('/[0-9]/', $password)) {
        $passwordErrors[] = "one number";
    }
    if ($password && !preg_match('/[\W]/', $password)) {
        $passwordErrors[] = "one special character";
    }

    if (!empty($passwordErrors)) {
        $errors[] = "Password must contain " . implode(', ', $passwordErrors) . ".";
    }

    // === Insert into database if no errors ===
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password, account_type)
            VALUES (:first_name, :last_name, :email, :password, :account_type)
        ");

        try {
            $stmt->execute([
                ':first_name'   => $firstName,
                ':last_name'    => $lastName,
                ':email'        => $email,
                ':password'     => $hashedPassword,
                ':account_type' => $accountType,
            ]);

            // Registration success
            $success = "Account created successfully!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "An account with this email already exists.";
            } else {
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<!-- === Display Errors on Page & Redirect Back === -->
<?php if (!empty($errors)): ?>
  <div class="error-box" style="color:red; margin:10px 0;">
    <?php foreach ($errors as $error): ?>
      <p><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>
  </div>
  <script>
    alert("<?= addslashes(implode('\n', $errors)) ?>");
    window.location.href = "register.html"; // Go back to login page
  </script>
<?php endif; ?>

<!-- === Success Alert & Redirect === -->
<?php if ($success): ?>
  <div class="success-box" style="color:green; margin:10px 0;">
    <p><?= htmlspecialchars($success) ?></p>
  </div>
  <script>
    alert("<?= addslashes($success) ?>");
    window.location.href = "login.html"; // Redirect to login page
  </script>
<?php endif; ?>
