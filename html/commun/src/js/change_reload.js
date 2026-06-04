$(document).ready(function () {
    files = {};
    $.get("./change.php", {}, function (data) {
        files = data;
    }, "json");
    setInterval(function () {
        $.get("./change.php", {}, function (data) {
            if (Object.values(files).length != Object.values(data).length) {
                window.location.reload();
            }
            for (file in data) {
                if (data[file] != files[file]) {
                    window.location.reload();
                }
            }
        }, "json");
    }, 1000);
});