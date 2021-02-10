<?php

/**
 * Cette classe permet la création d'un PDF (passe par printer.php et les classes html2pdf)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class printer {

    /**
     * Librairie à utiliser 
     * 
     * @var string Librairie a utiliser 
     */
    private $_lib;

    /**
     * Contenu du PDF
     * 
     * @var string Contenu du PDF
     */
    private $_content;

    /**
     * Cette classe permet la création d'un PDF (passe par printer.php )
     * 
     * @param string $lib Librairie à utiliser (dompdf ou debug)
     */
    public function __construct($lib = "dompdf") {
        $this->_lib = $lib;
        $this->init_printer();
    }

    /**
     * Entête du PDF (le style CSS est à modifier à votre convenance)
     */
    private function init_printer() {
        switch ($this->_lib) {
            case "dompdf":
                $this->_content = '<html><head><meta charset="UTF-8"></head><body>';
                break;
        }
    }

    /**
     * Ajoute du contenu HTML au PDF
     * @param string $content contenu HTML
     */
    public function add_content($content) {
        $this->_content .= $content;
    }

    /**
     * Retourne le contenu du PDF
     * @return string contenu du PDF 
     */
    private function return_content() {
        switch ($this->_lib) {
            case "dompdf":
                $this->_content .= '</body></html>';
                break;
        }
        return $this->_content;
    }

    /**
     * Affiche le bouton d'impression ouvrant le PDF dans print.php
     */
    public function print_buton($filename = "printer.pdf") {
        echo tags::tag("form", ["action" => "../commun/printer.php", "target" => "_blank", "method" => "post"], tags::tag(
                        "div", ["class" => "form-group"], tags::tag(
                                "input", ["type" => "hidden", "class" => "form-control", "name" => "content", "value" => base64_encode(($this->return_content()))]) .
                        tags::tag(
                                "input", ["type" => "hidden", "class" => "form-control", "name" => "lib", "value" => $this->_lib]) .
                        tags::tag(
                                "input", ["type" => "hidden", "class" => "form-control", "name" => "filename", "value" => $filename]) .
                        tags::tag(
                                "input", ["type" => "submit", "class" => "btn btn-secondary", "value" => "Version imprimable (PDF)"])
                )
        );
    }

}
