<?php

/**
 * Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery "stalactite"
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class stalactite {

    /**
     * Permet de vérifier que la librairie stalactite a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie stalactite a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery "stalactite"
     * 
     * @param string $id id CSS du conteneur stalactite
     */
    public function __construct($id) {
        if (!self::$_called) {
            echo html_structures::script("../commun/src/js/stalactite/stalactite.js");
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $id; ?>").stalactite({
                    duration: 25
                });
            });
        </script>
        <?php
    }

}
