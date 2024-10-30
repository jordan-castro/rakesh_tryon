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
        this.canvas.width = this.mindarVideo.style.width.split("px")[0];
        this.canvas.height = this.mindarVideo.style.height.split("px")[0];
        this.canvas.style.position = this.mindarVideo.style.position;
        this.canvas.style.top = this.mindarVideo.style.top;
        this.canvas.style.left = this.mindarVideo.style.left;
        // this.canvas.style.width = this.mindarVideo.clientWidth;
        // this.canvas.style.height = this.mindarVideo.clientHeight;
        // this.canvas.width = this.mindarVideo.clientWidth;
        // this.canvas.height = this.mindarVideo.clientHeight;  

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

    getHandLandmarks = () => {
        if (!this.results?.landmarks) {
            return {
                numberOfHands: 0,
                landmarks: []
            };
        }

        return {
            numberOfHands: this.results.landmarks.length,
            landmarks: this.results.landmarks.map((handLandmarks, index) => ({
                handIndex: index,
                points: handLandmarks.map((point, pointIndex) => ({
                    x: point.x,
                    y: point.y,
                    z: point.z,
                    index: pointIndex
                }))
            }))
        };
    };

    // Utility method to convert all hand landmarks to pixel coordinates
    getHandLandmarksInPixels = () => {
        const handData = this.getHandLandmarks();
        const canvasSize = {
            width: this.canvas.width,
            height: this.canvas.height
        };

        return {
            numberOfHands: handData.numberOfHands,
            landmarks: handData.landmarks.map(hand => ({
                handIndex: hand.handIndex,
                points: hand.points.map(point => 
                    TryonUtils.backIntoCoords(point, canvasSize)
                )
            }))
        };
    };
    
}

export { HandTracking };