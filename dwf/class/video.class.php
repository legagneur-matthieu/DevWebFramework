<?php

/**
 * Cette classe premet d'afficher une vidéo avec un player accessible
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class video {

    /**
     * Permet de vérifier que la librairie videojs a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie videojs a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette classe premet d'afficher une vidéo avec un player accessible
     * @param string $src Chemin d'accès à la vidéo
     * @param string $id ID css
     */
    public function __construct($src, $id = "video-js") {
        if (!self::$_called) {
            compact_css::get_instance()->add_css_file("../commun/src/js/videojs/video-js.min.css");
            echo html_structures::script("../commun/src/js/videojs/video.min.js");
            export_dwf::add_files([realpath("../commun/src/js/videojs")]);
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                videojs('<?= $id; ?>', {}, function () {});
            });
        </script>
        <?php
        echo tags::tag("video", ["id" => $id, "class" => "video-js vjs-default-skin vjs-big-play-centered", "controls" => "true", "preload" => "auto", "width" => "600", "data-setup" => "{'language':'fr'}"], tags::tag("source", ["src" => $src, "type" => mime_content_type($src)]));
    }
}
