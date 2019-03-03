<?php

/**
 * Cette classe permet de créer un sous-menu dans une autre classe et d'en gérer les routes
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class sub_menu {

    /**
     * Cette classe permet de créer un sous-menu dans une autre classe et d'en gérer les routes
     * 
     * @param object $this_class Dans ce parametre, c'est $this qui doit etre renseigné !
     * @param array $route Routes du sous menu sous la forme suivante ($key est à remplacer par la valeur définie dans $key ci-dessous) : <br />
     *              array(array("$key"=>"route1","title"=>"title1","text"=>"text1"),array("$key"=>"route2","title"=>"title2","text"=>"text2")); <br />
     *              ATTENTION ! A chaque route renseignée, une fonction publique doit etre créée dans la classe appellante, cette fonction doit s'appeler comme le mot associé à "$key"
     * 
     * @param string $key Mot clé des routes
     * @param string $route_default Clé/fonction par defaut à utiliser
     * @param array $keys_route_sup contient la liste des clés des menus supérieurs
     * @param string $css classe CSS du sous menu
     */
    public function __construct($this_class, $route, $key, $route_default, $keys_route_sup = array("page"), $css = "nav-tabs") {
        if (!isset($_GET[$key])) {
            $_GET[$key] = $route_default;
        }
        $ul = tags::ul(["class" => "nav " . $css, "id" => "nav-" . $key], "");
        foreach ($route as $value) {
            if (isset($value["text"])) {
                $href = "index.php?";
                foreach ($keys_route_sup as $k) {
                    $href .= $k . "=" . $_GET[$k] . "&";
                }
                $href .= $key . "=" . $value[$key];
                $li = tags::li(html_structures::a_link($href, $value["text"], "", $value["title"]));
                if ($_GET[$key] == $value[$key]) {
                    $li->set_attr("class", "active");
                }
                $ul->append_content($li);
            }
        }
        echo $ul;
        $action_finded = false;
        foreach ($route as $value) {
            if ($_GET[$key] == $value[$key]) {
                $action_finded = true;
                $v = $value[$key];
                if (isset($value["title"]) and ! empty($value["title"])) {
                    html5::before_title($value["title"] . " - ");
                }
                if (method_exists($this_class, $v)) {
                    $this_class->$v();
                } else {
                    dwf_exception::throw_exception(613, array("_1_" => get_class($this_class), "_2_" => $v . "()"));
                }
            }
        }
        if (!$action_finded) {
            $this_class->$route_default();
        }
    }

    /**
     * Ajoute une tab active à un sous menu
     * @param string $sub_menu_key $key du sous menu
     * @param string $text Texte de l'onglet
     * @param string $title Title de l'onglet
     */
    public static function add_active_tab($sub_menu_key, $text, $title = "") {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#nav-<?= $sub_menu_key; ?>>li").removeClass("active");
                $("#nav-<?= $sub_menu_key; ?>").append('<li class="active"><a href="#" <?php if ($title != "") { ?>title="<?= $title; ?>"<?php } ?>><?= $text; ?></a></li>');
            });
        </script>
        <?php
    }

}
