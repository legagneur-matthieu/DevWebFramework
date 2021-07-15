$(document).ready(function () {
    rs_ft = parseInt($("#DWF_Change").text());
    setInterval(function () {
        $.get(window.location.href, {}, function (data) {
            ft = parseInt($(data).children("#DWF_Change").text());
            if (ft && ft > rs_ft) {
                location.reload(true);
            }
        }, "HTML")
    }, 1000);
});