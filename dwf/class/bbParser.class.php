<?php

/**
 * Cette classe permet de gérer un système BBCode
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class bbParser {

    /**
     * Cette classe permet de gérer un système BBCode
     */
    public function __construct() {
        echo html_structures::script("../commun/src/bbparser/jquery.bbcode.js");
    }

    /**
     * Applique une interface BBCode à un textarea
     * 
     * @param string $name id du textarea à gérer
     */
    public function texarea_to_bbeditor($name) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $name; ?>").bbcode();
            });
        </script>
        <?php
    }

    /**
     * Convertit une chaÃ®ne BBCode en HTML
     * 
     * @param string $str code BBCode à convertir en HTML
     * @return string HTML
     */
    public function getHtml($str) {
        return nl2br(preg_replace([
            "#\[b\](.*?)\[/b\]#si",
            "#\[i\](.*?)\[/i\]#si",
            "#\[u\](.*?)\[/u\]#si",
            "#\[hr\]#si",
            "#\[url href=([^\]]*)\]([^\[]*)\[/url\]#i",
            "#\[img\]([^\[]*)\[/img\]#i"
                        ], [
            "<b>\\1</b>",
            "<i>\\1</i>",
            "<u>\\1</u>",
            "<hr />",
            '<a href="\\1" target="_blank" rel="nofollow">\\2</a>',
            '<img src="\\1" alt=""/>'
                        ], $str));
    }

}
