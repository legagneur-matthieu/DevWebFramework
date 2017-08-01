/**
 * Licence GNU/GPL (DWF)
 * @author LEGAGNEUR Matthieur <legagneur.matthieu@gmail.com>
 * @author BERNARD Rodolphe <mr.rodolphe.bernard@gmail.com>
 */

function audio($id) {
    document.getElementById($id).onplay = function () {
        setTimeout(function () {
            $("#" + $id + "_player_ctrl_timeline").attr("step", 1);
            $("#" + $id + "_player_ctrl_timeline").attr("max", document.getElementById($id).duration * 1000);
        }, 1);
    };
    document.getElementById($id).onpause = function () {
        $("#" + $id + "_player_ctrl_timeline").attr("step", 10000);
    };
    document.getElementById($id).ontimeupdate = function () {
        $("#" + $id + "_player_ctrl_timeline").attr("max", document.getElementById($id).duration * 1000);
        $("#" + $id + "_player_ctrl_timeline").val(document.getElementById($id).currentTime * 1000);
    };
    $("#" + $id + "_player_ctrl_timeline").on('input', function () {
        document.getElementById($id).currentTime = parseInt($("#" + $id + "_player_ctrl_timeline").val()) / 1000;
    });
    $("#" + $id + "_player_ctrl_play").click(function () {
        document.getElementById($id).play();
    });
    $("#" + $id + "_player_ctrl_stop").click(function () {
        document.getElementById($id).pause();
    });
    $("#" + $id + "_player_ctrl_mute").click(function () {
        document.getElementById($id).volume = 0;
        $("#" + $id + "_player_ctrl_volume").val(0);
    });
    $("#" + $id + "_player_ctrl_volume_up").click(function () {
        document.getElementById($id).volume += 0.1;
        $("#" + $id + "_player_ctrl_volume").val(document.getElementById($id).volume * 10);
        $("#" + $id + "_player_ctrl_volume_affichage").text($("#" + $id + "_player_ctrl_volume").val());
    });
    $("#" + $id + "_player_ctrl_volume_down").click(function () {
        document.getElementById($id).volume -= 0.1;
        $("#" + $id + "_player_ctrl_volume").val(document.getElementById($id).volume * 10);
        $("#" + $id + "_player_ctrl_volume_affichage").text($("#" + $id + "_player_ctrl_volume").val());
    });
    $("#" + $id + "_player_ctrl_volume").on('input', function () {
        document.getElementById($id).volume = parseInt($("#" + $id + "_player_ctrl_volume").val()) / 10;
    });

    $(".playlist a").click(function () {
        data = $(this).attr("data-src");
        id = $(this).attr("data-id");
        console.log(id);
        $("#" + id).attr("src", data);
        document.getElementById(id).play();

    });
    $("ul.playlist").menu();

}




