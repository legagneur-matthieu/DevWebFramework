$(document).ready(function () {
    $("#cookieaccept_accept").click(function () {
        $("#cookieaccept").hide();
        localStorage.setItem("cookieaccept", "1")
    });
    if (localStorage.getItem("cookieaccept") == "1") {
        $("#cookieaccept").hide();
    }
});