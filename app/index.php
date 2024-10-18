<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rakesh Try on (((Demo)))</title>

    <!-- Import important scripts -->
    <!-- Aframe for 3D models -->
    <script src="/assets/js/aframe.min.js"></script>
    <!-- MindAR for Basic AR -->
    <script src="/assets/js/mindar-aframe.js"></script>
    <!-- JShare for sharing pics -->
    <script src="/assets/js/jsshare.js"></script>
    
    <!-- In house custom script -->
    <script src="/scripts/main.js"></script>
</head>

<body>
    <div id="settings">
        <!-- Settings buttons go here -->
    </div>
    <div id="arScreen">
        <!-- Aframe goes here -->
        <a-scene mindar-face embedded color-space="sRGB" renderer="colorManagement: true, physicallyCorrectLights"
            xr-mode-ui="enabled: false" device-orientation-permission-ui="enabled: false"> <!-- This is going to add a camera by default -->
            <a-assets id="aAssets">
                <!-- Add a head occuluder by default -->
                <a-asset-item id="headModel"
                    src="https://cdn.jsdelivr.net/gh/hiukim/mind-ar-js@1.2.5/examples/face-tracking/assets/sparkar/headOccluder.glb">
                </a-asset-item>
            </a-assets>

            <!-- Add the 3D camera -->
            <a-camera active="true" position="0 0 0"></a-camera>

            <!-- This is where elements would GO. -->
            <!-- There is only one default element, the occluder -->
            <!-- It uses mindar which means it needs a anchorIndex... -->
            <a-entity mindar-face-target="anchorIndex: 168">
                <a-gltf-model mindar-face-occluder position="0 -0.3 0.15" rotation="0 0 0" scale="0.065 0.065 0.065"
                    src="#headModel"></a-gltf-model>
            </a-entity>
        </a-scene>

        <!-- This is the canvas for the mediapipe (should not be seen by the end user...) -->
        <div class="landmark-grid-container"></div>
    </div>
    <div id="modelOptions"> <!-- Sometimes is on bottom (mobile) -->
        <!-- Options go here... -->
    </div>

    <script>
        setTimeout(() => {
            start();
        }, 1000)
    </script>
</body>

</html>