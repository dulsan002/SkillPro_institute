<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_secure', 0);

session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $db = 'skillpro';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = "Email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            if ($user['account_type'] === 'admin') {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['first_name'];
                $_SESSION['account_type'] = 'admin';
                header("Location: dashboard.php");
                exit();
            } elseif ($user['account_type'] === 'student') {
                $_SESSION['student_id'] = $user['id'];
                $_SESSION['student_name'] = $user['first_name'];
                $_SESSION['account_type'] = 'student';
                header("Location: student.php");
                exit();
            } else {
                $error = "Unknown account type.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }

    // if there's an error, show popup
    if (!empty($error)) {
        echo "<script>alert('$error'); window.location.href='login.html';</script>";
        exit();
    }
}
?>
