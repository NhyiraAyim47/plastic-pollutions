<?php
// includes/visitor_counter.php  –  Track and retrieve visitor count

function trackVisitor(string $page = '/'): void {
    $db = getDB();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    // Only count each IP once per hour per page to avoid spam
    $stmt = $db->prepare("
        SELECT id FROM visitors
        WHERE ip_address = ? AND page = ? AND visited_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
        LIMIT 1
    ");
    $stmt->execute([$ip, $page]);
    if (!$stmt->fetch()) {
        $stmt2 = $db->prepare("INSERT INTO visitors (ip_address, page) VALUES (?, ?)");
        $stmt2->execute([$ip, $page]);
    }
}

function getTotalVisitorCount(): int {
    $db = getDB();
    $stmt = $db->query("SELECT COUNT(DISTINCT ip_address) AS cnt FROM visitors");
    $row = $stmt->fetch();
    return (int)($row['cnt'] ?? 0);
}

function getDailyVisitorCount(): int {
    $db = getDB();
    $stmt = $db->query("SELECT COUNT(DISTINCT ip_address) AS cnt FROM visitors WHERE DATE(visited_at) = CURDATE()");
    $row = $stmt->fetch();
    return (int)($row['cnt'] ?? 0);
}
