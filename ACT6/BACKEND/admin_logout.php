<?php
// Admin logout that also performs a full session logout for the site.
// This ensures clicking Logout in the admin dashboard signs the entire session out.
session_start();

// Unset all session variables
$_SESSION = [];

// If session cookie exists, clear it
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'], $params['secure'], $params['httponly']
    );
}

// Destroy session data on server
session_unset();
session_destroy();

// Regenerate a fresh session id to avoid fixation
if (session_status() === PHP_SESSION_NONE) session_start();
session_regenerate_id(true);

// Also clear remember cookies used by the app
setcookie('remember_username', '', time() - 3600, '/');

// Redirect to login page
header('Location: ../logsign.php');
exit;
