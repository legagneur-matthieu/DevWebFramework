$(document).ready(function () {
    if (!isset(localStorage["client_ssl_private_key"])) {
        rsa = new JSEncrypt();
        localStorage["client_ssl_private_key"] = rsa.getPrivateKey();
        localStorage["client_ssl_public_key"] = rsa.getPublicKey();
        $.post("../commun/service/index.php", {service: "rsa", prefix: $prefix, action: "set_client_ssl_public_key", public_key: localStorage["client_ssl_public_key"]}, function (data) {
            if (isset(data["error"])) {
                console.log(data["error"]);
                localStorage.removeItem("client_ssl_private_key");
            }
        }, "json");
    }
    rsa = new JSEncrypt();
    rsa.setPrivateKey(localStorage["client_ssl_private_key"]);
    $(".jsencrypt").each(function () {
        $(this).html(rsa.decrypt(strtr($(this).text(),{" ":"","\n":""})));
    });
});