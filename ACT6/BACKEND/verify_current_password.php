<?php
session_start();
header('Content-Type: application/json');
if (empty($_SESSION['user_id'])) {
    echo json_encode(['ok' => false, 'message' => 'Not authenticated']);
    exit;
}
$uid = (int)$_SESSION['user_id'];
$pw = $_POST['current_password'] ?? '';
if ($pw === '') {
    echo json_encode(['ok' => false, 'message' => 'Missing password']);
    exit;
}
$conn = new mysqli('localhost','root','','cogact');
if ($conn->connect_error) {
    echo json_encode(['ok' => false, 'message' => 'DB error']);
    exit;
}
$stmt = $conn->prepare('SELECT password FROM users WHERE id = ? LIMIT 1');
$stmt->bind_param('i',$uid);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();
$conn->close();
if (!$user) {
    echo json_encode(['ok' => false, 'message' => 'User not found']);
    exit;
}
$hash = $user['password'];
if (password_verify($pw, $hash)) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'message' => 'Incorrect password']);
}
