<?php

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../app/getIdFromPub.php";

/**
 * Get the models for an app.
 */
function getModelsForApp($appPublicId) {
    // get app id
    $appId = getIdFromPub($appPublicId);
    if ($appId === -1) {
        return [];
    }

    $db = new DB();
    $sql = "SELECT model_id FROM Model_To_App WHERE app_id = ?";
    $stmt = $db->createStmt($sql);
    $stmt->execute([$appId]);
    $modelIds = $db->parseRow($stmt, []);

    $models = [];

    foreach ($modelIds as $row) {
        $sql = "SELECT * FROM Models WHERE id=?";
        $stmt = $db->createStmt($sql);
        $stmt->execute([$row['model_id']]);
        $res = $db->parseRow($stmt, []);
        if (count($res) > 0) {
            $models[] = $res;
        }
    }

    return $models;
}