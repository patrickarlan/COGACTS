<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "cogact");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$username = $_POST['reg-username'] ?? '';
$email = $_POST['reg-email'] ?? '';
$password = $_POST['reg-password'] ?? '';

// Simple validation
if ($username && $email && $password) {
    // Check if user exists
    $check = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "Username or email already exists.";
    } else {
        // Hash password
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed);
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
} else {
    echo "All fields are required.";
}
$conn->close();
?>
