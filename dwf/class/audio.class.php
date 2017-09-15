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
            ?> 
            <script type="text/javascript" src="../commun/src/js/audio/audio.js"></script>
            <?php
            echo html_structures::link_in_body("../commun/src/js/audio/audio.css");
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                audio("<?php echo $id; ?>");
            });
        </script>
        <div class="player_ctrl">
            <audio id="<?php echo $id; ?>" src="<?php echo $src; ?>" ></audio> 
            <input title="Ligne de temps de la musique" id="<?php echo $id; ?>_player_ctrl_timeline" type="range" min="0" max="0" value="0" step="1"/>
            <button class="btn btn-xs btn-default" id="<?php echo $id; ?>_player_ctrl_play" type="button"><span class="glyphicon glyphicon-play"><span class="sr-only">Lecture</span></span></button>
            <button class="btn btn-xs btn-default" id="<?php echo $id; ?>_player_ctrl_stop" type="button"><span class="glyphicon glyphicon-pause"><span class="sr-only">Pause</span></span></button>
            <button class="btn btn-xs btn-default" id="<?php echo $id; ?>_player_ctrl_mute" type="button"><span class="glyphicon glyphicon-volume-off"><span class="sr-only">Muet</span></span></button>
            <button class="btn btn-xs btn-default" id="<?php echo $id; ?>_player_ctrl_volume_down" type="range"><span class="glyphicon glyphicon-volume-down"><span class="sr-only">Diminuer le volume</span></span></button>
            <button class="btn btn-xs btn-default" id="<?php echo $id; ?>_player_ctrl_volume_up" type="range"><span class="glyphicon glyphicon-volume-up"><span class="sr-only">Augmenter le volume</span></span></button>
            <div class="player_ctrl_volume_div">
                <input id="<?php echo $id; ?>_player_ctrl_volume" class="player_ctrl_volume" type="range" title="volume" min="0" max="10" value="10" oninput="$('#<?php echo $id; ?>_player_ctrl_volume_affichage').text($('#<?php echo $id; ?>_player_ctrl_volume').val());" />
                <p class="player_ctrl_volume_affichage" id="<?php echo $id; ?>_player_ctrl_volume_affichage">10</p>
            </div>
        </div>
        <?php
    }

    /**
     * Cette fonction permet de gérer la liste des musiques.
     * @param  array $playlist Tableau à deux dimensions contenant le titre et la source de la musique.
     * Forme du tableau : array(array("src"=>"source", "titre=>"titre"));
     */
    public function playlist($playlist) {
        ?> 
        <ul class="playlist">
            <?php
            foreach ($playlist as $t) {
                ?> <li> <a href="#<?php echo $this->_id; ?>" data-id="<?php echo $this->_id; ?>" data-src="<?php echo $t["src"]; ?>"><?php echo html_structures::glyphicon("music", "") . " " . $t["titre"]; ?></a></li>  <?php
            }
            ?>
        </ul>
        <?php
    }

}
