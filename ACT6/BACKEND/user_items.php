<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Authentication required']);
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$mysqli = new mysqli('localhost','root','','cogact');
if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Database connection failed']);
    exit;
}

$out = ['status'=>'ok'];

// favourites
$favs = [];
$stmt = $mysqli->prepare('SELECT product_key, product_title, product_image, created_at FROM favourites WHERE user_id=? ORDER BY created_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) $favs[] = $r;
$stmt->close();
$out['favourites'] = $favs;

// orders
$ords = [];
$stmt = $mysqli->prepare('SELECT id, product_key, product_title, product_image, qty, payment_method, status, total_amount, created_at FROM orders WHERE user_id=? ORDER BY created_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) $ords[] = $r;
$stmt->close();
$out['orders'] = $ords;

$mysqli->close();

echo json_encode($out);

?>
