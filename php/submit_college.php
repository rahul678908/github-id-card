<?php
include('config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $collegeName = $_POST['collegeName'];
    $collegeAddress = $_POST['collegeAddress'];
    $collegeEmail = $_POST['collegeEmail'];
    $collegeContact = $_POST['collegeContact'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO colleges (college_name, college_address, college_email, college_contact) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $collegeName, $collegeAddress, $collegeEmail, $collegeContact);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the college_creation.html with success message
        header("Location: college_creation.html?success=1");
        exit();
    } else {
        // Redirect with an error message
        header("Location: college_creation.html?error=" . urlencode($stmt->error));
        exit();
    }

    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>
