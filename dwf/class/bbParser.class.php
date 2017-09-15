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
        ?>
        <script type="text/javascript" src="../commun/src/bbparser/jquery.bbcode.js"></script>
        <?php
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
                $("#<?php echo $name; ?>").bbcode();
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
        $str = preg_replace(array("#\[b\](.*?)\[/b\]#si",
            "#\[i\](.*?)\[/i\]#si",
            "#\[u\](.*?)\[/u\]#si",
            "#\[hr\]#si"), array("<b>\\1</b>",
            "<i>\\1</i>",
            "<u>\\1</u>",
            "<hr />"), $str);
        $patern = "#\[url href=([^\]]*)\]([^\[]*)\[/url\]#i";
        $replace = '<a href="\\1" target="_blank" rel="nofollow">\\2</a>';
        $str = preg_replace($patern, $replace, $str);
        $patern = "#\[img\]([^\[]*)\[/img\]#i";
        $replace = '<img src="\\1" alt=""/>';
        $str = preg_replace($patern, $replace, $str);
        $str = nl2br($str);
        return $str;
    }

}
