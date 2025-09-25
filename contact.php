<?php
// DB config
$host = 'localhost';
$db   = 'skillpro';
$user = 'root';
$pass = '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "DB connection failed.";
  exit;
}

// Sanitize helper
function clean($data) {
  return htmlspecialchars(strip_tags(trim($data)));
}

// Collect & sanitize
$name    = clean($_POST['name'] ?? '');
$email   = clean($_POST['email'] ?? '');
$phone   = clean($_POST['phone'] ?? '');
$branch  = clean($_POST['branch'] ?? '');
$subject = clean($_POST['subject'] ?? '');
$message = clean($_POST['message'] ?? '');

if (!$name || !$email || !$subject || !$message) {
  echo "Please fill in all required fields.";
  exit;
}

// Insert into DB
$sql = "INSERT INTO contact_messages (name, email, phone, branch, subject, message)
        VALUES (:name, :email, :phone, :branch, :subject, :message)";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute([
  ':name'    => $name,
  ':email'   => $email,
  ':phone'   => $phone,
  ':branch'  => $branch,
  ':subject' => $subject,
  ':message' => $message
]);

echo $success ? "Message saved successfully!" : "Failed to save message.";
?>