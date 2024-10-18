<?php

require_once __DIR__ . "/../db.php";

function getIdFromPub($appId) {
    $db = new DB();
    $stmt = $db->createStmt("SELECT id FROM App WHERE public_id = ?");
    $stmt->execute(["$appId"]);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $db->close();

    if (!$row) {
        return -1;
    }

    return $row[0]['id'];
}