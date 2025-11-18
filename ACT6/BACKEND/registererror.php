<?php
// COMPONENTS/registerscript.php
// Handles registration logic and JS for register.php
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "cogact");
    if ($conn->connect_error) {
        $message = "Connection failed: " . $conn->connect_error;
    } else {
        $username = $_POST['reg-username'] ?? '';
        $email = $_POST['reg-email'] ?? '';
        $password = $_POST['reg-password'] ?? '';
        if ($username && $email && $password) {
            $check = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
            $check->bind_param("ss", $username, $email);
            $check->execute();
            $result = $check->get_result();
            if ($result->num_rows > 0) {
                $message = "Username or email already exists.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed);
                if ($stmt->execute()) {
                    $message = "Registration successful!";
                } else {
                    $message = "Error: " . $stmt->error;
                }
            }
        } else {
            $message = "All fields are required.";
        }
        $conn->close();
    }
}
?>
