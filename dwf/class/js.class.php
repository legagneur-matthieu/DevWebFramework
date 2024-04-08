<?php

/**
 * Cette classe gère des fonctions basiques de JavaScript
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class js {

    /**
     * CETTE METHODE EST OBSELETTE !
     * ALIAS DE html5::before_title();
     * Ajoute un préfixe au titre de la page en cours
     * 
     * @param string $text Préfixe au titre
     */
    public static function before_title($text) {
        html5::before_title($text);
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
    public static function alertify_alert($msg, $title = "Message") {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                alertify.alert("<?= $title; ?>", "<?= $msg; ?>");
            });
        </script>
        <?php
    }

    /**
     * Affiche un message à l'écran de l'utilisateur avant redirection
     * 
     * @param string $msg Message à afficher
     * @param string $url URL de redirection
     */
    public static function alertify_alert_redir($msg, $url, $title = "Message") {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                alertify.alert("<?= $title; ?>", "<?= $msg; ?>", function () {
                    window.location = '<?= strtr($url . "___", ["&amp;___" => "", "___" => "", "&amp;" => "&"]); ?>';
                });
            });
        </script>
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
            $(document).ready(function () {
                alertify.log("<?= $msg; ?>");
            });
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
            $(document).ready(function () {
                alertify.success("<?= $msg; ?>");
            });
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
            $(document).ready(function () {
                alertify.error("<?= $msg; ?>");
            });
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
     * Permet de lire un QRCode depuis une camera et afficher le resultat dans un élément HTML
     * Si l'élément HTML est un input alors le résultat deviendra la valeur de l'input
     * Il est déconseillé d'utiliser cette classe plusieurs fois dans la même page
     * @param string $id ID de l'ement HTML cible
     * @param boolean $dedug affiche la webcam sur la page pour calibrer la lecture des QRCodes
     * @return \jsqr
     */
    public static function jsqr($id, $debug = false) {
        return new jsqr($id, $debug);
    }

    /**
     * Créé un vTicker (suite de phrases qui défilent)
     * 
     * @param array $data Liste des phrases à afficher
     * @param string $id Id CSS du vTicker
     * @param array $params Surcharge les paramètres à appliquer au vTicker ( laissez par défaut ou voir la documentation )
     * @return \vticker Créé un vTicker (suite de phrases qui défilent)
     */
    public static function vTicker($data, $id = "vticker", $params = []) {
        new vticker($data, $id, $params);
    }

    /**
     * Transforme un tableau HTML en Datatable
     * 
     * @param string $id Id CSS du datatable
     * @param string $params Surcharge les paramètres à appliquer au datatable ( laissez par défaut ou voir la documentation )
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
     * @param string $theme Le theme de SyntaxHightlighter à utiliser http://alexgorbatchev.com/SyntaxHighlighter/manual/themes/
     */
    public static function syntaxhighlighter($code, $brush = "js", $theme = "Default") {
        new syntaxhighlighter($code, $brush, $theme);
    }

    /**
     * Permet l'affichage de galeries photos et vidéos via la librairie Fancybox
     * @param string $id Id du conteneur et nom de la galerie
     * @param array $data tableau de données de la galerie :
     * [ 
     *     [ "small"=>"minature.jpg", "big"=>"photo.jpg", "caption"=>"description HTML facultative" ],
     *     [ "small"=>"minature2.png", "big"=>"video.webm", "caption"=>"description HTML facultative" ],
     *     [ "small"=>"minature3.jpg", "big"=>"url youtube ou autre"]
     * ]
     * @param int $col Affichage en collones bootstrapDescription
     * "col-$col" 
     * exemple : 6 pour .col-6 et donc affichage sur 2 collones,
     * 4 pour 3 collones,
     * 3 pour 4 colonnes, ...
     */
    public static function fancybox($id, $data, $col = false) {
        $fancybox = new fancybox($id, $data);
        ($col ? $fancybox->in_cols($col) : $fancybox->simple());
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
     * REQUIERT JQUERY-UI
     * Affiche la boite de dialogue de jquery-ui
     * 
     * @param string $name Nom de la boite de dialogue
     * @param string $title Titre de la boite de dialogue
     * @param string $html Contenu de la boite de dialogue ( au format HTML)
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
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $id; ?>").accordion(
        <?php
        if ($collapsible or $heightStyle) {
            echo '{';
            if ($collapsible) {
                echo 'collapsible:true,';
            }
            if ($heightStyle) {
                echo 'heightStyle:"content",';
            }
            echo 'classes:{"ui-accordion-header-active":"ui-accordion-header-active bg-info"}}';
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
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $id; ?>").menu();
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
        $indicators = "";
        foreach ($data as $key => $value) {
            $attr = [
                "data-bs-target" => "#{$id}",
                "data-bs-slide-to" => $key,
                "aria-label" => "Slide {$key}",
            ];
            if ($key == 0) {
                $attr["class"] = "active";
                $attr["aria-current"] = "true";
            }
            $indicators .= tags::tag("button", $attr, "&nbsp;");
        }
        $indicators = tags::tag("div", ["class" => "carousel-indicators"], $indicators);
        $inner = "";
        foreach ($data as $key => $slide) {
            $inner .= tags::tag("div", ["class" => "carousel-item" . ($key ? "" : " active")],
                            tags::tag("img", ["src" => $slide["img"], "class" => "d-block w-100"]) .
                            (isset($slide["caption"]) ?
                            tags::tag("div", ["class" => "carousel-caption d-none d-md-block"], tags::tag("p", [], $slide["caption"])
                            ) : "")
            );
        }
        $inner = tags::tag("div", ["class" => "carousel-inner"], $inner);
        $buttons = tags::tag("button", ["class" => "carousel-control-prev", "type" => "button", "data-bs-target" => $id, "data-bs-slide" => "prev"],
                        tags::tag("span", ["class" => "carousel-control-prev-icon", "aria-hidden" => "true"],
                                tags::tag("span", ["class" => "visually-hidden"], "Previous")
                        )
                ) .
                tags::tag("a", ["href" => "#{$id}", "class" => "carousel-pause", "data-state" => "cycle", "data-bs-target" => "#{$id}"], html_structures::glyphicon("pause", "pause")) .
                tags::tag("button", ["class" => "carousel-control-next", "type" => "button", "data-bs-target" => $id, "data-bs-slide" => "next"],
                        tags::tag("span", ["class" => "carousel-control-next-icon", "aria-hidden" => "true"],
                                tags::tag("span", ["class" => "visually-hidden"], "Next")
                        )
        );
        echo tags::tag("div", ["id" => $id, "class" => "carousel slide"], $indicators . $inner . $buttons);
        ?>
        <script>
            $(document).ready(function () {
                carousel = bootstrap.Carousel.getOrCreateInstance(document.querySelector("#<?= $id; ?>"));
                carousel.cycle();
                $(".carousel-pause").click(function () {
                    if ($(this).attr("data-state") == "cycle") {
                        carousel.pause();
                        $(".carousel-pause > .glyphicon").attr("class", "glyphicon glyphicon-play");
                        $(this).attr("data-state", "pause");
                    } else {
                        carousel.cycle();
                        $(".carousel-pause > .glyphicon").attr("class", "glyphicon glyphicon-pause");
                        $(this).attr("data-state", "cycle");
                    }
                });
                $("#<?= $id ?>").mouseleave(function () {
                    carousel.cycle();
                });
            });
        </script>
        <?php
    }
}
