$(document).ready(function () {
    $(".accordion").accordion({collapsible: true});
    $(".has-error input, .has-error select").attr("aria-invalid", "true").attr("aria-errormessage", "Erreur");
    $(".has-warning input, .has-warning select").attr("aria-invalid", "true").attr("aria-errormessage", "Attention");
    $('[data-toggle="tooltip"]').tooltip();
    //parallax
    (function () {
        var elements = [];
        document.querySelectorAll(".parallax").forEach(function (elem) {
            elements.push({
                element: elem,
                speed: (elem.getAttribute("data-speed") ? elem.getAttribute("data-speed") : 0.5)
            });
            if (elem.getAttribute("data-zindex")) {
                elem.style.zIndex = elem.getAttribute("data-zindex");
            }
            if (elem.getAttribute("data-src")) {
                elem.style.backgroundImage = "url('" + elem.getAttribute("data-src") + "')";
            }
        });
        console.log(elements);
        new SimpleParallax(elements);
    })();
});

/**
 * Cette fonction permet de verifier si l'utilisateur a donné la permision de lancer automatiquement les sons et vidéos de l'application
 * et d'agir en concequance ( exemple : afficher un message a l'utilisateur )
 * @param {Callback} IfAllowedCallback 
 * @param {Callback} IfNotAllowedCallback
 */
function CheckAutoplayPermission(IfAllowedCallback, IfNotAllowedCallback) {
    $("body").append($("<audio>", {id: "AutoplayPermission", src: "../commun/src/js/CheckAutoplayPermission/silence.mp3"}));
    var AutoplayPermission = document.getElementById("AutoplayPermission");
    AutoplayPermission.play();
    setTimeout(function () {
        setTimeout(AutoplayPermission.paused ? IfNotAllowedCallback : IfAllowedCallback, 1);
        AutoplayPermission.pause();
        $("#AutoplayPermission").remove();
    }, 200);
}

function add_script(src) {
    if (!document.querySelector("script[src='" + src + "']")) {
        $("head").append('<script type="text/javascript" src="' + src + '"></script>');
    }
}