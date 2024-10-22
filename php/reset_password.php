<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash the new password
    // $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Verify the token and its expiry time
    $stmt = $conn->prepare("SELECT * FROM admin WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $decodedToken = urldecode($token);
    

    if ($result->num_rows == 1) {
        // Token is valid, update the password
        $stmt = $conn->prepare("UPDATE admin SET password = ?, reset_token = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashed_password, $token);
        $stmt->execute();

        echo "Password has been reset successfully.";
    } else {
        echo "Invalid or expired token.";
        echo "$decodedToken";
    }

    $stmt->close();
    $conn->close();
}
?>
