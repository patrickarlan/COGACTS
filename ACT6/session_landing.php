<?php
session_start();
// Redirect to the appropriate landing depending on session state
if (!empty($_SESSION['is_admin'])) {
    header('Location: BACKEND/admin.php');
    exit;
}
if (!empty($_SESSION['user_id'])) {
    header('Location: userdash.php');
    exit;
}
// Not logged in -> show login
header('Location: logsign.php');
exit;
?>
