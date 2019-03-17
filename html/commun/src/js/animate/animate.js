$animate_called = false;
function animate(id) {
    if (!$animate_called) {
        $("head").append('<link rel="stylesheet" href="../commun/src/js/animate/animate.css" />');
        $animate_called = true;
    }
    var _id = "#" + id;
    this.class = [
        "bounce",
        "flash",
        "pulse",
        "rubberBand",
        "shake",
        "headShake",
        "swing",
        "tada",
        "wobble",
        "jello",
        "heartBeat",
        "bounceIn",
        "bounceInDown",
        "bounceInLeft",
        "bounceInRight",
        "bounceInUp",
        "bounceOut",
        "bounceOutDown",
        "bounceOutLeft",
        "bounceOutRight",
        "bounceOutUp",
        "fadeIn",
        "fadeInDown",
        "fadeInDownBig",
        "fadeInLeft",
        "fadeInLeftBig",
        "fadeInRight",
        "fadeInRightBig",
        "fadeInUp",
        "fadeInUpBig",
        "fadeOut",
        "fadeOutDown",
        "fadeOutDownBig",
        "fadeOutLeft",
        "fadeOutLeftBig",
        "fadeOutRight",
        "fadeOutRightBig",
        "fadeOutUp",
        "fadeOutUpBig",
        "flip",
        "flipInX",
        "flipInY",
        "flipOutX",
        "flipOutY",
        "lightSpeedIn",
        "lightSpeedOut",
        "rotateIn",
        "rotateInDownLeft",
        "rotateInDownRight",
        "rotateInUpLeft",
        "rotateInUpRight",
        "rotateOut",
        "rotateOutDownLeft",
        "rotateOutDownRight",
        "rotateOutUpLeft",
        "rotateOutUpRight",
        "hinge",
        "jackInTheBox",
        "rollIn",
        "rollOut",
        "zoomIn",
        "zoomInDown",
        "zoomInLeft",
        "zoomInRight",
        "zoomInUp",
        "zoomOut",
        "zoomOutDown",
        "zoomOutLeft",
        "zoomOutRight",
        "zoomOutUp",
        "slideInDown",
        "slideInLeft",
        "slideInRight",
        "slideInUp",
        "slideOutDown",
        "slideOutLeft",
        "slideOutRight",
        "slideOutUp"
    ];
    $(_id).addClass("animated");
    this.rm_animate = function () {
        $.each(this.class, function (k, v) {
            $(_id).removeClass(v);
        });
        return this;
    };

    this.infinite = function () {
        $(_id).addClass("infinite");
        return this;
    };
    this.once = function () {
        $(_id).removeClass("infinite");
        return this;
    };

    this.anim=function(name){
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass(name);
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

    this.shake = function () {
        return this.anim("shake");
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

    this.lightSpeedIn = function () {
        return this.anim("lightSpeedIn");
    };

    this.lightSpeedOut = function () {
        return this.anim("lightSpeedOut");
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

}