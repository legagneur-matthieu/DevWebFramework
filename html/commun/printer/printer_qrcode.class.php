<?php

/**
 * Description of printer_pdf
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class printer_qrcode {

    public function __construct($content) {
        include '../../dwf/class/phpqrcode/qrlib.php';
        QRcode::png($content);
    }

}
