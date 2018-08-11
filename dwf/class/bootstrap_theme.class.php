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
        "lumen",
        "paper",
        "readable",
        "sandstone",
        "simplex",
        "slate",
        "spacelab",
        "superhero",
        "united",
        "yeti"
    ];

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
     * Utilisé dans html5.class.php, 0 ne pas utiliser !
     * Affiche les links des thèmes
     */
    public static function link_theme() {
        if (in_array($theme = self::get_theme(), self::$_theme)) {
            echo html_structures::link("../commun/src/dist/bootswatch/" . $theme . "/bootstrap.min.css");
        } else {
            echo html_structures::link("../commun/src/dist/css/bootstrap.min.css") .
            html_structures::link("../commun/src/dist/css/bootstrap-theme.min.css");
        }
    }

    /**
     * Affiche une interface (modal) permettant à l'utilisateur de choisir un thème
     */
    public static function user_custom() {
        if (isset($_POST["bootstrap_theme"])) {
            if (in_array($_POST["bootstrap_theme"], self::$_theme)) {
                session::set_val("theme", $_POST["bootstrap_theme"]);
            } else {
                session::set_val("theme", "default");
            }
            js::redir("");
            exit();
        }
        ob_start();
        form::new_form();
        $theme = self::get_theme();
        $option = [
            ["default", "Default", false]
        ];
        foreach (self::$_theme as $t) {
            $option[] = [$t, ucfirst($t), ($t == $theme)];
        }
        form::select("Theme", "bootstrap_theme", $option);
        form::submit("btn-default");
        form::close_form();
        $data = ob_get_clean();
        (new modal())->link_open_modal(html_structures::glyphicon("cog", "Modifier le theme du site"), "bootstrap_theme_param", "Modifier le theme du site", "Theme du site", $data, "");
    }

}
