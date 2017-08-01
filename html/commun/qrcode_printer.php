<?php

/**
 * Cette classe génére des QRCode
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class qrcode_printer {

    /**
     * Cette classe génére des QRCode
     */
    public function __construct() {
        if (isset($_REQUEST["raw"])) {
            include_once '../../dwf/class/phpqrcode/qrlib.php';
            QRcode::png($_REQUEST["raw"]);
        }
    }

}

new qrcode_printer();
