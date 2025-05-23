<?php

/**
 * Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery "freetile"
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class freetile {

    /**
     * Permet de vérifier que la librairie freetile a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie freetile a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery "freetile"
     * 
     * @param string $id id CSS du conteneur freetile
     */
    public function __construct($id) {
        if (!self::$_called) {
            echo html_structures::script("../commun/src/js/freetile/jquery.freetile.min.js");
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $id; ?>").freetile();
            });
        </script>
        <?php
    }

}
