<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status'=>'error','message'=>'Authentication required']);
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
$qty = isset($data['qty']) ? (int)$data['qty'] : 1;
if ($qty < 1) $qty = 1;
$product_title = isset($data['product_title']) ? substr(trim($data['product_title']),0,255) : null;
$product_image = isset($data['product_image']) ? substr(trim($data['product_image']),0,255) : null;

$mysqli = new mysqli('localhost','root','','cogact');
if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Database connection failed']);
    exit;
}

// Fetch user contact details
$stmt = $mysqli->prepare('SELECT contact_number, address, region, country FROM users WHERE id=? LIMIT 1');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$missing = [];
if (!$user || empty(trim($user['contact_number'] ?? ''))) $missing[] = 'contact_number';
if (!$user || empty(trim($user['address'] ?? ''))) $missing[] = 'address';
if (!$user || empty(trim($user['region'] ?? ''))) $missing[] = 'region';
if (!$user || empty(trim($user['country'] ?? ''))) $missing[] = 'country';

if (!empty($missing)) {
    echo json_encode(['status'=>'error','code'=>'incomplete_profile','missing'=>$missing]);
    $mysqli->close();
    exit;
}

$contact_snapshot = $mysqli->real_escape_string($user['contact_number']);
$address_snapshot = $mysqli->real_escape_string(trim($user['address'] . ', ' . $user['region'] . ', ' . $user['country']));

// Insert order (payment_method = cash)
$ins = $mysqli->prepare('INSERT INTO orders (user_id, product_key, product_title, product_image, qty, status, payment_method, contact_snapshot, address_snapshot) VALUES (?,?,?,?,?,?,?,?,?)');
$status = 'pending';
$payment = 'cash';
$ins->bind_param('isssisiss', $user_id, $product_key, $product_title, $product_image, $qty, $status, $payment, $contact_snapshot, $address_snapshot);
$ok = $ins->execute();
$order_id = $ins->insert_id;
$ins->close();
$mysqli->close();

if ($ok) echo json_encode(['status'=>'ok','order_id'=>$order_id]);
else { http_response_code(500); echo json_encode(['status'=>'error','message'=>'Failed to create order']); }

?>
