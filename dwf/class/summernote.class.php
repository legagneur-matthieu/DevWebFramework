<?php

/**
 * Summernot transforme une textarea en editeur WYSIWYG
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class summernote {

    /**
     * Permet de vérifier que la librairie Summernote a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie Summernote a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Identifiant du textarea a transformer en Summernote
     * 
     * @var int $id Identifiant du textarea a transformer en Summernote
     */
    private $_id;

    public function __construct($id) {
        if (!self::$_called) {
            echo html_structures::script("../commun/src/js/summernote/summernote-lite.js");
            echo html_structures::script("../commun/src/js/summernote/lang/summernote-fr-FR.js");
            echo html_structures::link_in_body("../commun/src/js/summernote/summernote-lite.css");
            export_dwf::add_files([realpath("../commun/src/js/summernote")]);
            self::$_called = true;
        }
        $this->_id=$id;
        ?>
        <script>
            $(document).ready(function () {
                $('#<?= $this->_id ?>').summernote({
                    lang:"fr-FR",
                    minHeight:300
                });
                $("#<?= $this->_id; ?>").parents("form").css("width", "100%");
                setInterval(function () {
                    $(".note-editor .tooltip").remove();
                }, 200);
            });
        </script>
        <?php
    }

    /**
     * Filtre de sécurité à utiliser lors de l'exécution d'un formulaire pour filter les balises utilisées dans Summernote ( protection XSS )
     * 
     * @param string $more_tags Ajouter des balises à witelister
     */
    public function parse($more_tags = "") {
        $tags = "<h1></h1><h2></h2><h3></h3><h4></h4><h5></h5><h6></h6><p></p><a></a><span></span><small></small><big></big><strong></strong><b></b><em></em><u></u><s></s><quote></quote><img><img/><sup></sup><sub></sub><div></div><ul></ul><ol></ol><li></li><dl></dl><dt></dt><dd></dd><time></time><br /><hr /><table></table><thead></thead><tbody></tbody><tfoot></tfoot><tr></tr><th></th><td></td><caption></caption><figure></figure><figcaption></figcaption>";
        if (!empty($more_tags)) {
            $tags .= $more_tags;
        }
        $str = $_POST[$this->_id];
        while ($str != ($_str = strip_tags($str, $tags))) {
            $str = $_str;
        }
        return $_str;
    }
}
