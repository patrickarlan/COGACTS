<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$is_admin = !empty($_SESSION['is_admin']);
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Authentication required']);
    exit;
}

// Allow admin to request another user's items via ?user_id= when session indicates admin
if ($is_admin && isset($_GET['user_id'])) {
    $user_id = (int) $_GET['user_id'];
} else {
    $user_id = (int) $_SESSION['user_id'];
}
$mysqli = new mysqli('localhost','root','','cogact');
if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Database connection failed']);
    exit;
}

// include basic user info when admin requests another user's data
$out = ['status'=>'ok'];
// fetch user's first name for context
$userInfo = null;
try {
    $uStmt = $mysqli->prepare('SELECT id, first_name, last_name FROM users WHERE id=?');
    $uStmt->bind_param('i', $user_id);
    $uStmt->execute();
    $uRes = $uStmt->get_result();
    if ($uRow = $uRes->fetch_assoc()) {
        $userInfo = ['id' => (int)$uRow['id'], 'first_name' => $uRow['first_name'] ?? '', 'last_name' => $uRow['last_name'] ?? ''];
    }
    $uStmt->close();
} catch (Exception $e) {
    // ignore — userInfo will remain null
}
if ($userInfo) $out['user'] = $userInfo;

// favourites
$favs = [];
$stmt = $mysqli->prepare('SELECT product_key, product_title, created_at FROM favourites WHERE user_id=? ORDER BY created_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) $favs[] = $r;
$stmt->close();
$out['favourites'] = $favs;

// orders
$ords = [];
$stmt = $mysqli->prepare('SELECT id, product_key, product_title, qty, payment_method, status, total_amount, created_at FROM orders WHERE user_id=? ORDER BY created_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) $ords[] = $r;
$stmt->close();
$out['orders'] = $ords;

$mysqli->close();

echo json_encode($out);

?>
