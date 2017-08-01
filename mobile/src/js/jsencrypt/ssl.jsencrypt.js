$(document).ready(function () {
    if (!isset(localStorage["ssl_public_key"])) {
        $.post("../commun/service/index.php", {service: "rsa", prefix: $prefix, action: "get_ssl_public_key"}, function (data) {
            if (isset(data["ssl_public_key"])) {
                localStorage["ssl_public_key"] = data["ssl_public_key"];
            }
        }, "json");
    }
    $("form").submit(function ($e) {
        function error() {
            alert("une erreur est survenu, veuillez réessayer ou renir plus tard");
            window.location = "";
            $e.preventDefault();
        }
        $(this).hide();
        $rsa = new JSEncrypt();
        $rsa.setPublicKey(localStorage["ssl_public_key"]);
        $(this).find("select").each(function () {
            enc = $rsa.encrypt($rsa.encrypt(this.value));
            if (enc) {
                option = document.createElement("option");
                option.value = enc;
                option.text = option.value;
                option.selected = true;
                this.add(option);
                this.value = option.value;
            } else {
                error();
            }
        });
        $(this).find("input").each(function () {
            enc = $rsa.encrypt($(this).val().toString());
            if (enc) {
                $(this).val(enc);
            } else {
                error();
            }
        });
        $(this).find("textarea").each(function () {
            enc = $rsa.encrypt($(this).val().toString());
            if (enc) {
                $(this).val(enc);
            } else {
                error();
            }
        });
    });
});