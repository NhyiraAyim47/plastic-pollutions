<?php
// index.php  –  Entry point, redirect to home page
require_once __DIR__ . '/config/db.php';
header('Location: ' . SITE_URL . '/pages/home.php');
exit;
