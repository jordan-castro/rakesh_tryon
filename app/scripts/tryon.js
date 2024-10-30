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

// This helps us work with our aframe mediapipe objects.
// I want to get all elements that have the attribute "mediapipe-hand-target"
function setupHandTargets() {
    const handTargets = document.querySelectorAll("[mediapipe-hand-target]");
    const transparencyGen = new ImageTransparency();
    
    for (let el of handTargets) {
        // check for "transparent-background" attribute
        if (el.attributes.getNamedItem("tsp-bckg") !== null) {
            // make the bck transparent <-- only works for white.
            transparencyGen.makeBackgroundTransparent(el.getAttribute("src")).then((val) => {
                el.setAttribute("src", val);
            }).catch((e) => {
                console.error("ERROR: " + e);
            });
        }

        // here we want to set a listener to follow the position of the hands.
    }
}