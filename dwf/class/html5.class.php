<?php
/**
 * Cette classe gère l'entête HTML5 et son pied de page.
 * Les métas descriptions et keyword sont à ajouter par vous même dans le constructeur
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class html5 {

    /**
     * Permet de vérifier si la classe html5 a déja été appellée.
     * @var boolean Permet de vérifier si la classe html5 a déja été appellée.
     */
    public static $_called = false;

    /**
     * Cette classe gère l'entête HTML5 et son pied de page.
     * 
     * @param string $title Titre du site
     * @param string $description Description de la page
     * @param string $keyword Mots clés de la page
     */
    public function __construct($description = "", $keywords = "") {
        self::$_called = true;
        $lang = explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        $lang = explode(";", $lang[0]);
        $lang = $lang[0];
        if (empty($lang)) {
            $lang = "fr";
        }
        ob_clean();
        ?><!DOCTYPE HTML>
        <html lang="<?= $lang; ?>">
            <head>
                <meta charset="UTF-8">
                <?php
                if ($description != "" or $keywords != "") {
                    ?>
                    <meta name="Robots" content="all">
                    <meta name="Revisit-after" content="14 days">   
                    <?php
                    if ($description != "") {
                        ?>
                        <meta name="description" content="<?= $description; ?>">
                        <?php
                    }
                    if ($keywords != "") {
                        ?>
                        <meta name="keywords" content="<?= $keywords; ?>">
                        <?php
                    }
                }
                ?>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <?php
                if (isset(config::$_title)and config::$_title != "") {
                    ?>
                    <title><?= config::$_title; ?></title>
                    <?php
                }
                if (isset(config::$_favicon)and config::$_favicon != "") {
                    ?> 
                    <link rel="icon" href="<?= config::$_favicon; ?>" />
                    <?php
                }
                $this->css();
                $this->js();
                $this->IE_support();
                ?>
            </head>
            <body>
                <?php
            }

            /**
             * Appele les link pour CSS
             */
            private function css() {
                ?>
                <link rel="stylesheet" href="../commun/src/dist/jquery-ui/jquery-ui.min.css" />
                <link rel="stylesheet" href="../commun/src/dist/jquery-ui/jquery-ui.structure.min.css" />
                <link rel="stylesheet" href="../commun/src/dist/jquery-ui/jquery-ui.theme.min.css" />
                <link rel="stylesheet" href="../commun/src/js/datetimepicker/jquery-ui-timepicker-addon.min.css" />
                <link rel="stylesheet" href="../commun/src/js/alertify/themes/alertify.core.css" />
                <link rel="stylesheet" href="../commun/src/js/alertify/themes/alertify.bootstrap.css" />
                <?php
                bootstrap_theme::link_theme();
                ?>                
                <link rel="stylesheet" href="../commun/src/css/style.css" />
                <link rel="stylesheet" href="../commun/src/css/pxtoem.css" />
                <?php
            }

            /**
             * Appele les script JS
             */
            private function js() {
                ?>
                <script type="text/javascript" src="../commun/src/dist/jquery-ui/jquery.js"></script>
                <script type="text/javascript" src="../commun/src/dist/jquery-ui/jquery-ui.min.js"></script>
                <script type="text/javascript" src="../commun/src/dist/jquery-ui/i18n/datepicker-fr.js"></script>
                <script type="text/javascript" src="../commun/src/dist/js/bootstrap.min.js"></script>
                <script type="text/javascript" src="../commun/src/dist/js/transition.js"></script>
                <script type="text/javascript" src="../commun/src/dist/js/collapse.js"></script>
                <script type="text/javascript" src="../commun/src/dist/js/popover.js"></script>
                <script type="text/javascript" src="../commun/src/dist/js/tooltip.js"></script>
                <script type="text/javascript" src="../commun/src/dist/js/carousel.js"></script>
                <script type="text/javascript" src="../commun/src/js/jquery.cookie.js"></script>
                <script type="text/javascript" src="../commun/src/js/datetimepicker/jquery-ui-timepicker-addon.min.js"></script>
                <script type="text/javascript" src="../commun/src/js/datetimepicker/i18n/jquery-ui-timepicker-addon-i18n.js"></script>
                <script type="text/javascript" src="../commun/src/js/datetimepicker/jquery-ui-sliderAccess.js"></script>
                <script type="text/javascript" src="../commun/src/js/alertify/lib/alertify.min.js"></script>
                <script type="text/javascript" src="../commun/src/js/animate/animate.js"></script>
                <script type="text/javascript" src="../commun/src/js/phpjs/phpjs.min.js"></script>
                <script type="text/javascript" src="../commun/src/js/php/phpvm.js"></script>
                <script type="text/javascript" src="../commun/src/js/js.js"></script>
                <?php
            }

            /**
             * Fonction pour la compatibilité IE (à améliorer !)
             */
            private function IE_support() {
                ?>
                <!--[if IE]>
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <script type="text/javascript" src="../commun/src/js/html5.js"></script>
                <script type="text/javascript" src="../commun/src/js/respond/src/respond.js"></script>
                <![endif]-->
                <?php
            }

            /**
             * Ferme le body et la balise html (et le système de chiffrement si activé)
             */
            public function __destruct() {
                application::event("onhtml_body_end");
                ?>
                <p id="real_title" class="hidden"><?= js::$_real_title; ?></p>
            </body>
        </html>
        <?php
    }

}
