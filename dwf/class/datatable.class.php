<?php

/**
 * Description of datatable
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class datatable {

    /**
     * Permet de vérifier que la librairie datatable a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie datatable a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Paramètres par défaut du datatable
     * 
     * @var array
     */
    private $_params = ["language" => "{url: '../commun/src/js/DataTables/i18n/fr-FR.json'}"];

    /**
     * Applique les fonctionnalités de la librairie datatable à un tableau HTML
     * 
     * @param string $id id du tableau HTML
     * @param array $params surcharge les paramètres à appliquer au datatable ( laissez par défaut ou voir la documentation )
     */
    public function __construct($id = "datatable", $params = []) {
        if (!self::$_called) {
            echo html_structures::script("../commun/src/js/DataTables/datatables.min.js");
            compact_css::get_instance()->add_css_file("../commun/src/js/DataTables/datatables.min.css");
            export_dwf::add_files([realpath("../commun/src/js/DataTables")]);
            self::$_called = true;
        }
        ?>
        <script>
            $(document).ready(function () {
            $("#<?= $id; ?>").addClass("display");
                    $('#<?= $id; ?>').DataTable(<?php
        if (count($params) or count($this->_params)) {
            ?>
                {<?php
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
        ?>);
            });

        </script>
        <?php
    }

}
