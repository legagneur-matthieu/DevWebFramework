<?php

/**
 * Description of printer_pdf
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class printer_pdf {

    public function __construct($content) {
        include_once '../../../dwf/class/phpqrcode/qrlib.php';
        QRcode::png(base64_decode(gzuncompress($content)));
    }

}
