<?php
session_start();
include('config.php');

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Basic validation for empty fields (server-side)
    if (empty($username) || empty($password)) {
        echo 'fail'; // Return a failure if any field is empty
        exit;
    }

    // Fetch the predefined user from the database
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Check if the entered password matches the stored password
        if ($password === $row['password']) { // Compare directly without hashing
            // Login successful
            $_SESSION['username'] = $username;  // Store username in session
            $_SESSION['loggedin'] = true;       // Indicate login status
            
            header("Location: ../index.html"); // Change to your desired page
            exit();   // Output 'success' for frontend handling
        } else {
            // Incorrect password
            echo 'Incorrect password'; // Provide feedback for incorrect password
        }
    } else {
        // User not found
        echo 'User not found'; // Provide feedback for user not found
    }

    $stmt->close();
    $conn->close();
}
?>

