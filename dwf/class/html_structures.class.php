<?php

/**
 * Ensemble de fonctions permettant de créer diverses structures HTML standardisées 
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class html_structures {

    /**
     * Retourne un tableau à partir d'un array d'entête et d'un array à deux dimensions comprenant les données
     * 
     * @param array $head entête du tableau html
     * @param array $data données du tableau html
     * @param string $summary description du tableau
     * @param string $id id css
     * @param string $class class css
     * @param boolean $head_scope l'entête du tableau doit être accessible ? (true/false, true par defaut)
     */
    public static function table($head, $data, $summary = '', $id = '', $class = "table", $head_scope = true) {
        $thead = tags::tr();
        foreach ($head as $h) {
            $thead->append_content(tags::tag("th", ($head_scope ? ["scope" => "col"] : []), $h));
        }
        $tbody = tags::tbody();
        if (count($data) !== 0) {
            foreach ($data as $row) {
                $td = "";
                foreach ($row as $value) {
                    $td .= tags::tag("td", [], $value);
                }
                $tbody->append_content(tags::tag("tr", [], $td));
            }
        } else {
            $td = "";
            for ($i = 0; $i < count($head); $i++) {
                $td .= tags::tag("td", [], "");
            }
            $tbody->append_content(tags::tag("tr", [], $td));
        }
        $table = tags::table(tags::tag("thead", [], $thead) . $tbody);
        if (!empty($summary)) {
            $table->set_attr("summary", $summary);
        }
        if (!empty($id)) {
            $table->set_attr("id", $id);
        }
        if (!empty($class)) {
            $table->set_attr("class", $class);
        }
        return $table;
    }

    /**
     * Retourne une liste au format HTML UL>LI à partir d'un array ( prend en compte l'imbrication des array)
     * 
     * @param array $data données de la liste
     * @param string $class class CSS de la liste 
     */
    public static function ul($data, $class = false) {
        $li = "";
        foreach ($data as $value) {
            $li .= tags::tag("li", [], (is_array($value) ? self::ul($value) : $value));
        }
        $ul = tags::ul($li);
        if ($class) {
            $ul->set_attr("class", $class);
        }
        return $ul;
    }

    /**
     * Retourne une liste "ordonnée" au format HTML OL>LI à partir d'un array ( prend en compte l'imbrication des array)
     * 
     * @param array $data données de la liste
     * @param string $class class CSS de la liste 
     */
    public static function ol($data, $class = false) {
        $li = "";
        foreach ($data as $value) {
            $li .= tags::tag("li", [], (is_array($value) ? self::ol($value) : $value));
        }
        $ol = tags::ol($li);
        if ($class) {
            $ol->set_attr("class", $class);
        }
        return $ol;
    }

    /**
     * Retourne une liste DL>DT+DD à partir d'un array associatif ( les clés seront les DT et les valeurs les DD)
     * 
     * @param array $data données de la liste
     * @param string $class class CSS de la liste 
     */
    public static function dl($data, $class = false) {
        $dt = "";
        foreach ($data as $key => $value) {
            $dt .= tags::tag("dt", [], $key) . tags::tag("dd", [], $value);
        }
        $dl = tags::dl($dt);
        if ($class) {
            $dl->set_attr("class", $class);
        }
        return $dl;
    }

    /**
     * Retourne un lien
     * 
     * @param string $href URL
     * @param string $text Texte
     * @param string $class Classe CSS
     * @param string $title Titre du lien
     * @param boolean $target_blank Ouvrir dans un nouvel onglet
     * @return string lien
     */
    public static function a_link($href, $text, $class = "", $title = "", $target_blank = false) {
        $a = tags::a(["href" => $href], $text);
        if (!empty($class)) {
            $a->set_attr("class", $class);
        }
        if (!empty($title)) {
            $a->set_attr("title", $title);
        }
        if ($target_blank) {
            $a->set_attr("target", "_blank");
        }
        return $a;
    }

    /**
     * Retourne une ancre a
     * 
     * @param string $id
     * @return string ancre
     */
    public static function ancre($id) {
        return tags::tag("a", ["id" => $id], "");
    }

    /**
     * Retourne une image img
     * 
     * @param string $src chemin de l'image
     * @param string $alt alternative
     * @param string $usemap pour maper l'image
     */
    public static function img($src, $alt = "", $usemap = "") {
        $img = tags::img(["src" => $src, "alt" => $alt]);
        if (!empty($usemap)) {
            $img->set_attr("usemap", $usemap);
        }
        return $img;
    }

    /** Retourne une figure ( illustration et légende )
     * 
     * @param string $src chemin de l'image
     * @param string $caption légende de l'image
     * @param string $alt alternative
     */
    public static function figure($src, $caption, $alt = "") {
        return tags::tag("figure", [], self::img($src, $alt) . tags::tag("figcaption", [], $caption));
    }

    /**
     * Retourne un nouveau mapping d'image
     * 
     * @param string $id correspond au usemap d'une image
     */
    public static function new_map($id) {
        return '<map id="' . $id . '">';
    }

    /**
     * Retourne la fermeture d'un mapping d'image
     */
    public static function close_map() {
        return '</map>';
    }

    /**
     * Retourne un area à ajouter une image map
     * 
     * @param string $shape forme : rect,  ou poly
     * @param string $coords coordonées des poins de l'area
     * @param string $href lien cible
     * @param string $alt alternative
     * @param string $id id css / js
     * @param string $class class css /js
     */
    public static function area($shape, $coords, $href, $alt = "", $id = "", $class = "") {
        $area = tags::area(["shape" => $shape, "coords" => $coords, "href" => $href, "alt" => $alt]);
        if (!empty($id)) {
            $area->set_attr("id", $id);
        }
        if (!empty($class)) {
            $area->set_attr("class", $class);
        }
        return $area;
    }

    /**
     * Retourne les données passées en paramètres sous forme de média (bootstrap)
     * 
     * @param array $data Tableau de données sous la forme : array( array("img"=>"","title"=>,"","text"=>""),...);
     * @param int $width Largeur de l'image (en px)
     */
    public static function media($data, $width = 100) {
        $str = "";
        foreach ($data as $d) {
            $str .= tags::tag("div", ["class" => "media"], tags::tag(
                                    "div", ["class" => "media-left"], tags::tag(
                                            "img", ["class" => "media-object", "style" => "width: " . $width . "px;", "src" => $d["img"], "alt" => $d["titre"]])
                            ) .
                            tags::tag("div", ["class" => "media-body"], tags::tag(
                                            "h3", ["class" => "media-heading"], $d["titre"]) .
                                    $d["text"])
                    ) . self::hr();
        }
        return $str;
    }

    /**
     * Retourne un glyphicon (avec un texte alternative)
     * 
     * @param string $glyphicon nom du glyphicon (code : glyphicon glyphcon-$glyphicon )
     * @param string $alt alternative accessible aux synthéses vocales
     * @return string Glyphicon
     */
    public static function glyphicon($glyphicon, $alt = "") {
        return tags::tag("span", ["class" => "glyphicon glyphicon-" . $glyphicon], tags::tag("span", ["class" => "visually-hidden"], $alt . "&nbsp;"));
    }

    /**
     * Retourne une Bootstrap Icon ("bi", avec un texte alternative)
     * 
     * @param string $bi nom du bi (code : bi bi-$bi )
     * @param string $alt alternative accessible aux synthéses vocales
     * @return string bi
     */
    public static function bi($bi, $alt = "") {
        return tags::tag("span", ["class" => "bi bi-" . $bi], tags::tag("span", ["class" => "visually-hidden"], $alt . "&nbsp;"));
    }

    /**
     * Retourne un séparateur horizontal
     * 
     * @return string Séparateur horizontal
     */
    public static function hr() {
        return tags::tag("hr");
    }

    /**
     * La balise time permet d'afficher une date avec une valeur SEO sémantique
     * @param string $datetime datetime de la balise time
     * @param string $text texte de la balise time
     * @return string balise time
     */
    public static function time($datetime, $text) {
        return tags::tag("time", ["datetime" => $datetime], $text);
    }

    /**
     * Permet de faire appel à une balise LINK dans le body 
     * (elle est injéctée dans le HEAD par Jquery, permet de passer la validation W3C)
     * @param string $href lien du fichier CSS
     * @return string script d'injection
     */
    public static function link_in_body($href) {
        return '<script type="text/javascript">$("head").append(\'<link rel="stylesheet" href="' . $href . '" />\');</script>';
    }

    /**
     * Permet de faire appel à une balise SCRIPT dans le body 
     * (elle est injéctée dans le HEAD par Jquery)
     * @param string $src lien du fichier JS
     * @return string script d'injection
     */
    public static function script_in_body($src) {
        return tags::tag("script", ["type" => "text/javascript"], "add_script(\"$src\")");
    }

    /**
     * Retourne une balise script pour inclure un fichier JS
     * @param string $src chemin vers le fichier JS
     * @param string $async le chargement doit-il etre ansynchrone ? (false par defaut)
     * @return string balise script
     */
    public static function script($src, $async = false) {
        $attr = ["type" => "text/javascript", "src" => $src];
        if ($async) {
            $attr["async"] = "true";
        }
        return tags::tag("script", $attr, "");
    }

    /**
     * Retourne une balise link pour inclure un fichier CSS
     * @param string $src chemin vers le fichier CSS
     * @return string balise link
     */
    public static function link($href) {
        return tags::tag("link", ["rel" => "stylesheet", "href" => $href]);
    }

    /**
     * Permet d'afficher un lien avec un popover
     * @param string $id Id CSS
     * @param string $text Texte du lien
     * @param string $title Title / Titre du popover
     * @param string $content Contenu du popover
     * @param string $class classe CSS
     * @return string popover
     */
    public static function popover($id, $text, $title, $content, $class = "") {
        return tags::tag("a", ["href" => "#" . $id, "data-toggle" => "popover", "title" => $title, "data-content" => $content], $text);
    }

    /**
     * Permet d'afficher une DIV qui aura un effet de parallax
     * @param tags|string $content Contenu de la div
     * @param float $speed Vitesse de déplacement par rapport au scroll
     * @param null|string $src Background-image de la div
     * @param null|int $zindex Z-index de la div
     * @return string la DIV
     */
    public static function parallax($content, $speed, $src = false, $zindex = false) {
        $attr = ["data-speed" => $speed];
        if ($src) {
            $attr["data-src"] = $src;
        }
        if ($zindex) {
            $attr["data-zindex"] = $zindex;
        }
        return tags::div($attr, $content);
    }

}
