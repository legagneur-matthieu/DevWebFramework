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
        "swing",
        "tada",
        "wobble",
        "jello",
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
        "slideInUp",
        "slideInDown",
        "slideInLeft",
        "slideInRight",
        "slideOutUp",
        "slideOutDown",
        "slideOutLeft",
        "slideOutRight",
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
        "hinge",
        "rollIn",
        "rollOut"
    ];
    $(_id).addClass("animated");
    this.rm_animate = function () {
        $.each(this.class, function (k, v) {
            $(_id).removeClass(v);
        });
    };

    this.infinite = function () {
        $(_id).addClass("infinite");
    };
    this.once = function () {
        $(_id).removeClass("infinite");
    };

    this.bounce = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("bounce");
        }, 10);
    };

    this.flash = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("flash");
        }, 10);
    };

    this.pulse = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("pulse");
        }, 10);
    };

    this.rubberBand = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rubberBand");
        }, 10);
    };

    this.shake = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("shake");
        }, 10);
    };

    this.headShake = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("headShake");
        }, 10);
    };

    this.swing = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("swing");
        }, 10);
    };

    this.tada = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("tada");
        }, 10);
    };

    this.wobble = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("wobble");
        }, 10);
    };

    this.jello = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("jello");
        }, 10);
    };

    this.bounceIn = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("bounceIn");
        }, 10);
    };

    this.bounceInDown = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("bounceInDown");
        }, 10);
    };

    this.bounceInLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("bounceInLeft");
        }, 10);
    };

    this.bounceInRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("bounceInRight");
        }, 10);
    };

    this.bounceInUp = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("bounceInUp");
        }, 10);
    };

    this.bounceOut = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("bounceOut");
        }, 10);
    };

    this.bounceOutDown = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("bounceOutDown");
        }, 10);
    };

    this.bounceOutLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("bounceOutLeft");
        }, 10);
    };

    this.bounceOutRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("bounceOutRight");
        }, 10);
    };

    this.bounceOutUp = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("bounceOutUp");
        }, 10);
    };

    this.fadeIn = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeIn");
        }, 10);
    };

    this.fadeInDown = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeInDown");
        }, 10);
    };

    this.fadeInDownBig = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeInDownBig");
        }, 10);
    };

    this.fadeInLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeInLeft");
        }, 10);
    };

    this.fadeInLeftBig = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeInLeftBig");
        }, 10);
    };

    this.fadeInRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeInRight");
        }, 10);
    };

    this.fadeInRightBig = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeInRightBig");
        }, 10);
    };

    this.fadeInUp = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeInUp");
        }, 10);
    };

    this.fadeInUpBig = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeInUpBig");
        }, 10);
    };

    this.fadeOut = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeOut");
        }, 10);
    };

    this.fadeOutDown = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeOutDown");
        }, 10);
    };

    this.fadeOutDownBig = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeOutDownBig");
        }, 10);
    };

    this.fadeOutLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeOutLeft");
        }, 10);
    };

    this.fadeOutLeftBig = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeOutLeftBig");
        }, 10);
    };

    this.fadeOutRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeOutRight");
        }, 10);
    };

    this.fadeOutRightBig = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeOutRightBig");
        }, 10);
    };

    this.fadeOutUp = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeOutUp");
        }, 10);
    };

    this.fadeOutUpBig = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("fadeOutUpBig");
        }, 10);
    };

    this.flipInX = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("flipInX");
        }, 10);
    };

    this.flipInY = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("flipInY");
        }, 10);
    };

    this.flipOutX = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("flipOutX");
        }, 10);
    };

    this.flipOutY = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("flipOutY");
        }, 10);
    };

    this.lightSpeedIn = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("lightSpeedIn");
        }, 10);
    };

    this.lightSpeedOut = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("lightSpeedOut");
        }, 10);
    };

    this.rotateIn = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rotateIn");
        }, 10);
    };

    this.rotateInDownLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rotateInDownLeft");
        }, 10);
    };

    this.rotateInDownRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rotateInDownRight");
        }, 10);
    };

    this.rotateInUpLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rotateInUpLeft");
        }, 10);
    };

    this.rotateInUpRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rotateInUpRight");
        }, 10);
    };

    this.rotateOut = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rotateOut");
        }, 10);
    };

    this.rotateOutDownLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rotateOutDownLeft");
        }, 10);
    };

    this.rotateOutDownRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rotateOutDownRight");
        }, 10);
    };

    this.rotateOutUpLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rotateOutUpLeft");
        }, 10);
    };

    this.rotateOutUpRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rotateOutUpRight");
        }, 10);
    };

    this.hinge = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("hinge");
        }, 10);
    };

    this.rollIn = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rollIn");
        }, 10);
    };

    this.rollOut = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("rollOut");
        }, 10);
    };

    this.zoomIn = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("zoomIn");
        }, 10);
    };

    this.zoomInDown = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("");
        }, 10);
    };

    this.zoomInLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("zoomInDown");
        }, 10);
    };

    this.zoomInRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("zoomInRight");
        }, 10);
    };

    this.zoomInUp = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("zoomInUp");
        }, 10);
    };

    this.zoomOut = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("zoomOut");
        }, 10);
    };

    this.zoomOutDown = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("zoomOutDown");
        }, 10);
    };

    this.zoomOutLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("zoomOutLeft");
        }, 10);
    };

    this.zoomOutRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("zoomOutRight");
        }, 10);
    };

    this.zoomOutUp = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("zoomOutUp");
        }, 10);
    };

    this.slideInDown = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("slideInDown");
        }, 10);
    };

    this.slideInLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("slideInLeft");
        }, 10);
    };

    this.slideInRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("slideInRight");
        }, 10);
    };

    this.slideInUp = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("slideInUp");
        }, 10);
    };

    this.slideOutDown = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("slideOutDown");
        }, 10);
    };

    this.slideOutLeft = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("slideOutLeft");
        }, 10);
    };

    this.slideOutRight = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("slideOutRight");
        }, 10);
    };

    this.slideOutUp = function () {
        this.rm_animate();
        setTimeout(function () {
            $(_id).addClass("slideOutUp");
        }, 10);
    };

}