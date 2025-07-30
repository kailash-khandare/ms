<?php
if (isset($_POST['submit'])) {
    // Collect and sanitize form inputs
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $type = trim($_POST['type']);
    $des = trim($_POST['description']);

    // Basic validation
    if (empty($name) || empty($contact) || empty($type) || empty($des)) {
        die("Please fill all required fields.");
    }

    // Handle file upload
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
        die("Please upload an image.");
    }

    $file = $_FILES['image'];
    $fileName = time() . '_' . basename($file['name']);
    $targetFile = $uploadDir . $fileName;

    // Validate image file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = mime_content_type($file['tmp_name']);
    if (!in_array($fileType, $allowedTypes)) {
        die("Only JPG, PNG, GIF, and WEBP images are allowed.");
    }

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        die("Failed to upload image.");
    }

    // Database connection
    $conn = new mysqli("localhost", "root", "", "stencil");

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Prepare statement with correct parameter types (all strings)
    $stmt = $conn->prepare("INSERT INTO upload (name, contact, type, des, image) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssss", $name, $contact, $type, $des, $fileName);

    if ($stmt->execute()) {
        echo "✅ Data inserted successfully!";
    header("Location: imgupload.php");
    } else {
        echo "❌ Insert error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
   
    exit;
}

?>

