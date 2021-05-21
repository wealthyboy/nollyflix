import videojs from "video.js";

let bV = document.querySelector(".background_video");
let bK = document.querySelector(".back");

bV.addEventListener("mouseenter", function(e) {
    bK.style.display = "block";
    bK.style.opacity = "1";
});

bV.addEventListener("mouseleave", function(e) {
    bK.style.display = "none";
    bK.style.opacity = "0";
});

window.onload = event => {
    let bGv = document.querySelector(".background_video");
    bGv.classList.remove("hide");

    const vidjs = videojs("video", {
        html5: {
            hls: {
                overrideNative: !videojs.browser.IS_SAFARI
            }
        },
        autoplay: true,
        nativeControlsForTouch: false
    });

    let p = document.getElementById("play");

    vidjs.ready(function() {
        document.getElementById("video-page-title-pro").classList.add("hide");
        this.muted(false);
        this.playsinline(false);
        if (!this.paused()) {
            console.log("Video is playing");
        } else {
            p.classList.remove("hide");
            let self = this;
            p.onclick = function() {
                self.play();
            };
        }
    });
};
