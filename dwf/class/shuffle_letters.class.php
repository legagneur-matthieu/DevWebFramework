<?php

/**
 * Applique l'effet "shuffleLetters" � un �l�ment au chargement de la page
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class shuffle_letters {

    /**
     * Permet de v�rifier que la librairie shuffleLetters a bien �t� appel�e qu'une fois.
     * @var boolean Permet de v�rifier que la librairie shuffleLetters a bien �t� appel�e qu'une fois.
     */
    private static $_called = false;

    /**
     * Applique l'effet "shuffleLetters" a un �l�ment au chargement de la page
     * 
     * @param string $id id CSS de l'�l�ment
     */
    public function __construct($id) {
        if (!self::$_called) {
            ?>
            <script type="text/javascript" src="../commun/src/js/shuffleletters/jquery.shuffleLetters.js"></script>
            <?php
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?php echo $id; ?>").shuffleLetters();
            });
        </script>
        <?php
    }

}
