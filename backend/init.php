<?php

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/db.php";

// intialize the Database
$db = new DB();

$db->execute("
    CREATE TABLE IF NOT EXISTS Admins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        password TEXT NOT NULL,
        email TEXT NOT NULL
    )
");

$db->execute("
    CREATE TABLE IF NOT EXISTS Elements (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        tag TEXT NOT NULL,
        arguments JSON,
        asset INTEGER,
        image_path TEXT
    )
");

$db->execute("
    CREATE TABLE IF NOT EXISTS Assets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        src TEXT NOT NULL,
        english_id TEXT NOT NULL
    )
");

$db->execute("
    CREATE TABLE IF NOT EXISTS Categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL
    )
");

$db->execute("
    CREATE TABLE IF NOT EXISTS Collections (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        secret TEXT NOT NULL,
        public_id TEXT NOT NULL,
        name TEXT NOT NULL,
        admin_id INTEGER NOT NULL
    )
");

$db->execute("
    CREATE TABLE IF NOT EXISTS Category_To_Element (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category_id INTEGER NOT NULL,
        element_id INTEGER NOT NULL
    )
");

$db->execute("
    CREATE TABLE IF NOT EXISTS Collection_To_Category (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        collection_id INTEGER NOT NULL,
        category_id INTEGER NOT NULL
    )
");


echo "All table should have been created...";

// All tables should have been created.