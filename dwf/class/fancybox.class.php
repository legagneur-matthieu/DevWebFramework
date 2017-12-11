<?php

/**
 * Cette classe permet l'affichage de galeries photo et vidéos via la librairie Fancybox
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class fancybox {

    /**
     * Permet de vérifier que la librairie fancybox a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie fancybox a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette classe permet l'affichage de galeries photo et vidéos via la librairie Fancybox
     * @param string $id Id du conteneur et nom de la galerie
     * @param array $data tableau de donnée de la galerie :
     * [ 
     *     [ "small"=>"minature.jpg", "big"=>"photo.jpg", "caption"=>"description HTML facultative" ],
     *     [ "small"=>"minature2.png", "big"=>"video.webm", "caption"=>"description HTML facultative" ],
     *     [ "small"=>"minature3.jpg", "big"=>"url youtube ou autre"]
     * ]
     */
    public function __construct($id, $data) {
        if (!self::$_called) {
            echo html_structures::link_in_body("../commun/src/js/fancybox/jquery.fancybox.min.css");
            ?>
            <script type="text/javascript" src="../commun/src/js/fancybox/jquery.fancybox.min.js"></script>
            <?php
            self::$_called = true;
        }
        ?>
        <div id="<?= $id; ?>">
            <?php
            foreach ($data as $value) {
                ?>
                <a data-fancybox="<?= $id; ?>" <?= (isset($value["caption"]) ? 'data-caption="' . ($caption = $value["caption"]) . '"' : $caption = "") ?> href="<?= $value["big"]; ?>">
                    <img src="<?= $value["small"]; ?>" alt="<?= $caption ?>">
                </a>
                <?php
            }
            ?>
        </div>
        <?php
    }

}
