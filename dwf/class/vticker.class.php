<?php

/**
 * Créé un vTicker (suite de phrases qui défilent)
 * peut être appelé depuis la methode statique js::vTicker()
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class vticker {

    /**
     * Permet de vérifier que la librairie vTicker a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie vTicker a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Paramètres par défaut du vTicker
     * 
     * @var array Paramètres par défaut du vTicker
     */
    private $_params = array(
        "speed" => '700',
        "pause" => '4000',
        "showItems" => '1',
        "mousePause" => 'true',
        "height" => '0',
        "animate" => 'true',
        "animation" => '"slide"',
        "margin" => '0',
        "padding" => '0',
        "startPaused" => 'false',
        "direction" => '"up"'
    );

    /**
     * Créé un vTicker (suite de phrases qui défilent)
     * 
     * @param array $data liste des phrases à afficher
     * @param string $id id CSS du vTicker
     * @param array $params surcharge les paramètres à appliquer au vTicker ( laissez par defaut ou voir la doc ...)
     */
    public function __construct($data, $id = "vticker", $params = array()) {
        if (!self::$_called) {
            echo html_structures::script("../commun/src/js/vticker/vticker.min.js");
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#<?= $id; ?>').vTicker({
        <?php
        foreach ($params as $key => $value) {
            $this->_params[$key] = $value;
        }
        foreach ($this->_params as $key => $value) {
            echo $key . ': ' . $value . ', ';
        }
        ?>
                });
            });
        </script>
        <?php
        $ul = [];
        foreach ($data as $value) {
            $ul[] = $value;
        }
        echo tags::tag("div", ["id" => $id], html_structures::ul($ul));
    }

}
