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