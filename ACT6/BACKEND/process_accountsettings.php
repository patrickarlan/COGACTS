<?php
session_start();
// Require login
if (empty($_SESSION['user_id'])) {
    header('Location: ../logsign.php');
    exit;
}

$uid = (int)$_SESSION['user_id'];

// Simple flash helper
function flash($type, $msg){ $_SESSION['flash_' . $type] = $msg; }

// Read POST
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$new = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$current = isset($_POST['current_password']) ? $_POST['current_password'] : '';

// Basic validation
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('error', 'Invalid email address');
    header('Location: ../BACKEND/accountsettings.php');
    exit;
}

$conn = new mysqli('localhost','root','','cogact');
if ($conn->connect_error) {
    flash('error','Database connection error');
    header('Location: ../BACKEND/accountsettings.php');
    exit;
}

// Fetch current user record
$stmt = $conn->prepare('SELECT email, password FROM users WHERE id = ? LIMIT 1');
$stmt->bind_param('i',$uid);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();
if (!$user) {
    flash('error','User not found');
    $conn->close();
    header('Location: ../BACKEND/accountsettings.php');
    exit;
}

$updates = [];
$params = '';
$values = [];

// If email changed, prepare update
if ($email !== '' && $email !== $user['email']) {
    $updates[] = 'email = ?';
    $params .= 's';
    $values[] = $email;
}

// If new password provided, validate and require current password
if ($new !== '') {
    if ($new !== $confirm) {
        flash('error','New password and confirmation do not match');
        $conn->close();
        header('Location: ../BACKEND/accountsettings.php');
        exit;
    }
    if (empty($current)) {
        flash('error','Current password is required to change your password');
        $conn->close();
        header('Location: ../BACKEND/accountsettings.php');
        exit;
    }
    // verify current password
    if (!password_verify($current, $user['password'])) {
        flash('error','Current password is incorrect');
        $conn->close();
        header('Location: ../BACKEND/accountsettings.php');
        exit;
    }
    $hash = password_hash($new, PASSWORD_DEFAULT);
    $updates[] = 'password = ?';
    $params .= 's';
    $values[] = $hash;
}

// If there are updates, run update
if (!empty($updates)) {
    $sql = 'UPDATE users SET ' . implode(', ', $updates) . ' WHERE id = ?';
    $params .= 'i';
    $values[] = $uid;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        flash('error','DB prepare failed');
        $conn->close();
        header('Location: ../BACKEND/accountsettings.php');
        exit;
    }
    // bind params dynamically
    $bind_names[] = $params;
    for ($i=0; $i < count($values); $i++) {
        $bind_name = 'bind' . $i;
        $$bind_name = $values[$i];
        $bind_names[] = &$$bind_name;
    }
    call_user_func_array(array($stmt,'bind_param'), $bind_names);
    $ok = $stmt->execute();
    if (!$ok) {
        flash('error','Failed to update account: ' . $stmt->error);
        $stmt->close();
        $conn->close();
        header('Location: ../BACKEND/accountsettings.php');
        exit;
    }
    $stmt->close();
    flash('success','Account updated successfully');
} else {
    // nothing to update
    flash('success','No changes were made');
}

$conn->close();
header('Location: ../BACKEND/accountsettings.php');
exit;
