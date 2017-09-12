<?php

/**
 * Organise dynamiquement les sous �l�ments d'un conteneur avec la librairie jquery "stalactite"
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class stalactite {

    /**
     * Permet de v�rifier que la librairie stalactite a bien �t� appel�e qu'une fois.
     * @var boolean Permet de v�rifier que la librairie stalactite a bien �t� appel�e qu'une fois.
     */
    private static $_called = false;

    /**
     * Organise dynamiquement les sous �l�ments d'un conteneur avec la librairie jquery "stalactite"
     * 
     * @param string $id id CSS du conteneur stalactite
     */
    public function __construct($id) {
        if (!self::$_called) {
            ?>
            <script type="text/javascript" src="../commun/src/js/stalactite/stalactite.js"></script>
            <?php
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?php echo $id; ?>").stalactite({
                    duration: 25
                });
            });
        </script>
        <?php
    }

}
