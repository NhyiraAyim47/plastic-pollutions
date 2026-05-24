<?php
// auth/logout.php
require_once __DIR__ . '/../config/db.php';

if (isset($_SESSION['user_id'])) {
    $db = getDB();
    $db->prepare("INSERT INTO activity_log (user_id, action, ip_address) VALUES (?, 'Logged out', ?)")
       ->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR'] ?? '']);
}

session_destroy();
header('Location: ' . SITE_URL . '/pages/home.php');
exit;
