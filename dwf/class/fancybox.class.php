<?php

/**
 * Cette classe permet l'affichage de galeries photos et vidéos via la librairie Fancybox
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class fancybox {

    /**
     * Permet de vérifier que la librairie Fancybox a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie Fancybox a bien été appelée qu'une fois.
     */
    private static $_called = false;
    private $_id;
    private $_data;

    /**
     * Cette classe permet l'affichage de galeries photos et vidéos via la librairie Fancybox
     * @param string $id Id du conteneur et nom de la galerie
     * @param array $data Tableau de donnée de la galerie :
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
        $this->_id = $id;
        $this->_data = $data;
    }

    /**
     * Affichage d'une gallerie simple
     */
    public function simple() {
        $as = "";
        foreach ($this->_data as $value) {
            $caption = "";
            $a = tags::a(["data-fancybox" => $this->_id, "href" => $value["big"]]);
            if (isset($value["caption"])) {
                $a->set_attr("data-caption", $caption = $value["caption"]);
            }
            $a->set_content(tags::tag("img", ["src" => $value["small"], "alt" => $caption]));
            $as .= $a;
        }
        echo tags::tag("div", ["id" => $this->_id], $as);
    }

    /**
     * Affichage d'une galerie en colone bootstrap
     * @param int $col "col-$col" 
     * exemple : 6 pour .col-6 et donc affichage sur 2 collones,
     * 4 pour 3 collones,
     * 3 pour 4 colonnes, ...
     */
    public function in_cols($col) {
        $as = "";
        foreach ($this->_data as $value) {
            $caption = "";
            $a = tags::a(["data-fancybox" => $this->_id, "href" => $value["big"]]);
            if (isset($value["caption"])) {
                $a->set_attr("data-caption", $caption = $value["caption"]);
            }
            $a->set_content(tags::tag("img", ["src" => $value["small"], "alt" => $caption]));
            $as .= tags::tag("div", ["class" => "col-{$col}"], $a);
        }
        echo tags::tag("div", ["class" => "row", "id" => $this->_id], $as);
    }
}
