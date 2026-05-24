<?php
// api/search.php  –  Search users/records (CRUD: Read/Query)
// Admin-only endpoint

require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success'=>false,'message'=>'Access denied.']);
    exit;
}

$q    = trim(filter_input(INPUT_GET,'q',FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$type = trim(filter_input(INPUT_GET,'type',FILTER_SANITIZE_SPECIAL_CHARS) ?? 'users');

$db = getDB();

if ($type === 'users') {
    if (strlen($q) < 1) { echo json_encode(['success'=>true,'results',[]]); exit; }
    $stmt = $db->prepare("
        SELECT id, pu_id, first_name, last_name, email, role, is_verified, created_at
        FROM users
        WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR pu_id LIKE ?
        ORDER BY created_at DESC LIMIT 20
    ");
    $stmt->execute(["%$q%","%$q%","%$q%","%$q%"]);
    $results = $stmt->fetchAll();
    echo json_encode(['success'=>true,'results'=>$results,'count'=>count($results)]);
    exit;
}

if ($type === 'donations') {
    $stmt = $db->prepare("
        SELECT d.*, u.first_name, u.last_name, u.pu_id
        FROM donations d
        LEFT JOIN users u ON d.user_id = u.id
        WHERE d.reference LIKE ? OR d.campaign LIKE ? OR u.email LIKE ?
        ORDER BY d.donated_at DESC LIMIT 20
    ");
    $stmt->execute(["%$q%","%$q%","%$q%"]);
    $results = $stmt->fetchAll();
    echo json_encode(['success'=>true,'results'=>$results,'count'=>count($results)]);
    exit;
}

echo json_encode(['success'=>false,'message'=>'Unknown search type.']);
