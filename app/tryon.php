<?php

$json_content = file_get_contents(__DIR__ . "/assets/models.json");
$data = json_decode($json_content, true);

function getModelId($model)
{
    return $model['type'] . "_" . $model['id'];
}

function convertJsonToHtml($json)
{
    $html = "<";
    $children = [];
    foreach ($json as $key => $value) {
        if ($key == "tag") {
            $html .= $value . " ";
            continue;
        }

        if ($key == "html") {
            foreach ($value as $index => $htmlValue) {
                $element = convertJsonToHtml($htmlValue);
                $children[] = $element;
            }
            continue;
        }

        // add attributes
        $html .= " " . $key . "=\"" . $value . "\"";
    }
    // close top tag
    $html .= ">";

    // Add all children
    foreach ($children as $child) {
        $html .= $child;
    }

    // close taag
    $html .= "</" . $json['tag'] . ">";
    return $html;
}

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAR with MindAR and A-Frame</title>
    <!-- Scripts -->
    <script src="/assets/js/aframe.min.js"></script>
    <script src="/assets/js/mindar-aframe.js"></script>
    <script src="/assets/js/jsshare.js"></script>


    <!-- MediaPipe -->
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow: hidden;
            width: 100vw;
            height: 100vh;
        }

        .ar-container {
            width: 100vw;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: Arial, sans-serif;
            z-index: 1000;
        }

        .start-button {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
        }

        #handTrackingCanvas {
            transform: rotateY(180deg);
            -webkit-transform: rotateY(180deg);
            -moz-transform: rotateY(180deg);
        }
    </style>

    <!-- Script for interacting with iframe -->
    <script src="/scripts/tryon.js"></script>
</head>

<body>
    <div id="loading-screen">Loading AR Experience...</div>
    <div id="yeyoCon" class="ar-container">
        <a-scene
            mindar-face
            embedded
            color-space="sRGB"
            renderer="colorManagement: true, physicallyCorrectLights: true"
            vr-mode-ui="enabled: false"
            device-orientation-permission-ui="enabled: false">

            <!-- Camera -->
            <a-camera active="true" position="0 0 0"></a-camera>

            <!-- Assets -->
            <a-assets>
                <a-asset-item
                    id="headModel"
                    src="https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/sparkar/headOccluder.glb">
                </a-asset-item>

                <?php foreach ($data['assets'] as $asset) { ?>
                    <a-asset-item id="asset<?= $asset['id'] ?>" src="<?= $asset['src'] ?>"></a-asset-item>
                <?php } ?>
            </a-assets>

            <?php
            foreach ($data['models'] as $model) {
                // $id = getModelId($model);
                foreach ($model['html'] as $htmlJson) {
                    echo convertJsonToHtml($htmlJson);
                }
            }
            ?>
        </a-scene>
        <video id="webcam" style="position: abso; display: none; " autoplay playsinline></video>
        <canvas id="handTrackingCanvas" style="position: absolute; left: 0px; top: 0px;"></canvas>
    </div>

    <script src="/scripts/tryonutils.js"></script>
    <script src="/scripts/tryon.js"></script>

    <script type="module">
        import {
            HandTracking
        } from "/scripts/tryonbody.js";

        const loadingScreen = document.getElementById('loading-screen');
        const sceneEl = document.querySelector('a-scene');

        window.handTracking = new HandTracking();

        // Hide loading screen when scene is loaded
        sceneEl.addEventListener('loaded', () => {
            loadingScreen.style.display = 'none';
            // handTracking.fill(
            //     document.getElementById("webcam"),
            //     document.getElementById("handTrackingCanvas"),
            //     document.querySelector("#handTrackingCanvas").getContext("2d")
            // );
            handTracking.init();
            setupHandTargets();
        });

        // // Handle start button click
        // startButton.addEventListener('click', () => {
        //     // Start AR experience
        //     const sceneEl = document.querySelector('a-scene');
        //     const camera = document.querySelector('a-camera');
        //     camera.setAttribute('active', true);
        // });

        // Handle AR system ready
        sceneEl.addEventListener('arReady', () => {
            console.log('AR System Ready');
            handTracking.init();
        });

        // Handle AR system error
        sceneEl.addEventListener('arError', () => {
            console.log('AR System Error');
        });

        // Handle target found
        sceneEl.addEventListener('targetFound', () => {
            // console.log('Target Found');
        });

        // Handle target lost
        sceneEl.addEventListener('targetLost', () => {
            // console.log('Target Lost');
        });
    </script>
</body>

</html>