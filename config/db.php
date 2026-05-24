<?php
// config/db.php  –  PDO database connection
// ============================================================
// Edit the constants below to match your hosting environment
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'plasticpollutions');
define('DB_USER', 'root');          // Change to your MySQL username
define('DB_PASS', '');              // Change to your MySQL password
define('DB_CHARSET', 'utf8mb4');

define('SITE_NAME',  'PlasticPollutions');
define('SITE_URL',   'http://localhost/WEBAPP_PlasticPollutions'); // Change to your domain
define('SITE_EMAIL', 'noreply@plasticpollutions.edu.gh');

// SMTP Configuration for PHPMailer (OTP emails)
define('SMTP_HOST',     'smtp.gmail.com');   // e.g. smtp.gmail.com
define('SMTP_PORT',     587);
define('SMTP_USER',     'nhyirahayim@gmail.com');   // Your Gmail or SMTP email
define('SMTP_PASS',     'nrhkjydmmysdrhpd');       // Gmail App Password (not account password)
define('SMTP_FROM',     'nhyirahayim@gmail.com');
define('SMTP_FROM_NAME', 'PlasticPollutions | Pentecost University');

// ============================================================
// PDO Connection (singleton pattern)
// ============================================================
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // In production, log this rather than displaying it
            error_log('DB Connection failed: ' . $e->getMessage());
            http_response_code(503);
            die(json_encode(['success' => false, 'message' => 'Database connection failed. Please try again later.']));
        }
    }
    return $pdo;
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
