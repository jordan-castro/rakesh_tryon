let sceneEl, messageBox;

window.addEventListener("load", function () {
  start();
});

async function start() {
}

function takeImage() {
  let arCanvas = document.getElementsByTagName("canvas");
  if (arCanvas.length > 1 || arCanvas.length == 0) {
    // umm?
    console.log("Error: arCanvas is not found or there is more than 1 canvas on the doc.");
    return;
  }
  const cv = arCanvas.item(0);

  // save the image
  const link = document.createElement('a');
  link.download = 'tryon.png';
  link.href = cv.toDataURL();
  link.click();
}
