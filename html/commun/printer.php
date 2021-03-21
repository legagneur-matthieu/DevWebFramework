<?php

/**
 * Cette classe génére un PDF à partir d'un code HTML généré préalablement (cf class/printer.class.php)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class printer {

    public function __construct() {
        if (isset($_POST["type"])) {
            switch (strtoupper($_POST["type"])) {
                case "PDF":
                    include_once __DIR__ . "/printer/printer_pdf.class.php";
                    new printer_pdf(gzuncompress(base64_decode($_POST["content"])), $_POST["filename"]);
                    break;
                case "CSV":
                    include_once __DIR__ . "/printer/printer_csv.class.php";
                    new printer_csv(json_decode(gzuncompress(base64_decode($_POST["content"]))), $_POST["filename"]);
                    break;
                case "QRCODE":
                    include_once __DIR__ . "/printer/printer_qrcode.class.php";
                    new printer_qrcode(gzuncompress(base64_decode($_POST["content"])));
                    break;
                default:
                    break;
            }
        }
    }

}

new printer();
