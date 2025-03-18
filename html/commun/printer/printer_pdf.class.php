<?php

/**
 * printer_pdf
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class printer_pdf {

    public function __construct($content, $filename, $landscape) {
        include_once __DIR__ . '/../../../dwf/class/dompdf/autoload.inc.php';
        $dompdf = new \Dompdf\Dompdf();
        if ($landscape) {
            $dompdf->setPaper("A4", "landscape");
        }
        $dompdf->load_html($content);
        ob_end_clean();
        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => 0]);
    }
}
