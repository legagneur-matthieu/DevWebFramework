<?php

/**
 * Cette classe permet le téléchargement de fichier .csv
 * @author BERNARD Rodolphe <mr.rodolphe.bernard@gmail.com>
 */
class csv {

    /**
     * Cela permet de télecharger le fichier .csv
     */
    public function __construct() {
        header("Content-Disposition: attachment; filename=export.csv;");
        header("Content-type:text/csv; charset=UTF-8");
        echo base64_decode($_GET["value"]);
    }

}

if (isset($_GET["value"])) {
    new csv();
}

