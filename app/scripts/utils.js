// Utilities class for converting 0.0 -> 1.0 values into x,y,z
const TryonUtils = {
    // take coordinates of 0.0 -> 1.0 back into it's actual coords using the canvas size.
    backIntoCoords : (normalized, canvasSize) => {
        const { width, height } = canvasSize;
        return {
            x: Math.round(normalized.x * width),
            y: Math.round(normalized.y * height),
            z: normalized.z  // Z coordinate remains normalized as it represents depth
        };
    }
}; 

class ImageTransparency {
    constructor() {
        this.canvas = document.createElement('canvas');
        this.ctx = this.canvas.getContext('2d');
    }

    makeBackgroundTransparent = async (imageSource, options = {}) => {
        const {
            threshold = 250, // RGB threshold for white detection (0-255)
            tolerance = 5,   // How much variation from white is allowed
            alpha = 0        // Target alpha value (0 = fully transparent)
        } = options;

        return new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = "Anonymous";  // Handle CORS if loading from URL

            img.onload = () => {
                // Set canvas size to match image
                this.canvas.width = img.width;
                this.canvas.height = img.height;

                // Draw image onto canvas
                this.ctx.drawImage(img, 0, 0);

                // Get image data
                const imageData = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
                const data = imageData.data;

                // Process each pixel
                for (let i = 0; i < data.length; i += 4) {
                    const red = data[i];
                    const green = data[i + 1];
                    const blue = data[i + 2];

                    // Check if pixel is close to white
                    if (this.isCloseToWhite(red, green, blue, threshold, tolerance)) {
                        data[i + 3] = alpha; // Set alpha channel
                    }
                }

                // Put processed image data back
                this.ctx.putImageData(imageData, 0, 0);

                // Convert to PNG with transparency
                const transparentImage = this.canvas.toDataURL('image/png');
                resolve(transparentImage);
            };

            img.onerror = reject;

            // Load image
            if (typeof imageSource === 'string') {
                img.src = imageSource;
            } else if (imageSource instanceof File) {
                const reader = new FileReader();
                reader.onload = (e) => img.src = e.target.result;
                reader.onerror = reject;
                reader.readAsDataURL(imageSource);
            } else {
                reject(new Error('Invalid image source'));
            }
        });
    };

    isCloseToWhite = (r, g, b, threshold, tolerance) => {
        return r >= threshold - tolerance &&
               g >= threshold - tolerance &&
               b >= threshold - tolerance;
    };

    // Utility method to apply the transparent image to an element
    applyToElement = async (element, imageSource, options = {}) => {
        try {
            const transparentImage = await this.makeBackgroundTransparent(imageSource, options);
            
            if (element instanceof HTMLImageElement) {
                element.src = transparentImage;
            } else {
                element.style.backgroundImage = `url(${transparentImage})`;
            }
            
            return transparentImage;
        } catch (error) {
            console.error('Error applying transparent image:', error);
            throw error;
        }
    };
}