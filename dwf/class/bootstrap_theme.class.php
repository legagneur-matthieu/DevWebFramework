<?php

/**
 * Cette classe permet de gèrer les thèmes de bootswatch
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class bootstrap_theme {

    /**
     * Thèmes de bootswath
     * @var array Thèmes de bootswath
     */
    private static $_theme = [
        "cerulean",
        "cosmo",
        "cyborg",
        "darkly",
        "flatly",
        "journal",
        "litera",
        "lumen",
        "lux",
        "materia",
        "minty",
        "pulse",
        "sandstone",
        "simplex",
        "sketchy",
        "slate",
        "solar",
        "spacelab",
        "superhero",
        "united",
        "yeti"
    ];

    /**
     * Retourne la liste des thèmes disponible
     * @return array La liste des thèmes disponible
     */
    public static function get_bootstrap_themes() {
        return self::$_theme;
    }

    /**
     * Retourne le thème renseigné dans config ou en session si l'utilisateur 
     * @return string Thèmes de bootswath
     */
    private static function get_theme() {
        $theme = "default";
        if (isset(config::$_theme)) {
            $theme = config::$_theme;
        }
        if (session::get_val("theme")) {
            $theme = session::get_val("theme");
        }
        return $theme;
    }

    /**
     * Utilisé dans html5.class.php, ne pas utiliser !
     * Affiche les links des thèmes
     */
    public static function link_theme() {
        echo html_structures::link("../commun/src/dist/css/bootstrap-reboot.min.css") .
        html_structures::link("../commun/src/dist/css/bootstrap-glyphicon.min.css") .
        html_structures::link("../commun/src/dist/bootstrap-icons/bootstrap-icons.css") .
        html_structures::link("../commun/src/dist/css/bootstrap-grid.min.css");
        if (in_array($theme = self::get_theme(), self::$_theme)) {
            echo html_structures::link("../commun/src/dist/bootswatch/{$theme}/bootstrap.min.css");
        } else {
            echo html_structures::link("../commun/src/dist/css/bootstrap.min.css");
        }
    }

    /**
     * Affiche une interface (modal) permettant à l'utilisateur de choisir un thème
     * @return string Lien modal
     */
    public static function user_custom() {
        if (isset($_POST["dwf_bootstrap_theme"])) {
            if (in_array($_POST["dwf_bootstrap_theme"], self::$_theme)) {
                session::set_val("theme", $_POST["dwf_bootstrap_theme"]);
            } else {
                session::set_val("theme", "default");
            }
            js::redir("");
            exit();
        }
        $form = new form();
        $option = [
            ["default", "Default", false]
        ];
        foreach (self::$_theme as $t) {
            $option[] = [$t, ucfirst($t), ($t == self::get_theme())];
        }
        $form->select("Thème", "dwf_bootstrap_theme", $option);
        $form->submit("btn-primary");
        return (new modal())->link_open_modal(html_structures::glyphicon("cog", "Modifier le thème du site"), "bootstrap_theme_param", "Modifier le thème du site", "Thème du site", $form->render(), "");
    }

    /**
     * Permet à l'utilisateur de passer du thème par défaut à un autre prédéfini et inversement.
     * (Généralement utilisé pour proposer un thème clair et un thème sombre)
     * @param string $theme thème à appliquer (Darkly par defaut)
     * @param array $labels les labels à afficher pour passer d'un thème à l'autre (["Thème clair", "Thème sombre"] par defaut)
     * @return string Formulaire du switch
     */
    public static function switch_theme($theme = "darkly", $labels = ["Thème clair", "Thème sombre"]) {
        if (isset($_POST["dwf_bootstap_switch_theme"])) {
            session::set_val("theme", ($_POST["dwf_bootstap_switch_theme"] == 0 ? config::$_theme : $theme));
            js::redir("");
            exit();
        }
        $value = (session::get_val("theme") == $theme ? 0 : 1);
        $form = new form();
        $form->hidden("dwf_bootstap_switch_theme", $value);
        $form->submit("btn-primary", $labels[$value]);
        return $form->render();
    }

}
