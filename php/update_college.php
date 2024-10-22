<?php
include('config.php');

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $collegeId = $_POST['id'];
    $collegeName = $_POST['collegeName'];
    $collegeAddress = $_POST['collegeAddress'];
    $collegeEmail = $_POST['collegeEmail'];
    $collegeContact = $_POST['collegeContact'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE colleges SET college_name = ?, college_address = ?, college_email = ?, college_contact = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $collegeName, $collegeAddress, $collegeEmail, $collegeContact, $collegeId);

    // Execute the statement
    if ($stmt->execute()) {
        echo "College updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
