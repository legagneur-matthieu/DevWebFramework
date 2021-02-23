<?php

/**
 * Cette classe gère la pagination dans une page
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class pagination {

    /**
     * Calcule toutes les valeurs nécessaires à la pagination
     * 
     * @param string $get nom de la variable get pour la pagination (généralement "p")
     * @param int $per_page nombre d'items par page ( généralement 10, 20, 50 ou 100 selon la taille de chaque élément)
     * @param int $count_all nombre total d'items ( valeur retournée par un count en SQL ou PHP)
     * @return array array($lim1, $lim2, $count_all, $nb_page);
     */
    public static function get_limits($get, $per_page, $count_all) {
        if (!isset($_GET[$get])) {
            $_GET[$get] = 1;
        }
        $lim1 = (int) ($_GET[$get] - 1) * $per_page;
        $lim2 = $lim1 + $per_page;
        $nb_page = (int) ($count_all / $per_page) + 1;
        return [$lim1, $lim2, $count_all, $nb_page];
    }

    /**
     * Affiche la pagination
     * 
     * @param string $get nom de la variable get pour la pagination
     * @param int $nb_page $var = pagination::get_limits($get, $per_page, $count_all); <br />
     *                     $nb_page=$var[3]
     */
    public static function print_pagination($get, $nb_page) {
        if ($nb_page != 1) {
            $ul = tags::ul(["class" => "pagination pagination-sm"]);
            for ($i = 1; $i <= $nb_page; $i++) {
                $li = tags::li(html_structures::a_link("?p" . $i, $i));
                if ($_GET[$get] == $i) {
                    $li->set_attr("class", "active");
                }
                $ul->append_content($li);
            }
            echo $ul;
        }
    }

}
