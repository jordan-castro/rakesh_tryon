// This file consists of methods that are used to interact with tryon.php iframe.

function getModelId(model) {
}

function setupAll() {
    // get any elements with class "tsp-bckg"
    const elements = document.getElementsByClassName("tsp-bckg");

    if (elements.length == 0) {
        return;
    }

    const transparencyGen = new ImageTransparency();
    for (let element of elements) {
        transparencyGen.makeBackgroundTransparent(element.getAttribute("src")).then((val) => {
            element.setAttribute("src", val);
        }).catch((e) => {
            console.error('ERROR: ' + e);
        });
    }
}

function toggleModel(modelId) {
    let element = document.getElementById(modelId);
    if (element.getAttribute("visible") == "true") {
        element.setAttribute("visible", "false");
    } else {
        element.setAttribute("visible", "true");
    }
}

function followHand(index, element) {
    // Get hand positions
    const landmarks = window.handTracking.getHandLandmarksInPixels().landmarks;
    if (landmarks.length === 0) {
        return;
    }

    // get rotation
    const rotation = TryonUtils.calculateHandRotation(landmarks[0].points);

    // Find the landmark by index
    let landmark = landmarks[0].points[index];

    // get pos
    const position = TryonUtils.calculateHandPosition(landmark, document.querySelector("a-scene").canvas);

    console.log(position.z);

    // Set the A-Frame element's position
    element.object3D.position.set(position.x, position.y, position.z);
    element.object3D.rotation.set(rotation.x, rotation.y, rotation.z);
}

// This helps us work with our aframe mediapipe objects.
// I want to get all elements that have the attribute "mediapipe-hand-target"
function setupHandTargets() {
    const handTargets = document.querySelectorAll("[mediapipe-hand-target]");

    for (let el of handTargets) {
        let child = el;
        if (el.children.length == 1) {
            child = el.children[0];
        }


        followHand(el.getAttribute("mediapipe-hand-target"), child);

        // here we want to set a listener to follow the position of the hands.
        setInterval(() => {
            followHand(el.getAttribute("mediapipe-hand-target"), child);
        }, 1);
    }
}