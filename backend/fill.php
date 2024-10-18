<?php

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/db.php";

// Initialize the Database
$db = new DB();

$db->execute("DROP TABLE IF EXISTS Models; DROP TABLE IF EXISTS App; DROP TABLE IF EXISTS Model_To_App");

require_once __DIR__ . "/init.php";

// insert a admin
$db->execute("
    INSERT INTO Admins (id, username, password, email) VALUES 
    (1, 'admin1', 'password123', 'admin@example.com')
");

// id INTEGER PRIMARY KEY AUTOINCREMENT,
// src TEXT NOT NULL,
// english_id TEXT NOT NULL

$db->execute("
    INSERT INTO Assets (id, src, english_id) VALUES 
    (1, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/glasses/scene.gltf', 'Pixel Glasses'),
    (2, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/earring/scene.gltf', 'Earring')
");

//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/glasses/scene.gltf', 'Pixel Glasses', '0 0 0', '0.01 0.01 0.01', '0 0 0', 168, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/glasses/thumbnail.png'),
//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/hat2/scene.gltf', 'Mario Hat', '0 -0.2 -0.65', '0.008 0.008 0.008', '0 0 0', 10, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/hat2/thumbnail.png'),
//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/earring/scene.gltf', 'Earring Left Model', '0 -0.3 -0.3', '0.05 0.05 0.05', '-0.1 0 0', 127, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/earring/thumbnail.png'),
//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/earring/scene.gltf', 'Earring Right Model', '0 -0.3 -0.3', '0.05 0.05 0.05', '0.1 0 0', 356, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/earring/thumbnail.png'),
//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/hat/scene.gltf', 'Circus Hat Model', '0 1.0 -0.5', '0.35 0.35 0.35', '0 0 0', 10, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/hat/thumbnail.png'),
//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/glasses2/scene.gltf', 'Sunglasses Model', '0 -0.3 0', '0.6 0.6 0.6', '0 -90 0', 168, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/glasses2/thumbnail.png')
//     INSERT INTO Models (path, name, position, scale, rotation, default_anchorIndex, image_path) VALUES


// id INTEGER PRIMARY KEY AUTOINCREMENT,
// tag TEXT NOT NULL,
// arguments JSON,
// asset INTEGER,
// image_path TEXT

$db->execute("
    INSERT INTO Elements (id, tag, arguments, asset, image_path) VALUES 
    (1, 'a-gltf-model', 'rotation=\"0 0 0\" position=\"0 0 0\" scale=\"0.01 0.01 0.01\" mindar-face-target=\"anchorIndex: 168\"', 1, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/glasses/thumbnail.png'),
    (2, 'a-gltf-model', 'rotation=\"\" position=\"\" scale=\"\" mindar-face-target=\"anchorIndex: 127|356\", 2, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/earring/thumbnail.png')
");

//         id INTEGER PRIMARY KEY AUTOINCREMENT,
//         title TEXT NOT NULL

$db->execute("
    INSERT INTO Categories (id, title) VALUES (
        1, 'earrings',
        2, 'necklace'
    )
");

// id INTEGER PRIMARY KEY AUTOINCREMENT,
// secret TEXT NOT NULL,
// public_id TEXT NOT NULL,
// name TEXT NOT NULL,
// admin_id INTEGER NOT NULL

$db->execute("
    INSERT INTO Collections (id, secret, public_id, name, admin_id) VALUES (
        1, 'secret_key', 'public_key', 'first try on', 1
    )
");

// CREATE TABLE IF NOT EXISTS Category_To_Element (
//     id INTEGER PRIMARY KEY AUTOINCREMENT,
//     category_id INTEGER NOT NULL,
//     element_id INTEGER NOT NULL
// )

$db->execute("
    INSERT INTO Category_To_Element (id, category_id, element_id) VALUES ()
");

// CREATE TABLE IF NOT EXISTS Collection_To_Category (
//     id INTEGER PRIMARY KEY AUTOINCREMENT,
//     collection_id INTEGER NOT NULL,
//     category_id INTEGER NOT NULL
// )

$db->execute("
    INSERT INTO Collection_To_Category (id, collection_id, category_id) VALUES ()
");

// // Insert data into Models table (path is an empty string for now)
// $db->execute("
//     INSERT INTO Models (path, name, position, scale, rotation, default_anchorIndex, image_path) VALUES
//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/glasses/scene.gltf', 'Pixel Glasses', '0 0 0', '0.01 0.01 0.01', '0 0 0', 168, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/glasses/thumbnail.png'),
//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/hat2/scene.gltf', 'Mario Hat', '0 -0.2 -0.65', '0.008 0.008 0.008', '0 0 0', 10, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/hat2/thumbnail.png'),
//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/earring/scene.gltf', 'Earring Left Model', '0 -0.3 -0.3', '0.05 0.05 0.05', '-0.1 0 0', 127, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/earring/thumbnail.png'),
//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/earring/scene.gltf', 'Earring Right Model', '0 -0.3 -0.3', '0.05 0.05 0.05', '0.1 0 0', 356, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/earring/thumbnail.png'),
//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/hat/scene.gltf', 'Circus Hat Model', '0 1.0 -0.5', '0.35 0.35 0.35', '0 0 0', 10, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/hat/thumbnail.png'),
//     ('https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/glasses2/scene.gltf', 'Sunglasses Model', '0 -0.3 0', '0.6 0.6 0.6', '0 -90 0', 168, 'https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/glasses2/thumbnail.png')
// ");

// // Insert data into App table
// $db->execute("
//     INSERT INTO App (secret, public_id) VALUES
//     ('secretKey123', 'pid')
// ");

// // Insert data into Model_To_App table
// $db->execute("
//     INSERT INTO Model_To_App (app_id, model_id) VALUES
//     (1, 1), -- Associates app_id 1 with model_id 1 (Glasses)
//     (1, 2), -- Associates app_id 1 with model_id 2 (Hat)
//     (1, 3), -- Associates app_id 2 with model_id 3 (Earring)
//     (1, 4),  -- Associates app_id 3 with model_id 1 (Glasses)
//     (1, 5),
//     (1, 6)
// ");

echo "Filler data inserted successfully!";

?>
