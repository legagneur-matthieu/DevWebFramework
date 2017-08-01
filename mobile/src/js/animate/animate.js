$animate_called = false;
function animate(id) {
    if (!$animate_called) {
        $("head").append('<link rel="stylesheet" href="../commun/src/js/animate/animate.css" />');
        $animate_called = true;
    }
    this.id = "#" + id;
    this.infini = "";
    this.class = [
        "animated",
        "infinite",
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

    this.rm_animate = function () {
        id = this.id;
        $.each(this.class, function (k, v) {
            $(id).removeClass(v);
        });
    };

    this.infinite = function () {
        $(this.id).addClass("infinite");
        this.infini = "infinite ";
    };
    this.once = function () {
        $(this.id).removeClass("infinite");
        this.infini = "";
    };

    this.bounce = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "bounce");
        }, 10);
    };

    this.flash = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "flash");
        }, 10);
    };

    this.pulse = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "pulse");
        }, 10);
    };

    this.rubberBand = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rubberBand");
        }, 10);
    };

    this.shake = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "shake");
        }, 10);
    };

    this.headShake = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "headShake");
        }, 10);
    };

    this.swing = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "swing");
        }, 10);
    };

    this.tada = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "tada");
        }, 10);
    };

    this.wobble = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "wobble");
        }, 10);
    };

    this.jello = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "jello");
        }, 10);
    };

    this.bounceIn = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "bounceIn");
        }, 10);
    };

    this.bounceInDown = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "bounceInDown");
        }, 10);
    };

    this.bounceInLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "bounceInLeft");
        }, 10);
    };

    this.bounceInRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "bounceInRight");
        }, 10);
    };

    this.bounceInUp = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "bounceInUp");
        }, 10);
    };

    this.bounceOut = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "bounceOut");
        }, 10);
    };

    this.bounceOutDown = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "bounceOutDown");
        }, 10);
    };

    this.bounceOutLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "bounceOutLeft");
        }, 10);
    };

    this.bounceOutRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "bounceOutRight");
        }, 10);
    };

    this.bounceOutUp = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "bounceOutUp");
        }, 10);
    };

    this.fadeIn = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeIn");
        }, 10);
    };

    this.fadeInDown = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeInDown");
        }, 10);
    };

    this.fadeInDownBig = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeInDownBig");
        }, 10);
    };

    this.fadeInLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeInLeft");
        }, 10);
    };

    this.fadeInLeftBig = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeInLeftBig");
        }, 10);
    };

    this.fadeInRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeInRight");
        }, 10);
    };

    this.fadeInRightBig = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeInRightBig");
        }, 10);
    };

    this.fadeInUp = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeInUp");
        }, 10);
    };

    this.fadeInUpBig = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeInUpBig");
        }, 10);
    };

    this.fadeOut = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeOut");
        }, 10);
    };

    this.fadeOutDown = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeOutDown");
        }, 10);
    };

    this.fadeOutDownBig = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeOutDownBig");
        }, 10);
    };

    this.fadeOutLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeOutLeft");
        }, 10);
    };

    this.fadeOutLeftBig = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeOutLeftBig");
        }, 10);
    };

    this.fadeOutRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeOutRight");
        }, 10);
    };

    this.fadeOutRightBig = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeOutRightBig");
        }, 10);
    };

    this.fadeOutUp = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeOutUp");
        }, 10);
    };

    this.fadeOutUpBig = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "fadeOutUpBig");
        }, 10);
    };

    this.flipInX = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "flipInX");
        }, 10);
    };

    this.flipInY = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "flipInY");
        }, 10);
    };

    this.flipOutX = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "flipOutX");
        }, 10);
    };

    this.flipOutY = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "flipOutY");
        }, 10);
    };

    this.lightSpeedIn = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "lightSpeedIn");
        }, 10);
    };

    this.lightSpeedOut = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "lightSpeedOut");
        }, 10);
    };

    this.rotateIn = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rotateIn");
        }, 10);
    };

    this.rotateInDownLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rotateInDownLeft");
        }, 10);
    };

    this.rotateInDownRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rotateInDownRight");
        }, 10);
    };

    this.rotateInUpLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rotateInUpLeft");
        }, 10);
    };

    this.rotateInUpRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rotateInUpRight");
        }, 10);
    };

    this.rotateOut = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rotateOut");
        }, 10);
    };

    this.rotateOutDownLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rotateOutDownLeft");
        }, 10);
    };

    this.rotateOutDownRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rotateOutDownRight");
        }, 10);
    };

    this.rotateOutUpLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rotateOutUpLeft");
        }, 10);
    };

    this.rotateOutUpRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rotateOutUpRight");
        }, 10);
    };

    this.hinge = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "hinge");
        }, 10);
    };

    this.rollIn = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rollIn");
        }, 10);
    };

    this.rollOut = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "rollOut");
        }, 10);
    };

    this.zoomIn = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "zoomIn");
        }, 10);
    };

    this.zoomInDown = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "");
        }, 10);
    };

    this.zoomInLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "zoomInDown");
        }, 10);
    };

    this.zoomInRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "zoomInRight");
        }, 10);
    };

    this.zoomInUp = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "zoomInUp");
        }, 10);
    };

    this.zoomOut = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "zoomOut");
        }, 10);
    };

    this.zoomOutDown = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "zoomOutDown");
        }, 10);
    };

    this.zoomOutLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "zoomOutLeft");
        }, 10);
    };

    this.zoomOutRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "zoomOutRight");
        }, 10);
    };

    this.zoomOutUp = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "zoomOutUp");
        }, 10);
    };

    this.slideInDown = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "slideInDown");
        }, 10);
    };

    this.slideInLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "slideInLeft");
        }, 10);
    };

    this.slideInRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "slideInRight");
        }, 10);
    };

    this.slideInUp = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "slideInUp");
        }, 10);
    };

    this.slideOutDown = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "slideOutDown");
        }, 10);
    };

    this.slideOutLeft = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "slideOutLeft");
        }, 10);
    };

    this.slideOutRight = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "slideOutRight");
        }, 10);
    };

    this.slideOutUp = function () {
        this.rm_animate();
        id = this.id;
        infini = this.infini;
        setTimeout(function () {
            $(id).addClass("animated " + infini + "slideOutUp");
        }, 10);
    };


}