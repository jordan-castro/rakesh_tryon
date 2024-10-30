// This file consists of methods that are used to interact with tryon.php iframe.

function getModelId(model) {
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
    // get hand positions.
    const landmarks = window.handTracking.getHandLandmarksInPixels().landmarks;
    if (landmarks.length == 0) {
        return;
    }
    // find index
    let landmark = landmarks[0].points[index];

    // set element to that position.
    element.setAttribute("position", `${landmark.x} ${landmark.y} ${landmark.z}`);
}

// This helps us work with our aframe mediapipe objects.
// I want to get all elements that have the attribute "mediapipe-hand-target"
function setupHandTargets() {
    const handTargets = document.querySelectorAll("[mediapipe-hand-target]");
    const transparencyGen = new ImageTransparency();
    
    for (let el of handTargets) {
        const child = el.children[0];
        // check for "transparent-background" attribute
        if (child.getAttribute("tsp-bckg") !== null) {
            // make the bck transparent <-- only works for white.
            transparencyGen.makeBackgroundTransparent(child.getAttribute("src")).then((val) => {
                child.setAttribute("src", val);
            }).catch((e) => {
                console.error("ERROR: " + e);
            });
        }

        // here we want to set a listener to follow the position of the hands.
        setInterval(() => {
            followHand(el.getAttribute("mediapipe-hand-target"), child);
        }, 10);
    }
}