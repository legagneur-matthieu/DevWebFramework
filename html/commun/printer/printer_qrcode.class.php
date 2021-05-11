<?php

/**
 * printer_qrcode
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class printer_qrcode {

    public function __construct($content) {
        include __DIR__ . '/../../../dwf/class/phpqrcode/qrlib.php';
        QRcode::png($content);
    }

}
