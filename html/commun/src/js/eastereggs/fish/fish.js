id_poisson = 0;
function poisson() {
    id_poisson++;
    id = id_poisson;
    $(".poisson").remove();
    $("body").append('<img src="../commun/src/js/eastereggs/fish/fish.gif" alt="" class="poisson" id="poisson_' + id + '" style="position : absolute; top : ' + rand(0, (screen.height - 70)) + 'px; left:0px;"/>');
    si = setInterval(function () {
        $("#poisson_" + id).css("left", (parseInt(strtr($("#poisson_" + id).css("left"), {"px": ""})) + 5) + "px");
        if (parseInt(strtr($("#poisson_" + id).css("left"), {"px": ""})) >= (screen.width - 140)) {
            $("#poisson_" + id).remove();
            clearInterval(si);
        }
    }, 10);
}
$(document).ready(function () {
    setInterval(function () {
        poisson();
    }, 5000);
});