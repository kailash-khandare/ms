
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        die("Invalid ID.");
    }

    $conn = new mysqli("localhost", "root", "", "stencil");
    if ($conn->connect_error) {
        die("DB connection failed: " . $conn->connect_error);
    }

    // Get image filename to delete file from server
    $stmt = $conn->prepare("SELECT image FROM upload WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imageFile);
    if ($stmt->fetch()) {
        $stmt->close();

        // Delete database record
        $delStmt = $conn->prepare("DELETE FROM upload WHERE id = ?");
        $delStmt->bind_param("i", $id);
        if ($delStmt->execute()) {
            $delStmt->close();

            // Delete image file from uploads folder
            $filePath = 'uploads/' . $imageFile;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $conn->close();
            header("Location: imgupload.php?msg=deleted");
            exit;
        } else {
            die("Failed to delete record: " . $delStmt->error);
        }
    } else {
        $stmt->close();
        $conn->close();
        die("Image not found.");
    }
} else {
    die("Invalid request.");
}

?>