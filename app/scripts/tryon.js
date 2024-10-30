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