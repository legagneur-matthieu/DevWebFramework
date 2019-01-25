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
            compact_css::get_instance()->add_css_file("../commun/src/css/ratioblocks.css");
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $id; ?>").css("width", "<?= ((int) $width); ?>px");
                $("#<?= $id; ?>").before().css("padding-top", "<?= ((int) $width * $ratio); ?>px");
            });
        </script>
        <?php
        echo tags::tag("div", ["id" => $id, "class" => "box"], tags::tag("div", ["class" => "box_content"], $contenu));
    }

}
