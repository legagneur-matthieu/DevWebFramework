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
        $str = '<table' . (!empty($summary) ? ' summary="' . $summary . '"' : '') . (!empty($id) ? ' id="' . $id . '"' : '') . (!empty($class) ? ' class="' . $class . '"' : '') . '><thead><tr>';
        foreach ($head as $h) {
            $str .= '<th' . ($head_scope ? ' scope="col"' : '') . '>' . $h . '</th>';
        }
        $str .= '</tr> </thead> <tbody>';
        foreach ($data as $row) {
            $str .= '<tr>';
            foreach ($row as $value) {
                $str .= '<td>' . $value . '</td>';
            }
            $str .= '</tr>';
        }
        return ($str . '</tbody></table>');
    }

    /**
     * Retourne une liste au format HTML UL>LI à partir d'un array ( prend en compte l'imbrication des array)
     * 
     * @param array $data données de la liste
     * @param string $class class CSS de la liste 
     */
    public static function ul($data, $class = false) {
        $str = "<ul" . ($class ? ' class="' . $class . '" ' : "") . ">";
        foreach ($data as $value) {
            $str .= "<li>" . (is_array($value) ? self::ul($value) : $value) . "</li>";
        }
        return ($str . "</ul>");
    }

    /**
     * Retourne une liste "ordonnée" au format HTML OL>LI à partir d'un array ( prend en compte l'imbrication des array)
     * 
     * @param array $data données de la liste
     * @param string $class class CSS de la liste 
     */
    public static function ol($data, $class = false) {
        $str = "<ol" . ($class ? ' class="' . $class . '" ' : "") . ">";
        foreach ($data as $value) {
            $str .= "<li>" . (is_array($value) ? html_structures::ul($value) : $value) . "</li>";
        }
        return ($str . "</ol>");
    }

    /**
     * Retourne une liste DL>DT+DD à partir d'un array associatif ( les clés seront les DT et les valeurs les DD)
     * 
     * @param array $data données de la liste
     * @param string $class class CSS de la liste 
     */
    public static function dl($data, $class = false) {
        $str .= "<dl" . ($class ? ' class="' . $class . '" ' : "") . ">";
        foreach ($data as $key => $value) {
            $str .= "<dt>" . $key . "</dt><dd>" . $value . "</dd>";
        }
        return ($str . "</dl>");
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
        return '<a href="' . $href . '"' . (!empty($class) ? ' class="' . $class . '"' : "") . (!empty($title) ? ' title="' . $title . '"' : "") . ($target_blank ? ' target="_blank" ' : "") . ">" . $text . "</a>";
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
        return '<img src="' . $src . '" alt="' . $alt . '"' . (!empty($usemap) ? ' usemap="' . $usemap . '" ' : "") . '/>';
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
        return '<area shape="' . $shape . '" coords="' . $coords . '" href="' . $href . '" alt="' . $alt . '"' . (!empty($id) ? ' id="' . $shape . '"' : "") . (!empty($class) ? ' class="' . $shape . '"' : "") . '/>';
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
