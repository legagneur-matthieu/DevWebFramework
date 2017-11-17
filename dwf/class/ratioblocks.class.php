<?php

/**
 * Cette classe permet d'afficher un bloc css avec les proportions passées en paramètres.
 * 
 * @author BERNARD Rodolphe <mr.rodolphe.bernard@gmail.com>
 */
class ratioblocks {

    /**
     * Permet de vérifier que le fichier .css a bien été appellé.
     * @var boolean Permet de vérifier que le fichier .css a bien été appellé.
     */
    private static $_called = false;

    /**
     * Cette classe permet d'afficher un bloc css avec les proportions passées en paramètres.
     * 
     * @param string $id CSS du bloc
     * @param int $width largeur du bloc en PX
     * @param float $ratio ratio du bloc en decimale
     * @param string $contenu contenu HTML du bloc
     */
    public function __construct($id, $width, $ratio, $contenu) {
        if (!self::$_called) {
            echo html_structures::link_in_body("../commun/src/css/ratioblocks.css");
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?php echo $id; ?>").css("width", "<?php echo ((int) $width); ?>px");
                $("#<?php echo $id; ?>").before().css("padding-top", "<?php echo ((int) $width * $ratio); ?>px");
            });
        </script>
        <div id="<?php echo $id; ?>" class="box"><div class="box_content"><?php echo $contenu ?></div></div>
        <?php
    }

}
