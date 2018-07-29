<?php

/**
 * Cette classe permet de générer un lecteur audio.
 * @author BERNARD Rodolphe <mr.rodolphe.bernard@gmail.com>
 */
class audio {

    /**
     * Permet de vérifier que la librairie audio a bien été appelée qu'une fois.
     * @var bool Permet de vérifier que la librairie audio a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Identifiant du lecteur
     * @var string Identifiant du lecteur.
     */
    private $_id;

    /**
     * Cette fonction génère le lecteur audio.
     * @param string $src Chemin d'accès de la musique, possibilité de le laisser vide pour une playlist.
     * @param string $id Identifiant du lecteur audio.
     */
    public function __construct($src = "", $id = "player") {
        $this->_id = $id;
        if (!self::$_called) {
            echo html_structures::script("../commun/src/js/audio/audio.js").
            html_structures::link_in_body("../commun/src/js/audio/audio.css");
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                audio("<?= $id; ?>");                
            });
        </script>
        <?php
        $class = "btn btn-xs btn-default";
        echo tags::tag(
                "div", ["class" => "player_ctrl"], tags::tag(
                        "audio", ["id" => $id, "src" => $src], "") .
                tags::tag("input", ["title" => "Ligne de temps de la musique", "id" => $id . "_player_ctrl_timeline", "type" => "range", "min" => "0", "max" => "0", "value" => "0", "step" => "1"]) .
                tags::tag("button", ["class" => $class, "id" => $id . "_player_ctrl_play"], html_structures::glyphicon("play", "Lecture")) .
                tags::tag("button", ["class" => $class, "id" => $id . "_player_ctrl_stop"], html_structures::glyphicon("pause", "Pause")) .
                tags::tag("button", ["class" => $class, "id" => $id . "_player_ctrl_mute"], html_structures::glyphicon("volume-off", "Muet")) .
                tags::tag("button", ["class" => $class, "id" => $id . "_player_ctrl_volume_down"], html_structures::glyphicon("volume-down", "Diminuer le volume")) .
                tags::tag("button", ["class" => $class, "id" => $id . "_player_ctrl_volume_up"], html_structures::glyphicon("volume-up", "Augmenter le volume")) .
                tags::tag(
                        "div", ["class" => ""], tags::tag(
                                "input", ["id" => $id . "_player_ctrl_volume", "class" => "player_ctrl_volume", "type" => "range", "title" => "volume", "min" => "0", "max" => "10", "value" => "10"]) .
                        tags::tag(
                                "p", ["class" => "player_ctrl_volume_affichage", "id" => $id . "_player_ctrl_volume_affichage"], 10)
                )
        );
    }

    /**
     * Cette fonction permet de gérer la liste des musiques.
     * @param  array $playlist Tableau à deux dimensions contenant le titre et la source de la musique.
     * Forme du tableau : array(array("src"=>"source", "titre=>"titre"));
     */
    public function playlist($playlist) {
        $li = "";
        foreach ($playlist as $t) {
            $li .= tags::tag(
                            "li", [], tags::tag(
                                    "a", ["href" => "#" . $this->_id, "data-id" => $this->_id, "data-src" => $t["src"]], html_structures::glyphicon("music", "") . " " . $t["titre"])
            );
        }
        echo tags::tag("ul", ["class" => "playlist"], $li);
    }

}
