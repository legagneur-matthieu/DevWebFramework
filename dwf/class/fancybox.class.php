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
            compact_css::get_instance()->add_css_file("../commun/src/js/fancybox/jquery.fancybox.min.css");
            echo html_structures::script("../commun/src/js/fancybox/jquery.fancybox.min.js");
            self::$_called = true;
        }
        $as = "";
        foreach ($data as $value) {
            $caption = "";
            $a = tags::a(["data-fancybox" => $id, "href" => $value["big"]]);
            if (isset($value["caption"])) {
                $a->set_attr("data-caption", $caption = $value["caption"]);
            }
            $a->set_content(tags::tag("img", ["src" => $value["small"], "alt" => $caption]));
            $as .= $a;
        }
        echo tags::tag("div", ["id" => $id], $as);
    }

}
