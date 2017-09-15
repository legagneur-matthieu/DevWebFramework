﻿<?php

/**
 * Applique l'effet "shuffleLetters" à un élément au chargement de la page
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class shuffle_letters {

    /**
     * Permet de vérifier que la librairie shuffleLetters a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie shuffleLetters a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Applique l'effet "shuffleLetters" a un élément au chargement de la page
     * 
     * @param string $id id CSS de l'élément
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
