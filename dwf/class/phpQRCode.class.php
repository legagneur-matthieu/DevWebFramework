<?php
/**
 * Cette classe permet de gerer des QRCode 
 * (communique avec /commun/qrcode_printer.php)
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class phpQRCode {

    /**
     * Cette fonction retourne un QRCode.
     * @param string $text Texte � transformer en QRCode
     */
    public static function print_img($text) {
        echo '<img src="../commun/qrcode_printer.php?raw=' . $text . '" alt="" />';
    }

    /**
     * Affiche un lien-modal permettant � l'utilisateur d'ouvrir la page courante sur smartphone ou tablette.
     */
    public static function this_page_to_qr() {
        (new modal())->link_open_modal(html_structures::glyphicon("qrcode", "Voir sur t�l�phone"), "QRcode", "Continuez la lecture de cette page sur votre t�l�phone", "QRCode de la page", '<div class="row"><div class="col-xs-6"><p>Scanez le QRCode pour continuer la lecture de cette page sur votre t�l�phone</p></div><div class="col-xs-6">' . self::print_img($_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . '</div></div>', "");
    }

    /**
     * Cette fonction permet d'avoir le QRCode en base 64.
     * @param string $text Text � transformer en QRCode
     * @return string QRCode (image) en Base64 
     */
    public static function get_img_b64($text) {
        return base64_encode(service::HTTP_GET($_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . strtr($_SERVER["SCRIPT_NAME"], array("index.php" => "")) . "../commun/qrcode_printer.php?raw=" . $text));
    }

}
