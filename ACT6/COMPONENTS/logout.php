<?php
session_start();
session_unset();
session_destroy();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
header("Pragma: no-cache");
header("Expires: 0");

header("Location: /COG3BACK/ACT6/logsign.php");
exit();
