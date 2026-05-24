<?php
// dashboard/delete_account.php  –  Permanently delete user account (CRUD: Delete)

require_once __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: ' . SITE_URL . '/auth/login.php'); exit; }

$userId = (int)$_SESSION['user_id'];
$db     = getDB();

// Hard delete user (cascades to donations, activity_log via FK)
$db->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);

session_destroy();
header('Location: ' . SITE_URL . '/pages/home.php?deleted=1');
exit;
