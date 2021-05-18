<?php

/**
 * Cette class permet de lire un QRCode depuis une camera et afficher le resultat dans un élément HTML
 * Si l'élément HTML est un input alors le résultat deviendra la valeur de l'input
 * Il est déconseillé d'utiliser cette classe plusieurs fois dans la même page
 *
 * Librairie tièr : https://github.com/cozmo/jsQR
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class jsqr {

    private static $_called = false;

    /**
     * Cette class permet de lire un QRCode depuis une camera et afficher le resultat dans un élément HTML
     * Si l'élément HTML est un input alors le résultat deviendra la valeur de l'input
     * Il est déconseillé d'utiliser cette classe plusieurs fois dans la même page
     * 
     * @param string $id ID de l'ement HTML cible
     * @param boolean $dedug affiche la webcam sur la page pour calibrer la lecture des QRCodes
     */
    public function __construct($id, $dedug = false) {
        if (!self::$_called) {
            echo html_structures::script("../commun/src/js/jsqr/jsQR.js") . html_structures::script("../commun/src/js/qr_reader.js");
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
        <?= ($dedug ? "qr_reader(\"$id\",true)" : "qr_reader(\"$id\")") ?>
            });
        </script>
        <?php
    }

}
