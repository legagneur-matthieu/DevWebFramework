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
        $str = "";
        $str .= '<table ';
        if (!empty($summary)) {
            $str .= 'summary="' . $summary . '" ';
        }
        if (!empty($id)) {
            $str .= 'id="' . $id . '" ';
        }
        if (!empty($class)) {
            $str .= 'class="' . $class . '" ';
        }
        $str .= '> <thead> <tr>';

        foreach ($head as $h) {
            $str .= '<th ';
            if ($head_scope) {
                $str .= 'scope="col"';
            }
            $str .= '>' . $h . '</th>';
        }
        $str .= '</tr> </thead> <tbody>';
        foreach ($data as $row) {
            $str .= '<tr>';
            foreach ($row as $value) {
                $str .= '<td>' . $value . '</td>';
            }
            $str .= '</tr>';
        }
        $str .= '</tbody></table>';
        return $str;
    }

    /**
     * Retourne une liste au format HTML UL>LI à partir d'un array ( prend en compte l'imbrication des array)
     * 
     * @param array $data données de la liste
     * @param string $class class CSS de la liste 
     */
    public static function ul($data, $class = false) {
        $str = "";
        $str .= "<ul ";
        if ($class) {
            $str .= 'class="' . $class . '"';
        }
        $str .= " >";
        $i = 0;
        while (isset($data[$i])) {
            $str .= "<li>";
            $str .= (is_array($data[$i]) ? self::ul($data[$i]) : $data[$i]);
            $str .= "</li>";
            $i++;
        }
        $str .= "</ul>";
        return $str;
    }

    /**
     * Retourne une liste "ordonnée" au format HTML OL>LI à partir d'un array ( prend en compte l'imbrication des array)
     * 
     * @param array $data données de la liste
     * @param string $class class CSS de la liste 
     */
    public static function ol($data, $class = false) {
        $str = "";
        $str .= "<ol ";
        if ($class) {
            $str .= 'class="' . $class . '"';
        }
        $str .= " >";
        $i = 0;
        while (isset($data[$i])) {
            $str .= "<li>";
            if (isset($data[$i][0]) and $data[$i] === (array) $data[$i]) {
                $str .= html_structures::ul($data[$i]);
            } else {
                $str .= $data[$i];
            }
            $str .= "</li>";
            $i++;
        }
        $str .= "</ol>";
        return $str;
    }

    /**
     * Retourne une liste DL>DT+DD à partir d'un array associatif ( les clés seront les DT et les valeurs les DD)
     * 
     * @param array $data données de la liste
     * @param string $class class CSS de la liste 
     */
    public static function dl($data, $class = false) {
        $str = "";
        $str .= "<dl ";
        if ($class) {
            $str .= 'class="' . $class . '"';
        }
        $str .= " >";
        foreach ($data as $key => $value) {
            $str .= "<dt>" . $key . "</dt><dd>" . $value . "</dd>";
        }
        $str .= "</dl>";
        return $str;
    }

    /**
     * Retourne un lien
     * 
     * @param string $href URL
     * @param string $text Texte
     * @param string $class Class CSS
     * @param string $title Title du lien
     * @param boolean $target_blank Ouvrir dans un nouvel onglet
     * @return string lien
     */
    public static function a_link($href, $text, $class = "", $title = "", $target_blank = false) {
        $str = "";
        $str .= '<a href="' . $href . '"';
        if (!empty($class)) {
            $str .= ' class="' . $class . '" ';
        }
        if (!empty($title)) {
            $str .= ' title="' . $title . '" ';
        }
        if ($target_blank) {
            $str .= ' target="_blank" ';
        }
        $str .= ">" . $text . "</a>";
        return $str;
    }

    /**
     * Retourne une ancre a
     * 
     * @param string $id
     * @return string ancre
     */
    public static function ancre($id) {
        return '<a id="' . $id . '"></a>';
    }

    /**
     * Retourne une image img
     * 
     * @param string $src chemain de l'image
     * @param string $alt alternative
     * @param string $usemap pour maper l'image
     */
    public static function img($src, $alt = "", $usemap = "") {
        $str = '<img src="' . $src . '" alt="' . $alt . '" ';
        if (!empty($usemap)) {
            $str .= 'usemap="' . $usemap . '" ';
        }
        $str .= '/>';
        return $str;
    }

    /** Retourne une figure ( illustration + légende )
     * 
     * @param string $src chemin de l'image
     * @param string $caption legende de l'image
     * @param string $alt alternative
     */
    public static function figure($src, $caption, $alt = "") {
        return '<figure><img src="' . $src . '" alt="' . $alt . '" /><figcaption>' . $caption . '</figcaption></figure>';
    }

    /**
     * Retourne un nouveau mapping d'image
     * 
     * @param string $id correspond au usemap d'une image
     */
    public function new_map($id) {
        return '<map id="' . $id . '">';
    }

    /**
     * Retourne la fermeture d'un mapping d'image
     */
    public function close_map() {
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
    public function area($shape, $coords, $href, $alt = "", $id = "", $class = "") {
        $str = '<area shape="' . $shape . '" coords="' . $coords . '" href="' . $href . '" alt="' . $alt . '" ';
        if (!empty($id)) {
            $str .= 'id="' . $shape . '" ';
        } if (!empty($class)) {
            $str .= 'class="' . $shape . '" ';
        }
        $str .= '/>';
        return $str;
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
            $str .= '<div class="media"><div class="media-left"><img class="media-object" style="width: ' . $width . 'px;" src="' . $d["img"] . '" alt="' . $d["titre"] . '"></div><div class="media-body"><h3 class="media-heading">' . $d["titre"] . '</h3>' . $d["text"] . '</div></div><hr />';
        }
        return $str;
    }

    /**
     * Retourne un glyphicon (avec un texte alternative)
     * 
     * @param string $glyphicon nom du glyphicon (code : glyphicon glyphcon-$glyphicon )
     * @param string $alt aleternative accessible aux synthéses vocales
     * @return string Glyphicon
     */
    public static function glyphicon($glyphicon, $alt) {
        return '<span class="glyphicon glyphicon-' . $glyphicon . '"><span class="sr-only">' . $alt . '</span></span>';
    }

    /**
     * Retourne un séparateur horizontal
     * 
     * @return string Séparateur horizontal
     */
    public static function hr() {
        return '<hr />';
    }

    /**
     * La balise time permet d'afficher une date avec une valeur SEO sémantique
     * @param string $datetime datetime de la balise time
     * @param string $text texte de la balise time
     * @return string balise time
     */
    public static function time($datetime, $text) {
        return' <time datetime="' . $datetime . '">' . $text . '</time>';
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
     * Permet d'afficher un lien avec un popover
     * @param string $id Id CSS
     * @param string $text Text du lien
     * @param string $title Title / Titre du popover
     * @param string $content Contenu du popover
     * @param string $class class CSS
     * @return string popover
     */
    public static function popover($id, $text, $title, $content, $class = "") {
        return self::ancre($id) . '</a><a href="#' . $id . '" data-toggle="popover" title="' . $title . '" data-content="' . $content . '">' . $text . '</a>';
    }

}
