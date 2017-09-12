<?php

/**
 * Cette classe premet d'afficher une vid�o avec un player accessible
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class video {

    /**
     * Permet de v�rifier que la librairie videojs a bien �t� appel�e qu'une fois.
     * @var boolean Permet de v�rifier que la librairie videojs a bien �t� appel�e qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette classe premet d'afficher une vid�o avec un player accessible
     * @param string $src Chemin d'acc�s � la vid�o
     * @param string $id ID css
     */
    public function __construct($src, $id = "video-js") {
        if (!self::$_called) {
            echo html_structures::link_in_body("../commun/src/js/videojs/video-js.min.css");
            ?>
            <script type="text/javascript" src="../commun/src/js/videojs/video.min.js"></script>
            <?php
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                videojs('<?php echo $id; ?>', {}, function () {});
            });
        </script>
        <video id="<?php echo $id; ?>" class="video-js vjs-default-skin" controls
               preload="auto" width="600" data-setup='{"language":"fr"}'>            
            <source src="<?php echo $src; ?>" type="<?php echo mime_content_type($src); ?>">
        </video>
        <?php
    }

}
