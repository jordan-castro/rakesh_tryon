function capture() {
    const video = document.querySelector("video");

    const canvas = document.createElement("canvas");
    video.pause();

    let v_width = video.clientWidth*2;
    let v_height = video.clientHeight*2;
    
    canvas.width = v_width;
    canvas.height = v_height;

    let element = document.querySelector('video'),
        style = window.getComputedStyle(element),
        top = style.getPropertyValue('top');

    canvas.getContext('2d').drawImage(video, 0, parseFloat(top), v_width, v_height);

    let imgData = document.querySelector('a-scene').components.screenshot.getCanvas('perspective');

    canvas.getContext('2d')
        .drawImage(imgData, 0, 0, v_width, v_height);

    const link = document.createElement('a');
    link.download = "test.png";
    link.href = canvas.toDataURL();
    link.click();
    
    video.play();
}
