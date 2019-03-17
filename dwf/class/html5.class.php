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
     * Contien le titre (title) de la page HTML
     * @var string Contien le titre (title) de la page HTML
     */
    public static $_real_title = "";

    /**
     * Contien la description (meta description) de la page HTML
     * @var string Contien la description (meta description) de la page HTML
     */
    public static $_real_description = "";

    /**
     * Contien les mots clé (meta keywords) de la page HTML
     * @var string Contien les mots clé (meta keywords) de la page HTML
     */
    public static $_real_keywords = "";

    /**
     * Cette classe gère l'entête HTML5 et son pied de page.
     * 
     * @param string $title Titre du site
     * @param string $description Description de la page
     * @param string $keyword Mots clés de la page
     */
    public function __construct() {
        self::$_called = true;
        $lang = explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        $lang = explode(";", $lang[0]);
        $lang = $lang[0];
        if (empty($lang)) {
            $lang = "fr";
        }
        ob_clean();
        ob_start();
        $meta = "";
        ?><!DOCTYPE HTML>
        <html lang="<?= $lang; ?>">
            <head>
                <?php
                $meta .= tags::tag("title", [], "") .
                        tags::tag("meta", ["charset" => "UTF-8"]) .
                        tags::tag("meta", ["name" => "viewport", "content" => "width=device-width, initial-scale=1.0"]) .
                        tags::tag("meta", ["name" => "Robots", "content" => "all"]) .
                        tags::tag("meta", ["name" => "Revisit-after", "content" => "14 days"]) .
                        tags::tag("meta", ["name" => "description", "content" => ""]) .
                        tags::tag("meta", ["name" => "keywords", "content" => ""]);
                if (isset(config::$_favicon)and config::$_favicon != "") {
                    $meta .= tags::tag("link", ["rel" => "icon", "href" => config::$_favicon]);
                }
                echo $meta;
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
                bootstrap_theme::link_theme();
                foreach ([
            "../commun/src/dist/jquery-ui/jquery-ui.min.css",
            "../commun/src/dist/jquery-ui/jquery-ui.structure.min.css",
            "../commun/src/dist/jquery-ui/jquery-ui.theme.min.css",
            "../commun/src/js/datetimepicker/jquery-ui-timepicker-addon.min.css",
            "../commun/src/js/alertify/themes/alertify.core.css",
            "../commun/src/js/alertify/themes/alertify.bootstrap.css",
            "../commun/src/css/style.css",
            "../commun/src/css/pxtoem.css"
                ] as $href) {
                    compact_css::get_instance()->add_css_file($href);
                }
                echo compact_css::get_instance()->get_file_in_cache();
            }

            /**
             * Appele les script JS
             */
            private function js() {
                $script = "";
                foreach ([
            "../commun/src/dist/jquery-ui/external/jquery/jquery.js",
            "../commun/src/dist/jquery-ui/jquery-ui.min.js",
            "../commun/src/dist/jquery-ui/i18n/datepicker-fr.js",
            "../commun/src/dist/js/bootstrap.bundle.min.js",
            "../commun/src/dist/js/bootstrap.min.js",
            "../commun/src/js/jquery.cookie.js",
            "../commun/src/js/datetimepicker/jquery-ui-timepicker-addon.min.js",
            "../commun/src/js/datetimepicker/i18n/jquery-ui-timepicker-addon-i18n.js",
            "../commun/src/js/datetimepicker/jquery-ui-sliderAccess.js",
            "../commun/src/js/alertify/lib/alertify.min.js",
            "../commun/src/js/animate/animate.js",
            "../commun/src/js/sarraltroff/sarraltroff.js",
            "../commun/src/js/phpjs/phpjs.min.js",
            "../commun/src/js/php/phpvm.js",
            "../commun/src/js/js.js"
                ] as $s) {
                    $script .= html_structures::script($s);
                }
                echo $script;
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
                compact_css::get_instance()->render();
                ?>
            </body>
        </html>
        <?php
        self::render(ob_get_clean());
    }

    public static function render($document) {
        if (class_exists("tidy")) {
            $tidy = new tidy();
            $tidy->parseString($document, [
                "indent" => true,
                "wrap" => 256,
                "new-blocklevel-tags" => implode(" ", self::tags_list())
                    ], "utf8");
            echo strtr($tidy, [
                "&amp;" => "&",
                "<title></title>" => "<title>" . self::$_real_title . "</title>",
                "<meta name=\"description\" content=\"\">" => (!empty(self::$_real_description) ? "<meta name=\"description\" content=\"" . self::$_real_description . "\">" : ""),
                "<meta name=\"keywords\" content=\"\">" => (!empty(self::$_real_keywords) ? "<meta name=\"keywords\" content=\"" . self::$_real_keywords . "\">" : "")
            ]);
        } else {
            include_once __DIR__ . '/xhtml-formatter/src/XhtmlFormatter/Formatter.php';
            echo strtr((new XhtmlFormatter\Formatter())->addSkippedElement("pre")->format($document), [
                "<title></title>" => "<title>" . self::$_real_title . "</title>",
                "<meta name=\"description\" content=\"\">" => (!empty(self::$_real_description) ? "<meta name=\"description\" content=\"" . self::$_real_description . "\">" : ""),
                "<meta name=\"keywords\" content=\"\">" => (!empty(self::$_real_keywords) ? "<meta name=\"keywords\" content=\"" . self::$_real_keywords . "\">" : "")
            ]);
        }
    }

    /**
     * Ajoute un préfixe au titre de la page en cours
     * 
     * @param string $text Préfixe au titre
     */
    public static function before_title($text) {
        if (empty(self::$_real_title)) {
            self::$_real_title = config::$_title;
        }
        self::$_real_title = $text . self::$_real_title;
    }

    /**
     * Défini la décription de la page en cours (conseil SEO : pas plus de 200 caractères)
     * 
     * @param string $description Description de la page
     */
    public static function set_description($description) {
        self::$_real_description = $description;
    }

    /**
     * Défini les mots clé de la page en cours
     * 
     * @param string $keywords Mots clé de la page
     */
    public static function set_keywords($keywords) {
        self::$_real_keywords = $keywords;
    }

    /**
     * Ajoute des mots clé à la page en cours (séparer chaques mots par une virgule)
     * 
     * @param string $keywords Mots clé à ajouter à la page
     */
    public static function add_keywords($keywords) {
        self::$_real_keywords .= ", {$keywords}";
    }

    private static function tags_list() {
        return [
            "a",
            "abbr",
            "address",
            "area",
            "article",
            "aside",
            "audio",
            "b",
            "base",
            "bdi",
            "bdo",
            "blockquote",
            "body",
            "br",
            "button",
            "canvas",
            "caption",
            "cite",
            "code",
            "col",
            "colgroup",
            "data",
            "datalist",
            "dd",
            "del",
            "details",
            "dfn",
            "dialog",
            "div",
            "dl",
            "dt",
            "em",
            "embed",
            "fieldset",
            "figcaption",
            "figure",
            "footer",
            "form",
            "h1",
            "h2",
            "h3",
            "h4",
            "h5",
            "h6",
            "head",
            "header",
            "hgroup",
            "hr",
            "html",
            "i",
            "iframe",
            "img",
            "input",
            "ins",
            "kbd",
            "keygen",
            "label",
            "legend",
            "li",
            "link",
            "main",
            "map",
            "mark",
            "menu",
            "menuitem",
            "meta",
            "meter",
            "nav",
            "noscript",
            "object",
            "ol",
            "optgroup",
            "option",
            "output",
            "p",
            "param",
            "pre",
            "progress",
            "q",
            "rb",
            "rp",
            "rt",
            "rtc",
            "ruby",
            "s",
            "samp",
            "script",
            "section",
            "select",
            "small",
            "source",
            "span",
            "strong",
            "style",
            "sub",
            "summary",
            "sup",
            "table",
            "tbody",
            "td",
            "template",
            "textarea",
            "tfoot",
            "th",
            "thead",
            "time",
            "title",
            "tr",
            "track",
            "u",
            "ul",
            "var",
            "video",
            "wbr",
            //svg
            "svg",
            "g",
            "defs",
            "path",
            "clipPath",
            "circle",
            "linearGradient",
            "rect", "line",
            "polyline",
            "ellipse"
        ];
    }

}
