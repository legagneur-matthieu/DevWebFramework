<?php

/**
 * Cette classe génére un PDF à partir d'un code HTML généré préalablement (cf class/printer.class.php)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class printer {

    public function __construct() {
        session_start();
        if (
                isset($_SESSION[$_POST["content"]]) and
                in_array($type = explode("_", $_POST["content"])[0], ["PDF", "CSV", "QRCODE"])
        ) {
            $content = $_SESSION[$_POST["content"]];
            switch ($type) {
                case "PDF":
                    include_once __DIR__ . "/printer/printer_pdf.class.php";
                    new printer_pdf($content, $_POST["filename"]);
                    break;
                case "CSV":
                    include_once __DIR__ . "/printer/printer_csv.class.php";
                    new printer_csv(json_decode($content), $_POST["filename"]);
                    break;
                case "QRCODE":
                    include_once __DIR__ . "/printer/printer_qrcode.class.php";
                    new printer_qrcode($content);
                    break;
            }
        }
    }

}

new printer();
