$(document).ready(function () {
    rs_ft = 0;
    $.get("./change.php", {}, function (ft) {
        rs_ft = ft;
    }, "json");
    setInterval(function () {
        $.get("./change.php", {}, function (ft) {
            if (ft && ft > rs_ft) {
                location.reload(true);
            }
        }, "json");
    }, 1000);
});