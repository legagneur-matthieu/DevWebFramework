<?php

/**
 * Cr�� un vTicker (suite de phrases qui d�filent)
 * peut �tre appel� depuis la methode statique js::vTicker()
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class vticker {

    /**
     * Permet de v�rifier que la librairie vTicker a bien �t� appel�e qu'une fois.
     * @var boolean Permet de v�rifier que la librairie vTicker a bien �t� appel�e qu'une fois.
     */
    private static $_called = false;

    /**
     * Param�tres par d�faut du vTicker
     * 
     * @var array Param�tres par d�faut du vTicker
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
     * Cr�� un vTicker (suite de phrases qui d�filent)
     * 
     * @param array $data liste des phrases � afficher
     * @param string $id id CSS du vTicker
     * @param array $params surcharge les param�tres � appliquer au vTicker ( laissez par defaut ou voir la doc ...)
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
