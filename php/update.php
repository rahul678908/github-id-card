<?php
include('config.php');

$username = "thomson"; // Your admin username or user identifier
$new_password = "12345"; // The new password to be hashed

// Hash the new password using bcrypt
$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

// Update the password in the database
$stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
$stmt->bind_param("ss", $hashed_password, $username);

if ($stmt->execute()) {
    echo "Password updated successfully!";
} else {
    echo "Error updating password: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
