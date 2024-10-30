// This script runs in the body of tryon.php
// mediapipe stuff.
import { HandLandmarker, FilesetResolver } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0";

class HandTracking {
    constructor() {
        this.handLandmarker = undefined;
        this.results = undefined;
        this.video = undefined;
        this.mindarVideo = undefined;
        this.mindarCanvas = undefined;
        this.canvas = undefined;
        this.ctx = undefined;
        this.yeyoCon = undefined;
        this.lastVideoTime = -1;
    }

    init = async () => {
        this.video = document.getElementById("webcam");
        this.canvas = document.getElementById("handTrackingCanvas");
        this.ctx = this.canvas.getContext("2d");
        this.mindarCanvas = document.getElementsByTagName("canvas")[0];
        this.mindarVideo = document.getElementsByTagName("video")[1];
        this.yeyoCon = document.getElementById("yeyoCon");

        if (!this.handLandmarker) {
            // create
            await this.createHandLandmarker();
            return;
        }

        // start
        this.start();
    };

    // fill = (video, canvas, ctx) => {
    //     this.video = video;
    //     this.canvas = canvas;
    //     this.ctx = ctx;
    // };

    start = () => {
        const hasGetUserMedia = () => !!navigator.mediaDevices?.getUserMedia;
        if (hasGetUserMedia()) {
            const constraints = {
                video: true
            };

            navigator.mediaDevices.getUserMedia(constraints).then((stream) => {
                this.video.srcObject = stream;
                this.video.addEventListener("loadeddata", this.predictWebcam);
            })
            this.lastVideoTime = -1;
        } else {
            console.warn("getUserMedia() is not supported by your browser");
        }
    };

    predictWebcam = async () => {
        this.canvas.style.width = this.mindarVideo.clientWidth;
        this.canvas.style.height = this.mindarVideo.clientHeight;
        this.canvas.width = this.mindarVideo.clientWidth;
        this.canvas.height = this.mindarVideo.clientHeight;  

        let startTimeMs = performance.now();
        if (this.lastVideoTime !== this.video.currentTime) {
            this.lastVideoTime = this.video.currentTime;
            this.results = await this.handLandmarker.detectForVideo(this.video, startTimeMs);
        }

        this.ctx.save();
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        if (this.results?.landmarks) {
            for (const landmarks of this.results.landmarks) {
                drawConnectors(this.ctx, landmarks, HAND_CONNECTIONS, {
                    color: "#00FF00",
                    lineWidth: 5
                });
                drawLandmarks(this.ctx, landmarks, { color: "#FF0000", lineWidth: 2 });
            }
        }
        this.ctx.restore();
        window.requestAnimationFrame(this.predictWebcam);
    };

    createHandLandmarker = async () => {
        const vision = await FilesetResolver.forVisionTasks(
            "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0/wasm"
        );
        this.handLandmarker = await HandLandmarker.createFromOptions(vision, {
            baseOptions: {
                modelAssetPath: `https://storage.googleapis.com/mediapipe-models/hand_landmarker/hand_landmarker/float16/1/hand_landmarker.task`,
                delegate: "GPU"
            },
            runningMode: "VIDEO",
            numHands: 2
        });
    };
}

// const createHandLandmarker = async () => {
//     const vision = await FilesetResolver.forVisionTasks(
//         "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0/wasm"
//     );
//     handLandmarker = await HandLandmarker.createFromOptions(vision, {
//         baseOptions: {
//             modelAssetPath: `https://storage.googleapis.com/mediapipe-models/hand_landmarker/hand_landmarker/float16/1/hand_landmarker.task`,
//             delegate: "GPU"
//         },
//         runningMode: "VIDEO",
//         numHands: 2
//     });
// };

// createHandLandmarker();

// const hasGetUserMedia = () => !!navigator.mediaDevices?.getUserMedia;
// if (hasGetUserMedia()) {

//     const constraints = {
//         video: true
//     };

//     navigator.mediaDevices.getUserMedia(constraints).then((stream) => {
//         video.srcObject = stream;
//         video.addEventListener("loadeddata", predictWebcam);
//     })
//     let lastVideoTime = -1;
//     let results = undefined;
//     console.log(video);
//     async function predictWebcam() {
//         canvas.style.width = video.videoWidth;;
//         canvas.style.height = video.videoHeight;
//         canvas.width = video.videoWidth;
//         canvas.height = video.videoHeight;

//         // // Now let's start detecting the stream.
//         // if (runningMode === "IMAGE") {
//         //     runningMode = "VIDEO";
//         //     await handLandmarker.setOptions({ runningMode: "VIDEO" });
//         // }
//         let startTimeMs = performance.now();
//         if (lastVideoTime !== video.currentTime) {
//             lastVideoTime = video.currentTime;
//             results = handLandmarker.detectForVideo(video, startTimeMs);
//         }
//         ctx.save();
//         ctx.clearRect(0, 0, canvasElement.width, canvasElement.height);
//         if (results.landmarks) {
//             for (const landmarks of results.landmarks) {
//                 drawConnectors(ctx, landmarks, HAND_CONNECTIONS, {
//                     color: "#00FF00",
//                     lineWidth: 5
//                 });
//                 drawLandmarks(ctx, landmarks, { color: "#FF0000", lineWidth: 2 });
//             }
//         }
//         ctx.restore();
//         window.requestAnimationFrame(predictWebcam);
//     }
// } else {
//     console.warn("getUserMedia() is not supported by your browser");
// }

export { HandTracking };