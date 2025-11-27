<?php
session_start();
header('Content-Type: application/json');

// Only admins allowed
if (empty($_SESSION['is_admin'])) {
  http_response_code(403);
  echo json_encode(['status'=>'error','message'=>'Forbidden']);
  exit;
}

// CSRF protection: expect header 'X-CSRF-Token'
$headers = getallheaders();
$token = $headers['X-CSRF-Token'] ?? $headers['x-csrf-token'] ?? '';
if (empty($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>'Invalid CSRF token']);
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$user_id = intval($input['user_id'] ?? 0);
$action = $input['action'] ?? '';

if (!$user_id || !in_array($action, ['deactivate','activate'])) {
  echo json_encode(['status'=>'error','message'=>'Invalid input']);
  exit;
}

// Prevent admin from deactivating themselves
if (isset($_SESSION['user_id']) && intval($_SESSION['user_id']) === $user_id) {
  echo json_encode(['status'=>'error','message'=>'Cannot change your own status']);
  exit;
}

$mysqli = new mysqli('localhost','root','','cogact');
if ($mysqli->connect_error) {
  echo json_encode(['status'=>'error','message'=>'DB connection failed']);
  exit;
}

if ($action === 'deactivate') {
  $stmt = $mysqli->prepare("UPDATE users SET status = 'deactivated', deactivated_at = NOW(), deactivated_by = ? WHERE id = ?");
  $adminId = intval($_SESSION['user_id'] ?? 0);
  $stmt->bind_param('ii', $adminId, $user_id);
} else {
  $stmt = $mysqli->prepare("UPDATE users SET status = 'active', deactivated_at = NULL, deactivated_by = NULL WHERE id = ?");
  $stmt->bind_param('i', $user_id);
}

$ok = $stmt->execute();
$stmt->close();
$mysqli->close();

if ($ok) {
  echo json_encode(['status'=>'ok','new_status'=> ($action === 'deactivate' ? 'deactivated' : 'active')]);
} else {
  echo json_encode(['status'=>'error','message'=>'Failed to update status']);
}

?>
