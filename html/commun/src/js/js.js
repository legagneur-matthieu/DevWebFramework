$(document).ready(function () {
    $(".accordion").accordion({collapsible: true});
    $(".has-error input, .has-error select").attr("aria-invalid", "true").attr("aria-errormessage", "Erreur");
    $(".has-warning input, .has-warning select").attr("aria-invalid", "true").attr("aria-errormessage", "Attention");
    $(".carousel-pause").click(function () {
        if ($(".carousel-pause > .glyphicon").attr("class") == "glyphicon glyphicon-pause") {
            $(".carousel-pause > .glyphicon").attr("class", "glyphicon glyphicon-play");
            $(".carousel-pause").attr("data-slide", "cycle");
        } else {
            $(".carousel-pause > .glyphicon").attr("class", "glyphicon glyphicon-pause");
            $(".carousel-pause").attr("data-slide", "pause");
        }
    });
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();

});

/**
 * Cette fonction permet de formater l'affichage d'un nombre dans un INPUT de type text
 * @param {string} id ID CSS de l'input
 * @param {boolean} integer False si l'input doit tolérer les type float
 * @param {string} thousands Caractère d'espacement entre les milliers
 * @param {string} decimal Cacactère pour les décimales
 */
function maskNumber(id, integer = false, thousands = " ", decimal = ".") {
    let from = {};
    from[thousands] = "";
    from[decimal] = ".";
    $("#" + id).val(number_format(parseFloat(strtr($("#" + id).val(), from)), (integer ? 0 : 2), decimal, thousands))
            .unbind("change")
            .change(function () {
                maskNumber(id, integer, thousands, decimal);
            });
}

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