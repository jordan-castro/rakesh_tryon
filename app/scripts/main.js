function start() {
    // tryon args
    window.tryOn = {
        videoElement: null,
        canvasElement: null,
        canvasCtx: null,
        landmarkContainer: document.getElementsByClassName('landmark-grid-container')[0],
    }
    window.tryOn.grid = new LandmarkGrid(window.tryOn.landmarkContainer);
    
    window.tryOn.videoElement = document.querySelector("video");
    window.tryOn.canvasElement = document.querySelector("canvas");
    window.tryOn.canvasCtx = window.tryOn.canvasElement.getContext("2d");
    
    const pose = new Pose({locateFile: (file) => {
        return `https://cdn.jsdelivr.net/npm/@mediapipe/pose/${file}`;
      }});
      pose.setOptions({
        modelComplexity: 1,
        smoothLandmarks: true,
        enableSegmentation: true,
        smoothSegmentation: true,
        minDetectionConfidence: 0.5,
        minTrackingConfidence: 0.5
      });
      pose.onResults(onResults);
      
      const camera = new Camera(window.tryOn.videoElement, {
        onFrame: async () => {
          await pose.send({image: window.tryOn.videoElement});
        },
        width: 1280,
        height: 720
      });
      camera.start();
      
}

function onResults(results) {
  if (!results.poseLandmarks) {
    window.tryOn.grid.updateLandmarks([]);
    return;
  }

  const canvasCtx = window.tryOn.canvasCtx;
  const canvasElement = window.tryOn.canvasElement;

  canvasCtx.save();
  canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
  canvasCtx.drawImage(results.segmentationMask, 0, 0,
                      canvasElement.width, canvasElement.height);

  // Only overwrite existing pixels.
  canvasCtx.globalCompositeOperation = 'source-in';
  canvasCtx.fillStyle = '#00FF00';
  canvasCtx.fillRect(0, 0, canvasElement.width, canvasElement.height);

  // Only overwrite missing pixels.
  canvasCtx.globalCompositeOperation = 'destination-atop';
  canvasCtx.drawImage(
      results.image, 0, 0, canvasElement.width, canvasElement.height);

  canvasCtx.globalCompositeOperation = 'source-over';
  drawConnectors(canvasCtx, results.poseLandmarks, POSE_CONNECTIONS,
                 {color: '#00FF00', lineWidth: 4});
  drawLandmarks(canvasCtx, results.poseLandmarks,
                {color: '#FF0000', lineWidth: 2});
  canvasCtx.restore();

  window.tryOn.grid.updateLandmarks(results.poseWorldLandmarks);
}


// // Add a easy to use global access poing
// window.tryOn = {
//     isTracking: false,
// };

// // This function starts the tacking...
// function startTracking() {
//     insertTrackers();
//     window.tryOn.isTracking = true;
// }

// // This function inserts all needed variables
// function insertTrackers() {
//     const videoElement = getVideo();
//     const poseCanvas = getGoogleShit("Pose");
//     const canvasCtx = poseCanvas.getContext('2d');
//     const landmarkContainer = document.getElementsByClassName("landmark-grid-container")[0];

//     const grid = new LandmarkGrid(landmarkContainer);
// }

// // get the video element easily
// function getVideo() {
//     return document.querySelector("video");
// }

// // get a google shit mediapipe canvas
// function getGoogleShit(id) {
//     return document.getElementById(`googleShit${id}`);
// }