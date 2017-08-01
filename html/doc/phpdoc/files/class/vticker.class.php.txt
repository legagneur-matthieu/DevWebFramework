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
            ?>
            <script type="text/javascript" src="../commun/src/js/vticker/vticker.min.js"></script>
            <?php
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#<?php echo $id; ?>').vTicker({
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
        <div id="<?php echo $id; ?>">
            <ul>
                <?php
                foreach ($data as $value) {
                    ?>
                    <li>
                        <?php echo $value; ?>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php
    }

}
