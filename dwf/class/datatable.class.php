<?php

/**
 * Description of datatable
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class datatable {

    /**
     * Permet de v�rifier que la librairie datatable a bien �t� appel�e qu'une fois.
     * @var boolean Permet de v�rifier que la librairie datatable a bien �t� appel�e qu'une fois.
     */
    private static $_called = false;

    /**
     * Param�tres par d�faut du flexslider
     * 
     * @var array
     */
    private $_params = array("responsive" => "true", "language" => "{url: '../commun/src/js/DataTables/lang/French.lang.json'}");

    /**
     * Applique les fonctionnalit�es de la librairie datatable � un tableau HTML
     * 
     * @param string $id id du tableau HTML
     * @param array $params surcharge les param�tres � appliquer au flexslider ( laissez par d�faut ou voir la doc)
     */
    public function __construct($id = "datatable", $params = array()) {
        if (!self::$_called) {
            ?>
            <script type="text/javascript" src="../commun/src/js/DataTables/media/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="../commun/src/js/DataTables/media/js/dataTables.bootstrap.js"></script>       
            <?php
            echo html_structures::link_in_body("../commun/src/js/DataTables/media/css/dataTables.bootstrap.css");
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
            $("#<?php echo $id; ?>").addClass("display");
                    $('#<?php echo $id; ?>').DataTable(<?php
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
