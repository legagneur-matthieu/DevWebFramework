<?php

/**
 * Configuration minimale pour printer.php : permet d'utiliser session::start()
 * afin que la politique de session soit la même que celle des projets
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class config {

    /**
     * Préfixe des variables de session propres à printer
     * @var string Préfixe des variables de session propres à printer
     */
    public static $_prefix = "printer";

    /**
     * Algorithme de hachage utilisé par application::hash()
     * @var string Algorithme de hachage utilisé par application::hash()
     */
    public static $_hash_algo = "sha512";

    /**
     * Title de printer, utilisé par la classe csp
     * @var string Title de printer, utilisé par la classe csp
     */
    public static $_title = "Printer";
}

/**
 * Cette classe génére un PDF à partir d'un code HTML généré préalablement (cf class/printer.class.php)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class printer {

    public function __construct() {
        include_once __DIR__ . "/../../dwf/class/cli.class.php";
        cli::classloader();
        session::start(false);
        ob_start();
        security::run();
        ob_end_clean();
        if (
                isset($_POST["content"]) and
                isset($_SESSION[$_POST["content"]]) and
                in_array($type = explode("_", $_POST["content"])[0], ["PDF", "CSV", "QRCODE"])
        ) {
            $content = $_SESSION[$_POST["content"]];
            switch ($type) {
                case "PDF":
                    include_once __DIR__ . "/printer/printer_pdf.class.php";
                    new printer_pdf($content, $_POST["filename"], $_POST["landscape"]);
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
