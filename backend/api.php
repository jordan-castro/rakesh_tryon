<?php

require __DIR__ . "/../config.php";
require_once __DIR__ . "/utils/app_exists.php";

if (!array_key_exists('fn', $_GET) or !array_key_exists('app_id', $_GET) or !array_key_exists('app_key', $_GET)) {
    echo json_encode(["version" => API_VERSION]);
    exit(1);
}


$fn = $_GET['fn'];
$appId = $_GET['app_id'];
$appKey = $_GET['app_key'];

if (!appExists($appId, $appKey)) {
    echo json_encode(["version" => API_VERSION]);
    exit(1);
}

if ($fn === "getModelsForApp") {
    require_once __DIR__ . "/models/getModelsForApp.php";
    echo json_encode(getModelsForApp($appId));
}