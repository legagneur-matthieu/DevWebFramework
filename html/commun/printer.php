<?php

/**
 * Cette classe génére un PDF à partir d'un code HTML généré préalablement (cf class/printer.class.php)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class print_pdf {

    /**
     * Cette classe génére un PDF à partir d'un code HTML généré préalablement (cf class/printer.class.php)
     * 
     * @param string $content contenu HTML du PDF
     */
    public function __construct($lib, $content, $filename) {
        switch ($lib) {
            case "dompdf":
                include_once '../../dwf/class/dompdf/autoload.inc.php';
                $dompdf = new \Dompdf\Dompdf();
                $dompdf->load_html($content);
                ob_end_clean();
                $dompdf->render();
                $dompdf->stream($filename, array('Attachment' => 0));
                break;
            case"debug":
                echo $content;
                break;
        }
    }

}

if (isset($_POST["content"])) {
    $printer = new print_pdf($_POST["lib"], base64_decode($_POST["content"]), (isset($_POST["filename"]) ? $_POST["filename"] : "printer.pdf"));
}       