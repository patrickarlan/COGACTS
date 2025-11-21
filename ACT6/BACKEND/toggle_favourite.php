<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Authentication required']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data || empty($data['product_key'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$product_key = substr(trim($data['product_key']), 0, 100);
$product_title = isset($data['product_title']) ? substr(trim($data['product_title']),0,255) : null;
$product_image = isset($data['product_image']) ? substr(trim($data['product_image']),0,255) : null;

$mysqli = new mysqli('localhost','root','','cogact');
if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Database connection failed']);
    exit;
}

// Check existing
$stmt = $mysqli->prepare('SELECT 1 FROM favourites WHERE user_id=? AND product_key=? LIMIT 1');
$stmt->bind_param('is', $user_id, $product_key);
$stmt->execute();
$res = $stmt->get_result();
$exists = (bool) $res->fetch_assoc();
$stmt->close();

if ($exists) {
    $del = $mysqli->prepare('DELETE FROM favourites WHERE user_id=? AND product_key=?');
    $del->bind_param('is', $user_id, $product_key);
    $ok = $del->execute();
    $del->close();
    if ($ok) echo json_encode(['status'=>'ok','favourited'=>false]);
    else { http_response_code(500); echo json_encode(['status'=>'error','message'=>'Failed to remove favourite']); }
    $mysqli->close();
    exit;
}

// Insert
$ins = $mysqli->prepare('INSERT INTO favourites (user_id, product_key, product_title, product_image) VALUES (?,?,?,?)');
$ins->bind_param('isss', $user_id, $product_key, $product_title, $product_image);
$ok = $ins->execute();
$ins->close();
$mysqli->close();

if ($ok) echo json_encode(['status'=>'ok','favourited'=>true]);
else { http_response_code(500); echo json_encode(['status'=>'error','message'=>'Failed to add favourite']); }

?>
