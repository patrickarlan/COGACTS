<?php
// Server-side profile router: redirects users to the appropriate dashboard
// - admins -> BACKEND/admin.php
// - regular users -> userdash.php
// - not logged in -> logsign.php

session_start();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
header("Pragma: no-cache");
header("Expires: 0");

$isAdmin = false;
if (!empty($_SESSION['is_admin'])) {
    $isAdmin = true;
}
if (!$isAdmin && !empty($_SESSION['role']) && is_string($_SESSION['role'])) {
    if (strtolower(trim($_SESSION['role'])) === 'admin') $isAdmin = true;
}

if ($isAdmin) {
    header('Location: BACKEND/admin.php');
    exit();
}

if (!empty($_SESSION['user_id'])) {
    header('Location: userdash.php');
    exit();
}

header('Location: logsign.php');
exit();
