<?php

/**
 * Organise dynamiquement les sous �l�ments d'un conteneur avec la librairie jquery "freetile"
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class freetile {

    /**
     * Permet de v�rifier que la librairie freetile a bien �t� appel�e qu'une fois.
     * @var boolean Permet de v�rifier que la librairie freetile a bien �t� appel�e qu'une fois.
     */
    private static $_called = false;

    /**
     * Organise dynamiquement les sous �l�ments d'un conteneur avec la librairie jquery "freetile"
     * 
     * @param string $id id CSS du conteneur freetile
     */
    public function __construct($id) {
        if (!self::$_called) {
            ?>
            <script type="text/javascript" src="../commun/src/js/freetile/jquery.freetile.min.js"></script>
            <?php
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?php echo $id; ?>").freetile();
            });
        </script>
        <?php
    }

}
