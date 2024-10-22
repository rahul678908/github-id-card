<?php
include('config.php');

// Function to sanitize user input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// File upload configuration
$targetDir = "uploads/";  // Use this directory to save photos
$allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
$maxFileSize = 2 * 1024 * 1024; // 2MB

// Handle student photo upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $name = sanitizeInput($_POST['name']);
    $roll_number = sanitizeInput($_POST['roll_number']);
    $father_name = sanitizeInput($_POST['father_name']);
    $address = sanitizeInput($_POST['address']);
    $blood_group = strtoupper(sanitizeInput($_POST['blood_group']));
    $phone = sanitizeInput($_POST['phone']);
    $email = sanitizeInput($_POST['email']) ? sanitizeInput($_POST['email']) : null;

    // Validate blood group (server-side check)
    $validBloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
    if (!in_array($blood_group, $validBloodGroups)) {
        die("Invalid blood group");
    }

    // Validate phone number (simple check for digits and length)
    if (!preg_match('/^\d{10}$/', $phone)) {
        die("Invalid phone number");
    }

    // Handle file upload
    if (isset($_FILES['photo'])) {
        $photo = $_FILES['photo'];
        $fileName = basename($photo['name']);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        // Check file size for student photo
        if ($photo['size'] > $maxFileSize) {
            die("Photo size exceeds the 2MB limit");
        }

        // Check file type for student photo
        if (!in_array(strtolower($fileType), $allowedTypes)) {
            die("Invalid file type for student photo. Only JPG, JPEG, PNG, and GIF are allowed.");
        }

        // Create a unique name for the photo
        $newFileName = uniqid() . "." . $fileType;
        $targetFilePath = $targetDir . $newFileName;

        // Upload the student photo to the server
        if (move_uploaded_file($photo['tmp_name'], $targetFilePath)) {
            // Insert student data into the database
            $stmt = $conn->prepare("INSERT INTO students (roll_number, name, father_name, address, blood_group, phone, email, photo, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssssss", $roll_number, $name, $father_name, $address, $blood_group, $phone, $email, $newFileName);

            if ($stmt->execute()) {
                echo "Student information saved successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            die("There was an error uploading the photo.");
        }
    }

    // Close the database connection
    $conn->close();
}
?>
