<?php
session_start();

// Require login
if (empty($_SESSION['user_id'])) {
    header('Location: ../logsign.php');
    exit;
}

$uid = (int) $_SESSION['user_id'];

// Simple helper
function redirect_back($url = '../BACKEND/editprofile.php', $msg = null, $type = 'error') {
    if ($msg) {
        $_SESSION['flash_' . $type] = $msg;
    }
    header('Location: ' . $url);
    exit;
}

// Read and sanitize POST
$first = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last  = isset($_POST['last_name'])  ? trim($_POST['last_name'])  : '';
$phone = isset($_POST['contact_number']) ? trim($_POST['contact_number']) : '';
// Normalize phone: keep digits only
$phone_digits = preg_replace('/\D+/', '', $phone);
// Enforce exactly 11 digits for mobile numbers (if provided)
if ($phone_digits === '') {
    redirect_back('../BACKEND/editprofile.php', 'Contact number is required', 'error');
}
if (!preg_match('/^\d{11}$/', $phone_digits)) {
    redirect_back('../BACKEND/editprofile.php', 'Contact number must be exactly 11 digits', 'error');
}
$phone = $phone_digits;
$region = isset($_POST['region']) ? trim($_POST['region']) : '';
$country = isset($_POST['country']) ? trim($_POST['country']) : '';
$postal  = isset($_POST['postal_id']) ? trim($_POST['postal_id']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';

// Basic validation
// Required fields
if ($first === '') {
    redirect_back('../BACKEND/editprofile.php', 'First name is required', 'error');
}
if ($last === '') {
    redirect_back('../BACKEND/editprofile.php', 'Last name is required', 'error');
}
if ($phone === '') {
    redirect_back('../BACKEND/editprofile.php', 'Contact number is required', 'error');
}

if (mb_strlen($first) > 100 || mb_strlen($last) > 100) {
    redirect_back('../BACKEND/editprofile.php', 'Name too long', 'error');
}
if (mb_strlen($phone) > 32 || mb_strlen($postal) > 32) {
    redirect_back('../BACKEND/editprofile.php', 'Contact or postal too long', 'error');
}
if (mb_strlen($region) > 100 || mb_strlen($country) > 100) {
    redirect_back('../BACKEND/editprofile.php', 'Region/Country too long', 'error');
}
if (mb_strlen($address) > 2000) {
    redirect_back('../BACKEND/editprofile.php', 'Address too long', 'error');
}

// Content validation rules
// Names and country: only letters, spaces, hyphens and apostrophes
$namePattern = '/^[\p{L}\s\-\']*$/u';
if ($first !== '' && !preg_match($namePattern, $first)) {
    redirect_back('../BACKEND/editprofile.php', 'First name may only contain letters, spaces, hyphens, and apostrophes', 'error');
}
if ($last !== '' && !preg_match($namePattern, $last)) {
    redirect_back('../BACKEND/editprofile.php', 'Last name may only contain letters, spaces, hyphens, and apostrophes', 'error');
}
if ($country !== '' && !preg_match($namePattern, $country)) {
    redirect_back('../BACKEND/editprofile.php', 'Country may only contain letters, spaces, hyphens, and apostrophes', 'error');
}

// Contact and postal: disallow letters (only digits, plus, spaces, hyphens allowed)
if ($phone !== '' && preg_match('/\p{L}/u', $phone)) {
    redirect_back('../BACKEND/editprofile.php', 'Contact number must not contain letters', 'error');
}
if ($postal !== '' && preg_match('/\p{L}/u', $postal)) {
    redirect_back('../BACKEND/editprofile.php', 'Postal code must not contain letters', 'error');
}

$conn = new mysqli('localhost', 'root', '', 'cogact');
if ($conn->connect_error) {
    redirect_back('../BACKEND/editprofile.php', 'Database connection error', 'error');
}

$stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, contact_number = ?, region = ?, country = ?, postal_id = ?, address = ? WHERE id = ?");
if (!$stmt) {
    $conn->close();
    redirect_back('../BACKEND/editprofile.php', 'DB prepare failed', 'error');
}

$stmt->bind_param('sssssssi', $first, $last, $phone, $region, $country, $postal, $address, $uid);
$ok = $stmt->execute();
$stmt->close();
$conn->close();

if ($ok) {
    // Redirect back to the edit form and show a success flash modal
    redirect_back('../BACKEND/editprofile.php', 'Profile updated successfully.', 'success');
} else {
    redirect_back('../BACKEND/editprofile.php', 'Failed to update profile', 'error');
}

?>
