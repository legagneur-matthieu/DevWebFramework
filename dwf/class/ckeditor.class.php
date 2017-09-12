<?php

/**
 * Cette classe permet d'appliquer l'�diteur CKEditor (WYSIWYG) � un textarea 
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class ckeditor {

    /**
     * Permet de v�rifier que la librairie CKEditor a bien �t� appel�e qu'une fois.
     * @var boolean Permet de v�rifier que la librairie CKEditor a bien �t� appel�e qu'une fois.
     */
    private static $_called = false;

    /**
     * Param�tres par d�faut du CKEditor
     * 
     * @var array
     */
    private $_params = array();

    /**
     * Cette classe permet d'appliquer l'�diteur CKEditor (WYSIWYG) � un textarea 
     * 
     * @param array $id Id du textarea
     * @param array $params Surcharge les param�tres � appliquer au CKEditor ( laissez par d�faut ou voir la doc)
     */
    public function __construct($id, $params = array()) {
        if (!self::$_called) {
            ?>
            <script type="text/javascript" src="../commun/src/js/ckeditor/ckeditor.js"></script>
            <?php
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
            CKEDITOR.replace('<?php echo $id; ?>'<?php
        if (count($params) or count($this->_params)) {
            ?>
                , {<?php
            foreach ($params as $key => $value) {
                $this->_params[$key] = $value;
            }
            foreach ($this->_params as $key => $value) {
                echo $key . ': ' . $value . ', ';
            }
            ?>
                }
            <?php
        }
        ?>
            );
            $("#<?php echo $id; ?>").parents("form").css("width", "100%");
            });
        </script>
        <?php
    }

    /**
     * Filtre de s�curit� � utiliser lors de l'ex�cution d'un formulaire pour filter les balises utilis�es dans CKEditor ( protection XSS )
     * 
     * @param string $str Retour du CKEditor
     * @param string $more_tags Ajouter des balises � witelister
     */
    public function parse($str, $more_tags = "") {
        $tags = "<h1></h1><h2></h2><h3></h3><h4></h4><h5></h5><h6></h6><p></p><a></a><span></span><small></small><big></big><strong><em></em><u></u><s></s></strong><quote></quote><img><img/><sup></sup><sub></sub><div></div><ul></ul><ol></ol><li></li><dl></dl><dt></dt><dd></dd><time></time><br /><hr /><table></table><thead></thead><tbody></tbody><tfoot></tfoot><tr></tr><th></th><td></td><caption></caption><figure></figure><figcaption></figcaption>";
        if (!empty($more_tags)) {
            $tags .= $more_tags;
        }
        while ($str != ($_str = strip_tags($str, $tags))) {
            $str = $_str;
        }
        return $_str;
    }

}
