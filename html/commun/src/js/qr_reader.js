function qr_reader(id, debug = false) {
    $("#" + id).after("<a id=\"" + id + "_jsqr\" class=\"btn btn-secondary\"> <span class=\"glyphicon glyphicon-qrcode\"><span class=\"visually-hidden\">qrcode</span></span></a>");
    video = document.createElement("video")
    video.id = id + "_video";
    canvasElement = document.createElement("canvas");
    canvasElement.id = id + "_canvas";
    canvas = canvasElement.getContext("2d");
    if (debug) {
        $("main").append(video);
    }
    function tick() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth;
            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
            imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
            code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "dontInvert",
            });
            if (code && code.data != "") {
                ($("#" + id).prop("tagName") == "INPUT" ? $("#" + id).val("" + code.data) : $("#" + id).text("" + code.data));
            }
        }
        requestAnimationFrame(tick);
    }
    $("#" + id + "_jsqr").click(function () {
        navigator.mediaDevices.getUserMedia({audio: false, video: {facingMode: "environment"}}).then(function (stream) {
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            requestAnimationFrame(tick);
        });
    });
}