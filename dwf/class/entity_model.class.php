<?php

/**
 * Cettle classe permet d'afficher des pseudos MCD à partir de vos entités
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class entity_model {

    /**
     * Retourne un pseudo MCD de l'entité passé en paramètre sous la forme d'un tableau HTML
     * @param string $entity Nom de l'entité
     * @return string Pseudo MCD sous forme d'un tableau HTML
     */
    public static function table($entity) {
        $str = '<table class="table table-bordered table-condensed"><thead>'
                . '<tr><th style="border-right:0;text-align:right" class="active">' . $entity . '</th>'
                . '<th style="border-left:0;" class="active"></th></tr><tr><th>Champ</th><th>Type</th></tr></thead><tbody>';
        foreach ($entity::get_structure() as $value) {
            $str .= '<tr><td>';
            $pk = !in_array($value[1], ["int", "integer","bool","boolean", "string", "mail", "array"]);
            if ($value[2]) {
                $str .= '<u>' . $value[0] . '</u>';
            } elseif ($pk) {
                $str .= '# ' . $value[0];
            } else {
                $str .= $value[0];
            }
            $str .= '</td><td>' . ($pk ? self::table($value[1]) : $value[1]) . '</td</tr>';
        }
        $str .= '</tbody></table>';
        return $str;
    }

    /**
     * Retourne un pseudo MCD de l'entité passé en paramètre sous la forme de DIV HTML
     * @param string $entity Nom de l'entité
     * @return string Pseudo MCD sous forme de DIV HTML
     */
    public static function div($entity) {
        $str = '<div class="mcd_entity"><div class="mcd_entity_name"><p>' . $entity . '</p></div><div class="mcd_entity_data"><ul>';
        foreach ($entity::get_structure() as $value) {
            $pk = !in_array($value[1], ["int", "integer","bool","boolean", "string", "mail", "array"]);
            $str .= '<li>';
            if ($value[2]) {
                $str .= '<u>' . $value[0] . '</u>';
            } elseif ($pk) {
                $str .= '# ' . $value[0];
            } else {
                $str .= $value[0];
            }
            $str .= ($pk ? self::div($value[1], false) : " (" . $value[1] . ")").'</li>';
        }
        $str .= '</ul></div></div>';
        return $str;
    }

}
