<?php
// sigup.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signup.html');
    exit;
}

// Get values
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$pwd = $_POST['pwd'] ?? '';

// Validate
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pwd) < 6) {
    die('Invalid email or password too short.');
}

// Hash password
$hashed = password_hash($pwd, PASSWORD_BCRYPT);

// Connect to DB
$conn = new mysqli("localhost", "root", "", "stencil");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL with 3 placeholders
$stmt = $conn->prepare("INSERT INTO signup (name, email, pwd) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed);

if ($stmt->execute()) {
    echo "<script>
            alert('Signup successful!');
            window.location.href='index.php';
          </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
