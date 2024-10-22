<?php
include('config.php');

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if the email matches the predefined admin email
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a reset token
        $token = bin2hex(random_bytes(32));  // 64-character token

        // Store the token in the database
        $update_stmt = $conn->prepare("UPDATE admin SET reset_token = ? WHERE email = ?");
        $update_stmt->bind_param("ss", $token, $email);

        if ($update_stmt->execute()) {
            // Display the token in the prompt instead of sending it via email
            echo "Use the following token to reset your password: $token";
        } else {
            echo "Error updating token: " . $update_stmt->error;
        }

        $update_stmt->close();
    } else {
        echo "Email not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
