<?php

require_once __DIR__ . "/../db.php";

/**
 * Check if app exists.
 */
function appExists($appPublicId, $appSecret) {
    $db = new DB();

    $stmt = $db->pdo->prepare("SELECT * FROM App WHERE secret = ? AND public_id = ?");
    $stmt->execute([$appSecret, $appPublicId]);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$row) {
        return false;
    }

    return count($row) > 0;
}