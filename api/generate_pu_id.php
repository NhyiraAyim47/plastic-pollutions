<?php
// api/generate_pu_id.php  –  Unique PU Visitor ID Generator
// Format: PU + 6 digits (e.g. PU123456)
// Validated with regex, guaranteed unique in DB

/**
 * Generate a unique PU ID
 * Pattern: PU followed by exactly 6 digits
 * Validates via regex: /^PU\d{6}$/
 */
function generateUniquePUId(PDO $db): string {
    $pattern = '/^PU\d{6}$/';  // Regex pattern for PU ID format

    do {
        $digits = sprintf('%06d', random_int(0, 999999));
        $puId   = 'PU' . $digits;

        // Validate format with regex
        if (!preg_match($pattern, $puId)) {
            continue; // Should never happen, but safety check
        }

        // Check uniqueness in DB
        $stmt = $db->prepare("SELECT id FROM users WHERE pu_id = ? LIMIT 1");
        $stmt->execute([$puId]);
        $exists = $stmt->fetch();

    } while ($exists);

    return $puId;
}

// ─── API Endpoint: Generate and return a PU ID ──────────────────────────────
// Called via AJAX for Task 6 demo
if (basename(__FILE__) === 'generate_pu_id.php' && php_sapi_name() !== 'cli') {
    // Only run as standalone endpoint if directly accessed
    if (!isset($calledAsInclude)) {
        require_once __DIR__ . '/../config/db.php';
        header('Content-Type: application/json');

        $db   = getDB();
        $puId = generateUniquePUId($db);

        echo json_encode([
            'success'   => true,
            'pu_id'     => $puId,
            'pattern'   => 'PU followed by 6 digits',
            'regex'     => '^PU\\d{6}$',
            'validated' => (bool) preg_match('/^PU\d{6}$/', $puId)
        ]);
        exit;
    }
}
