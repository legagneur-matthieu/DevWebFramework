<?php

/**
 * Cette classe gère des fonctions basiques de JavaScript
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class js {

    /**
     * Permet d'afficher le p#real_title à la fin des pages (utilisé par statistiques.class.php)
     * @var string Permet d'afficher le p#real_title à la fin des pages (utilisé par statistiques.class.php)
     */
    public static $_real_title = "";

    /**
     * Ajoute un préfixe au titre de la page en cours
     * 
     * @param string $text Préfixe au titre
     */
    public static function before_title($text) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("title").text("<?= $text; ?> " + $("title").text());
            });
        </script>
        <?php
        if (empty(self::$_real_title)) {
            self::$_real_title = config::$_title;
        }
        self::$_real_title = $text . self::$_real_title;
    }

    /**
     * Affiche un message à l'écran de l'utilisateur
     * 
     * @param string $msg Message à afficher
     */
    public static function alert($msg) {
        ?>
        <script type="text/javascript">
            alert("<?= $msg; ?>");
        </script>
        <?php
    }

    /**
     * Affiche un message à l'écran de l'utilisateur
     * 
     * @param string $msg Message à afficher
     */
    public static function alertify_alert($msg) {
        ?>
        <script type="text/javascript">
            alertify.alert("<?= $msg; ?>");
        </script>
        <?php
    }

    /**
     * Affiche un message à l'écran de l'utilisateur avant redirection
     * 
     * @param string $msg Message à afficher
     * @param string $url URL de redirection
     */
    public static function alertify_alert_redir($msg, $url) {
        ?>
        <script type="text/javascript">
            alertify.alert("<?= $msg; ?>", function (e) {
                window.location = '<?= strtr($url . "___", ["&amp;___" => "", "___" => "", "&amp;" => "&"]); ?>';
            });</script>
        <?php
    }

    /**
     * Affiche un message de log à l'écran de l'utilisateur
     * 
     * @param string $msg Log à afficher
     */
    public static function log_std($msg) {
        ?>
        <script type="text/javascript">
            alertify.log("<?= $msg; ?>");
        </script>
        <?php
    }

    /**
     * Affiche un message de log à l'écran de l'utilisateur
     * 
     * @param string $msg Log à afficher
     */
    public static function log_success($msg) {
        ?>
        <script type="text/javascript">
            alertify.success("<?= $msg; ?>");
        </script>
        <?php
    }

    /**
     * Affiche un message de log à l'écran de l'utilisateur
     * 
     * @param string $msg Log à afficher
     */
    public static function log_error($msg) {
        ?>
        <script type="text/javascript">
            alertify.error("<?= $msg; ?>");
        </script>
        <?php
    }

    /**
     * Redirige l'utilisateur vers l'url renseigné en paramètre (peut être un chemin relatif)
     * 
     * @param string $url Url de redirection
     */
    public static function redir($url) {
        ?>
        <script type="text/javascript">
            window.location = "<?= strtr($url . "___", ["&amp;___" => "", "___" => "", "&amp;" => "&"]); ?>";
        </script>
        <?php
    }

    /**
     * Affiche un timer avant redirection
     * 
     * @param int $second Nombre de secondes avant redirection
     * @param string $p_id Id de la balise P ou doit être affiché le timer
     * @param string $url URL de redirection
     */
    public static function timer($second, $p_id, $url) {
        $minute = (int) ($second / 60);
        $second = $second % 60;
        $heur = (int) ($minute / 60);
        $minute = $minute % 60;
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $minuterie_heur = <?= $heur; ?>;
                $minuterie_min = <?= $minute; ?>;
                $minuterie_sec = <?= $second; ?>;
                setInterval(function () {
                    if ($minuterie_sec == 0) {
                        $minuterie_sec = 59;
                        if ($minuterie_min != 0) {
                            $minuterie_min--;
                        } else {
                            $minuterie_min = 59;
                            if ($minuterie_heur != 0) {
                                $minuterie_heur--;
                            }
                        }
                    } else {
                        $minuterie_sec--;
                    }
                    if ($minuterie_sec == 0 && $minuterie_min == 0 && $minuterie_heur == 0) {
                        window.location = "<?= $url; ?>";
                    }
                    document.getElementById('<?= $p_id; ?>').innerHTML = $minuterie_heur + ':' + $minuterie_min + ':' + $minuterie_sec;
                }, 1000);
            });
        </script>
        <?php
    }

    /**
     * Applique un éditeur CKEditor (WYSIWYG) à un textarea
     * @param array $id Id du textarea
     * @param array $params Surcharge les paramètres à appliquer au CKEditor ( laissez par défaut ou voir la doc)
     * @return \ckeditor Cette classe permet d'appliquer l'éditeur CKEditor (WYSIWYG) à un textarea 
     */
    public static function ckeditor($id, $params = []) {
        return new ckeditor($id, $params);
    }

    /**
     * Créé un vTicker (suite de phrases qui défilent)
     * 
     * @param array $data Liste des phrases à afficher
     * @param string $id Id CSS du vTicker
     * @param array $params Surcharge les paramètres à appliquer au flexslider ( laissez par défaut ou voir la doc ...)
     * @return \vticker Créé un vTicker (suite de phrases qui défilent)
     */
    public static function vTicker($data, $id = "vticker", $params = []) {
        new vticker($data, $id, $params);
    }

    /**
     * Transforme un tableau HTML en Datatable
     * 
     * @param string $id Id CSS du datatable
     * @param string $params Surcharge les paramètres à appliquer au datatable ( laissez par défaut ou voir la doc)
     * @return \datatable Applique les fonctionnalitées de la librairie datatable à un tableau HTML
     */
    public static function datatable($id = "datatable", $params = []) {
        new datatable($id, $params);
    }

    /**
     * Afficher du code formaté et stylisé par la librairie SyntaxHightlighter http://alexgorbatchev.com/SyntaxHighlighter/
     *
     * @param strig $code Le code à afficher
     * @param string $brush Le brush est lié au langage à utiliser (js par deafaut)<br />
     * Astuce script html/php : "php; html-script: true" <br />
     *  http://alexgorbatchev.com/SyntaxHighlighter/manual/brushes/
     * @param string $theme Le theme de SyntaxHightlighter a utiliser http://alexgorbatchev.com/SyntaxHighlighter/manual/themes/
     */
    public static function syntaxhighlighter($code, $brush = "js", $theme = "Default") {
        new syntaxhighlighter($code, $brush, $theme);
    }

    /**
     * Permet l'affichage de galeries photo et vidéos via la librairie Fancybox
     * @param string $id Id du conteneur et nom de la galerie
     * @param array $data tableau de donnée de la galerie :
     * [ 
     *     [ "small"=>"minature.jpg", "big"=>"photo.jpg", "caption"=>"description HTML facultative" ],
     *     [ "small"=>"minature2.png", "big"=>"video.webm", "caption"=>"description HTML facultative" ],
     *     [ "small"=>"minature3.jpg", "big"=>"url youtube ou autre"]
     * ]
     */
    public static function fancybox($id, $data) {
        new fancybox($id, $data);
    }

    /**
     * Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery "freetile"
     * 
     * @param string $id id CSS du conteneur freetile
     */
    public static function freetile($id) {
        new freetile($id);
    }

    /**
     * Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery "stalactite"
     * 
     * @param string $id id CSS du conteneur stalactite
     */
    public static function stalactite($id) {
        new stalactite($id);
    }

    /**
     * Applique l'effet "shuffleLetters" à un élément au chargement de la page
     * 
     * @param string $id id CSS de l'élément
     */
    public static function shuffle_letters($id) {
        new shuffle_letters($id);
    }

    /**
     * REQUIERS JQUERY-UI
     * Affiche la boite de dialogue de jquery-ui
     * 
     * @param string $name Som de la boite de dialogue
     * @param string $title Titre de la boite de dialogue
     * @param string $html Contenu de le boite de dialogue ( au format HTML)
     */
    public static function dialog($name, $title, $html) {
        echo tags::tag("div", ["id" => $name, "title" => $title], $html);
        ?>
        <script type="text/javascript">
            $("#<?= $name; ?>").dialog({autoOpen: true});
        </script>
        <?php
    }

    /**
     * Applique un effet accordéon à une structure : div#$id>(h3+div)*X
     * @param string $id Id CSS de l'accordéon
     * @param boolan $collapsible Si $collapible est passé à false , un volet de l'accordéon sera toujours ouvert, à true , tout les volets peuvent être fermés
     * @param boolan $heightStyle La taille d'un volet doit-il être relatif à son contenu ? (true / false, false par defaut )
     */
    public static function accordion($id = "accordion", $collapsible = true, $heightStyle = false) {
        ?>
        <script>
            $(document).ready(function () {
            $("#<?= $id; ?>").accordion(
        <?php
        if ($collapsible or $heightStyle) {
            ?>
                {
            <?php
            if ($collapsible) {
                echo 'collapsible:true,';
            }
            if ($heightStyle) {
                echo 'heightStyle:"content",';
            }
            ?>
                classes:{"ui-accordion-header-active":"ui-accordion-header-active label-info"},
                }
            <?php
        }
        ?>
            );
            });</script>
        <?php
    }

    /**
     * Transforme une liste ul>li en menu
     * @param string $id Id CSS de la liste
     */
    public static function menu($id = "menu") {
        ?>
        <script>
                    $(document).ready(function () {
            $("#<?= $id; ?>").menu();
            });</script>
        <?php
    }

    /**
     * Applique un toolpip à un élément ( transforme son "title" en infobulle rapide)
     * @param string $id Id CSS de l'élément
     */
    public static function tooltip($id) {
        ?>
        <script>
                    $(document).ready(function () {
            $("#<?= $id; ?>").tooltip();
            });
        </script>
        <?php
    }

    /**
     * Affiche le carousel/slide de bootstrap
     * @param string $id Id css du carousel/slide
     * @param array $data Tableau contenant les images, les alternatives et les caption, 
     * format du tableau : array(array("img"=>"chamain/img.png","alt"=>"facultative","caption"=>"HTML facultative"));
     */
    public static function slider($id, $data) {

        //Indicators
        $ol = tags::ol(["class" => "carousel-indicators", "data-ride" => "carousel"]);
        for ($i = 0; $i < count($data); $i++) {
            $li = tags::li(["data-target" => "#" . $id, "data-slide-to" => $i], html_structures::a_link("#", tags::tag("span", ["class" => "sr-only"], "slide " . $i)));
            if ($i == 0) {
                $li->set_attr("class", "active");
            }
            $ol->append_content($li);
        }
        //Wrapper for slides
        $inner = tags::div(["class" => "carousel-inner", "role" => "listbox"]);
        $first = true;
        foreach ($data as $d) {
            $inner->append_content(tags::tag("div", ["class" => "item" . ($first ? " active" : "")], html_structures::img($d["img"], (isset($d["alt"]) ? $d["alt"] : "")) .
                            (isset($d["caption"]) ? tags::tag("div", ["class" => "carousel-caption"], $d["caption"]) : "")
            ));
            $first = false;
        }
        //Controls
        $controls = tags::tag("a", ["class" => "left carousel-control", "href" => "#" . $id, "role" => "button", "data-slide" => "prev"], html_structures::glyphicon("chevron-left", "Précédent")) .
                tags::tag("a", ["class" => "right carousel-control", "href" => "#" . $id, "role" => "button", "data-slide" => "next"], html_structures::glyphicon("chevron-right", "Suivant")) .
                tags::tag("a", ["class" => "center carousel-pause", "href" => "#" . $id, "role" => "button", "data-slide" => "pause"], html_structures::glyphicon("pause", "Pause"));
        echo tags::tag("div", ["id" => $id, "class" => "carousel slide", "data-ride" => "carousel"], $ol . $inner . $controls);
    }

}
