<?php

/**
 * Description of printer_pdf
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class printer_pdf {

    public function __construct($content, $filename) {
        include_once '../../dwf/class/dompdf/autoload.inc.php';
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->load_html($content);
        ob_end_clean();
        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => 0]);
    }

}
