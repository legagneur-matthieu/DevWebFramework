<?php

/**
 * Cette classe affiche une citation célébre à chaque chargement de page
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class citations {

    /**
     * Cette classe affiche une citation célébre à chaque chargement de page
     * @param string $css css de la citation
     */
    public function __construct($css = "cite-block") {
        $citatation = json_decode(file_get_contents(__DIR__ . "/citations/citations.json"), true);
        $citatation = $citatation[rand(0, count($citatation) - 1)];
        ?>
        <div class="citation <?php echo $css; ?>">
            <p>
                <q><?php echo $citatation["quote"]; ?></q>
                <cite><?php echo $citatation["autor"]; ?></cite>
            </p>
        </div>
        <?php
    }

}
