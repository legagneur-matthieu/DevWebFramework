$animate_called = false;
function animate(id) {
    if (!$animate_called) {
        $("head").append('<link rel="stylesheet" href="../commun/src/js/animate/animate.css" />');
        $animate_called = true;
    }
    var _id = "#" + id;
    this.class = [
        //Attention seekers
        "bounce",
        "flash",
        "pulse",
        "rubberBand",
        "shakeX",
        "shakeY",
        "headShake",
        "swing",
        "tada",
        "wobble",
        "jello",
        "heartBeat",
        //Back entrances
        "backInDown",
        "backInLeft",
        "backInRight",
        "backInUp",
        //Back exits
        "backOutDown",
        "backOutLeft",
        "backOutRight",
        "backOutUp",
        //Bouncing entrances
        "bounceIn",
        "bounceInDown",
        "bounceInLeft",
        "bounceInRight",
        "bounceInUp",
        //Bouncing exits
        "bounceOut",
        "bounceOutDown",
        "bounceOutLeft",
        "bounceOutRight",
        "bounceOutUp",
        //Fading entrances
        "fadeIn",
        "fadeInDown",
        "fadeInDownBig",
        "fadeInLeft",
        "fadeInLeftBig",
        "fadeInRight",
        "fadeInRightBig",
        "fadeInUp",
        "fadeInUpBig",
        "fadeInTopLeft",
        "fadeInTopRight",
        "fadeInBottomLeft",
        "fadeInBottomRight",
        //Fading exits
        "fadeOut",
        "fadeOutDown",
        "fadeOutDownBig",
        "fadeOutLeft",
        "fadeOutLeftBig",
        "fadeOutRight",
        "fadeOutRightBig",
        "fadeOutUp",
        "fadeOutUpBig",
        "fadeOutTopLeft",
        "fadeOutTopRight",
        "fadeOutBottomRight",
        "fadeOutBottomLeft",
        //Flippers
        "flip",
        "flipInX",
        "flipInY",
        "flipOutX",
        "flipOutY",
        //Lightspeed
        "lightSpeedInRight",
        "lightSpeedInLeft",
        "lightSpeedOutRight",
        "lightSpeedOutLeft",
        //Rotating entrances
        "rotateIn",
        "rotateInDownLeft",
        "rotateInDownRight",
        "rotateInUpLeft",
        "rotateInUpRight",
        //Rotating exits
        "rotateOut",
        "rotateOutDownLeft",
        "rotateOutDownRight",
        "rotateOutUpLeft",
        "rotateOutUpRight",
        //Specials
        "hinge",
        "jackInTheBox",
        "rollIn",
        "rollOut",
        //Zooming entrances
        "zoomIn",
        "zoomInDown",
        "zoomInLeft",
        "zoomInRight",
        "zoomInUp",
        //Zooming exits
        "zoomOut",
        "zoomOutDown",
        "zoomOutLeft",
        "zoomOutRight",
        "zoomOutUp",
        //Sliding entrances
        "slideInDown",
        "slideInLeft",
        "slideInRight",
        "slideInUp",
        //Sliding exits
        "slideOutDown",
        "slideOutLeft",
        "slideOutRight",
        "slideOutUp"
    ];
    this.utility = [
        //Utility classes
        "delay-2s",
        "delay-3s",
        "delay-4s",
        "delay-5s",
        "slow",
        "slower",
        "fast",
        "faster",
        "repeat-1",
        "repeat-2",
        "repeat-3",
        "infinite"
    ];
    $(_id).addClass("animate__animated");
    this.rm_animate = function () {
        $.each(this.class, function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this;
    };
    this.anim = function (name) {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("animate__" + name);
        }, 10);
        return this;
    };
    this.bounce = function () {
        return this.anim("bounce");
    };
    this.flash = function () {
        return this.anim("flash");
    };
    this.pulse = function () {
        return this.anim("pulse");
    };
    this.rubberBand = function () {
        return this.anim("rubberBand");
    };
    this.shakeX = function () {
        return this.anim("shakeX");
    };
    this.shakeY = function () {
        return this.anim("shakeY");
    };
    this.headShake = function () {
        return this.anim("headShake");
    };
    this.swing = function () {
        return this.anim("swing");
    };
    this.tada = function () {
        return this.anim("tada");
    };
    this.wobble = function () {
        return this.anim("wobble");
    };
    this.jello = function () {
        return this.anim("jello");
    };
    this.heartBeat = function () {
        return this.anim("heartBeat");
    };
    this.backInDown = function () {
        return this.anim("backInDown");
    };
    this.backInLeft = function () {
        return this.anim("backInLeft");
    };
    this.backInRight = function () {
        return this.anim("backInRight");
    };
    this.backInUp = function () {
        return this.anim("backInUp");
    };
    this.backOutDown = function () {
        return this.anim("backOutDown");
    };
    this.backOutLeft = function () {
        return this.anim("backOutLeft");
    };
    this.backOutRight = function () {
        return this.anim("backOutRight");
    };
    this.backOutUp = function () {
        return this.anim("backOutUp");
    };
    this.bounceIn = function () {
        return this.anim("bounceIn");
    };
    this.bounceInDown = function () {
        return this.anim("bounceInDown");
    };
    this.bounceInLeft = function () {
        return this.anim("bounceInLeft");
    };
    this.bounceInRight = function () {
        return this.anim("bounceInRight");
    };
    this.bounceInUp = function () {
        return this.anim("bounceInUp");
    };
    this.bounceOut = function () {
        return this.anim("bounceOut");
    };
    this.bounceOutDown = function () {
        return this.anim("bounceOutDown");
    };
    this.bounceOutLeft = function () {
        return this.anim("bounceOutLeft");
    };
    this.bounceOutRight = function () {
        return this.anim("bounceOutRight");
    };
    this.bounceOutUp = function () {
        return this.anim("bounceOutUp");
    };
    this.fadeIn = function () {
        return this.anim("fadeIn");
    };
    this.fadeInDown = function () {
        return this.anim("fadeInDown");
    };
    this.fadeInDownBig = function () {
        return this.anim("fadeInDownBig");
    };
    this.fadeInLeft = function () {
        return this.anim("fadeInLeft");
    };
    this.fadeInLeftBig = function () {
        return this.anim("fadeInLeftBig");
    };
    this.fadeInRight = function () {
        return this.anim("fadeInRight");
    };
    this.fadeInRightBig = function () {
        return this.anim("fadeInRightBig");
    };
    this.fadeInUp = function () {
        return this.anim("fadeInUp");
    };
    this.fadeInUpBig = function () {
        return this.anim("fadeInUpBig");
    };
    this.fadeInTopLeft = function () {
        return this.anim("fadeInTopLeft");
    };
    this.fadeInTopRight = function () {
        return this.anim("fadeInTopRight");
    };
    this.fadeInBottomLeft = function () {
        return this.anim("fadeInBottomLeft");
    };
    this.fadeInBottomRight = function () {
        return this.anim("fadeInBottomRight");
    };
    this.fadeOut = function () {
        return this.anim("fadeOut");
    };
    this.fadeOutDown = function () {
        return this.anim("fadeOutDown");
    };
    this.fadeOutDownBig = function () {
        return this.anim("fadeOutDownBig");
    };
    this.fadeOutLeft = function () {
        return this.anim("fadeOutLeft");
    };
    this.fadeOutLeftBig = function () {
        return this.anim("fadeOutLeftBig");
    };
    this.fadeOutRight = function () {
        return this.anim("fadeOutRight");
    };
    this.fadeOutRightBig = function () {
        return this.anim("fadeOutRightBig");
    };
    this.fadeOutUp = function () {
        return this.anim("fadeOutUp");
    };
    this.fadeOutUpBig = function () {
        return this.anim("fadeOutUpBig");
    };
    this.fadeOutTopLeft = function () {
        return this.anim("fadeOutTopLeft");
    };
    this.fadeOutTopRight = function () {
        return this.anim("fadeOutTopRight");
    };
    this.fadeOutBottomRight = function () {
        return this.anim("fadeOutBottomRight");
    };
    this.fadeOutBottomLeft = function () {
        return this.anim("fadeOutBottomLeft");
    };
    this.flip = function () {
        return this.anim("flip");
    };
    this.flipInX = function () {
        return this.anim("flipInX");
    };
    this.flipInY = function () {
        return this.anim("flipInY");
    };
    this.flipOutX = function () {
        return this.anim("flipOutX");
    };
    this.flipOutY = function () {
        return this.anim("flipOutY");
    };
    this.lightSpeedInRight = function () {
        return this.anim("lightSpeedInRight");
    };
    this.lightSpeedInLeft = function () {
        return this.anim("lightSpeedInLeft");
    };
    this.lightSpeedOutRight = function () {
        return this.anim("lightSpeedOutRight");
    };
    this.lightSpeedOutLeft = function () {
        return this.anim("lightSpeedOutLeft");
    };
    this.rotateIn = function () {
        return this.anim("rotateIn");
    };
    this.rotateInDownLeft = function () {
        return this.anim("rotateInDownLeft");
    };
    this.rotateInDownRight = function () {
        return this.anim("rotateInDownRight");
    };
    this.rotateInUpLeft = function () {
        return this.anim("rotateInUpLeft");
    };
    this.rotateInUpRight = function () {
        return this.anim("rotateInUpRight");
    };
    this.rotateOut = function () {
        return this.anim("rotateOut");
    };
    this.rotateOutDownLeft = function () {
        return this.anim("rotateOutDownLeft");
    };
    this.rotateOutDownRight = function () {
        return this.anim("rotateOutDownRight");
    };
    this.rotateOutUpLeft = function () {
        return this.anim("rotateOutUpLeft");
    };
    this.rotateOutUpRight = function () {
        return this.anim("rotateOutUpRight");
    };
    this.hinge = function () {
        return this.anim("hinge");
    };
    this.jackInTheBox = function () {
        return this.anim("jackInTheBox");
    };
    this.rollIn = function () {
        return this.anim("rollIn");
    };
    this.rollOut = function () {
        return this.anim("rollOut");
    };
    this.zoomIn = function () {
        return this.anim("zoomIn");
    };
    this.zoomInDown = function () {
        return this.anim("zoomInDown");
    };
    this.zoomInLeft = function () {
        return this.anim("zoomInLeft");
    };
    this.zoomInRight = function () {
        return this.anim("zoomInRight");
    };
    this.zoomInUp = function () {
        return this.anim("zoomInUp");
    };
    this.zoomOut = function () {
        return this.anim("zoomOut");
    };
    this.zoomOutDown = function () {
        return this.anim("zoomOutDown");
    };
    this.zoomOutLeft = function () {
        return this.anim("zoomOutLeft");
    };
    this.zoomOutRight = function () {
        return this.anim("zoomOutRight");
    };
    this.zoomOutUp = function () {
        return this.anim("zoomOutUp");
    };
    this.slideInDown = function () {
        return this.anim("slideInDown");
    };
    this.slideInLeft = function () {
        return this.anim("slideInLeft");
    };
    this.slideInRight = function () {
        return this.anim("slideInRight");
    };
    this.slideInUp = function () {
        return this.anim("slideInUp");
    };
    this.slideOutDown = function () {
        return this.anim("slideOutDown");
    };
    this.slideOutLeft = function () {
        return this.anim("slideOutLeft");
    };
    this.slideOutRight = function () {
        return this.anim("slideOutRight");
    };
    this.slideOutUp = function () {
        return this.anim("slideOutUp");
    };
    this.delay_2s = function () {
        $.each(["delay-2s", "delay-3s", "delay-4s", "delay-5s"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("delay-2s");
    };
    this.delay_3s = function () {
        $.each(["delay-2s", "delay-3s", "delay-4s", "delay-5s"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("delay-3s");
    };
    this.delay_4s = function () {
        $.each(["delay-2s", "delay-3s", "delay-4s", "delay-5s"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("delay-4s");
    };
    this.delay_5s = function () {
        $.each(["delay-2s", "delay-3s", "delay-4s", "delay-5s"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("delay-5s");
    };
    this.slow = function () {
        $.each(["slow", "slower", "fast", "faster"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("slow");
    };
    this.slower = function () {
        $.each(["slow", "slower", "fast", "faster"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("slower");
    };
    this.fast = function () {
        $.each(["slow", "slower", "fast", "faster"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("fast");
    };
    this.faster = function () {
        $.each(["slow", "slower", "fast", "faster"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("faster");
    };
    this.repeat_1 = function () {
        $.each(["repeat-1", "repeat-2", "repeat-3", "infinite"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("repeat-1");
    };
    this.repeat_2 = function () {
        $.each(["repeat-1", "repeat-2", "repeat-3", "infinite"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("repeat-2");
    };
    this.repeat_3 = function () {
        $.each(["repeat-1", "repeat-2", "repeat-3", "infinite"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("repeat-3");
    };
    this.infinite = function () {
        $.each(["repeat-1", "repeat-2", "repeat-3", "infinite"], function (k, v) {
            $(_id).removeClass("animate__" + v);
        });
        return this.anim("infinite");
    };
}