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
if (!$data || empty($data['order_id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$order_id = (int) $data['order_id'];

$mysqli = new mysqli('localhost','root','','cogact');
if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Database connection failed']);
    exit;
}

// Verify ownership and status
$stmt = $mysqli->prepare('SELECT status FROM orders WHERE id=? AND user_id=? LIMIT 1');
$stmt->bind_param('ii', $order_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(404);
    echo json_encode(['status'=>'error','message'=>'Order not found']);
    $mysqli->close();
    exit;
}

if ($row['status'] !== 'pending') {
    echo json_encode(['status'=>'error','message'=>'Only pending orders can be cancelled']);
    $mysqli->close();
    exit;
}

$upd = $mysqli->prepare('UPDATE orders SET status = ? WHERE id=?');
$newstatus = 'cancelled';
$upd->bind_param('si', $newstatus, $order_id);
$ok = $upd->execute();
$upd->close();
$mysqli->close();

if ($ok) echo json_encode(['status'=>'ok']);
else { http_response_code(500); echo json_encode(['status'=>'error','message'=>'Failed to cancel order']); }

?>
