<?php
// api/visitor_count.php  –  Returns visitor count as JSON

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/visitor_counter.php';

header('Content-Type: application/json');

echo json_encode([
    'total' => getTotalVisitorCount(),
    'today' => getDailyVisitorCount(),
]);
