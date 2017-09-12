<?php

/**
 * Cette classe g�re des fonctions basiques de JavaScript
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class js {

    /**
     * Permet d'afficher le p#real_title � la fin des pages (utilis� par statistiques.class.php)
     * @var string Permet d'afficher le p#real_title � la fin des pages (utilis� par statistiques.class.php)
     */
    public static $_real_title = "";

    /**
     * Ajoute un pr�fixe au titre de la page en cours
     * 
     * @param string $text Pr�fixe au titre
     */
    public static function before_title($text) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("title").text("<?php echo $text; ?> " + $("title").text());
            });
        </script>
        <?php
        if (empty(self::$_real_title)) {
            self::$_real_title = config::$_title;
        }
        self::$_real_title = $text . self::$_real_title;
    }

    /**
     * Affiche un message � l'�cran de l'utilisateur
     * 
     * @param string $msg Message � afficher
     */
    public static function alert($msg) {
        ?>
        <script type="text/javascript">
            alert("<?php echo $msg; ?>");
        </script>
        <?php
    }

    /**
     * Affiche un message � l'�cran de l'utilisateur
     * 
     * @param string $msg Message � afficher
     */
    public static function alertify_alert($msg) {
        ?>
        <script type="text/javascript">
            alertify.alert("<?php echo $msg; ?>");
        </script>
        <?php
    }

    /**
     * Affiche un message � l'�cran de l'utilisateur avant redirection
     * 
     * @param string $msg Message � afficher
     * @param string $url URL de redirection
     */
    public static function alertify_alert_redir($msg, $url) {
        ?>
        <script type="text/javascript">
            alertify.alert("<?php echo $msg; ?>", function (e) {
                window.location = '<?php echo strtr($url . "___", array("&amp;___" => "", "___" => "", "&amp;" => "&")); ?>';
            });</script>
        <?php
    }

    /**
     * Affiche un message de log � l'�cran de l'utilisateur
     * 
     * @param string $msg Log � afficher
     */
    public static function log_std($msg) {
        ?>
        <script type="text/javascript">
            alertify.log("<?php echo $msg; ?>");
        </script>
        <?php
    }

    /**
     * Affiche un message de log � l'�cran de l'utilisateur
     * 
     * @param string $msg Log � afficher
     */
    public static function log_success($msg) {
        ?>
        <script type="text/javascript">
            alertify.success("<?php echo $msg; ?>");
        </script>
        <?php
    }

    /**
     * Affiche un message de log � l'�cran de l'utilisateur
     * 
     * @param string $msg Log � afficher
     */
    public static function log_error($msg) {
        ?>
        <script type="text/javascript">
            alertify.error("<?php echo $msg; ?>");
        </script>
        <?php
    }

    /**
     * Redirige l'utilisateur vers l'url renseign� en param�tre (peut �tre un chemin relatif)
     * 
     * @param string $url Url de redirection
     */
    public static function redir($url) {
        ?>
        <script type="text/javascript">
            window.location = "<?php echo strtr($url . "___", array("&amp;___" => "", "___" => "", "&amp;" => "&")); ?>";
        </script>
        <?php
    }

    /**
     * Affiche un timer avant redirection
     * 
     * @param int $second Nombre de secondes avant redirection
     * @param string $p_id Id de la balise P ou doit �tre affich� le timer
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
                $minuterie_heur = <?php echo $heur; ?>;
                $minuterie_min = <?php echo $minute; ?>;
                $minuterie_sec = <?php echo $second; ?>;
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
                        window.location = "<?php echo $url; ?>";
                    }
                    document.getElementById('<?php echo $p_id; ?>').innerHTML = $minuterie_heur + ':' + $minuterie_min + ':' + $minuterie_sec;
                }, 1000);
            });
        </script>
        <?php
    }

    /**
     * Applique un �diteur CKEditor (WYSIWYG) � un textarea
     * @param array $id Id du textarea
     * @param array $params Surcharge les param�tres � appliquer au CKEditor ( laissez par d�faut ou voir la doc)
     * @return \ckeditor Cette classe permet d'appliquer l'�diteur CKEditor (WYSIWYG) � un textarea 
     */
    public static function ckeditor($id, $params = array()) {
        return new ckeditor($id, $params);
    }

    /**
     * Cr�� un vTicker (suite de phrases qui d�filent)
     * 
     * @param array $data Liste des phrases � afficher
     * @param string $id Id CSS du vTicker
     * @param array $params Surcharge les param�tres � appliquer au flexslider ( laissez par d�faut ou voir la doc ...)
     * @return \vticker Cr�� un vTicker (suite de phrases qui d�filent)
     */
    public static function vTicker($data, $id = "vticker", $params = array()) {
        new vticker($data, $id, $params);
    }

    /**
     * Transforme un tableau HTML en Datatable
     * 
     * @param string $id Id CSS du datatable
     * @param string $params Surcharge les param�tres � appliquer au datatable ( laissez par d�faut ou voir la doc)
     * @return \datatable Applique les fonctionnalit�es de la librairie datatable � un tableau HTML
     */
    public static function datatable($id = "datatable", $params = array()) {
        new datatable($id, $params);
    }

    /**
     * Afficher du code format� et stylis� par la librairie SyntaxHightlighter http://alexgorbatchev.com/SyntaxHighlighter/
     *
     * @param strig $code Le code � afficher
     * @param string $brush Le brush est li� au langage � utiliser (js par deafaut)<br />
     * Astuce script html/php : "php; html-script: true" <br />
     *  http://alexgorbatchev.com/SyntaxHighlighter/manual/brushes/
     * @param string $theme Le theme de SyntaxHightlighter a utiliser http://alexgorbatchev.com/SyntaxHighlighter/manual/themes/
     */
    public static function syntaxhighlighter($code, $brush = "js", $theme = "Default") {
        new syntaxhighlighter($code, $brush, $theme);
    }

    /**
     * Organise dynamiquement les sous �l�ments d'un conteneur avec la librairie jquery "freetile"
     * 
     * @param string $id id CSS du conteneur freetile
     */
    public static function freetile($id) {
        new freetile($id);
    }

    /**
     * Organise dynamiquement les sous �l�ments d'un conteneur avec la librairie jquery "stalactite"
     * 
     * @param string $id id CSS du conteneur stalactite
     */
    public static function stalactite($id) {
        new stalactite($id);
    }

    /**
     * Applique l'effet "shuffleLetters" � un �l�ment au chargement de la page
     * 
     * @param string $id id CSS de l'�l�ment
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
        ?>
        <div Id="<?php echo $name; ?>" title="<?php echo $title; ?>"><?php echo $html; ?></div>
        <script type="text/javascript">
            $("#<?php echo $name; ?>").dialog({autoOpen: true});
        </script>
        <?php
    }

    /**
     * Applique un effet accord�on � une structure : div#$id>(h3+div)*X
     * @param string $id Id CSS de l'accord�on
     * @param boolan $collapsible Si $collapible est pass� � false , un volet de l'accord�on sera toujours ouvert, � true , tout les volets peuvent �tre ferm�s
     * @param boolan $heightStyle La taille d'un volet doit-il �tre relatif � son contenu ? (true / false, false par defaut )
     */
    public static function accordion($id = "accordion", $collapsible = true, $heightStyle = false) {
        ?>
        <script>
            $(document).ready(function () {
            $("#<?php echo $id; ?>").accordion(
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
            $("#<?php echo $id; ?>").menu();
            });</script>
        <?php
    }

    /**
     * Applique un toolpip � un �l�ment ( transforme son "title" en infobulle rapide)
     * @param string $id Id CSS de l'�l�ment
     */
    public static function tooltip($id) {
        ?>
        <script>
                    $(document).ready(function () {
            $("#<?php echo $id; ?>").tooltip();
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
        ?>
        <div id="<?php echo $id; ?>" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?php
                for ($i = 0; $i < count($data); $i++) {
                    ?>
                    <li data-target="#<?php echo $id; ?>" data-slide-to="<?php echo $i; ?>" <?php if ($i == 0) { ?> class="active" <?php } ?>><a href="#"><span class="sr-only">slide <?php echo $i; ?></span></a></li>
                    <?php
                }
                ?>
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <?php
                $first = true;
                foreach ($data as $d) {
                    ?>
                    <div class="item<?php
                    if ($first) {
                        echo " active";
                        $first = false;
                    }
                    ?>">
                        <img src="<?php echo $d["img"]; ?>" alt="<?php
                        if (isset($d["alt"])) {
                            echo $d["alt"];
                        }
                        ?>"> 
                        <?php if (isset($d["caption"])) { ?><div class="carousel-caption"><?php echo $d["caption"]; ?></div><?php } ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- Controls -->
            <a class="left carousel-control" href="#<?php echo $id; ?>" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Pr�c�dent</span>
            </a>
            <a class="right carousel-control" href="#<?php echo $id; ?>" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Suivant</span>
            </a>
            <a class="center carousel-pause" href="#<?php echo $id; ?>" role="button" data-slide="pause">
                <span class="glyphicon glyphicon-pause" aria-hidden="true"></span><span class="sr-only">Pause</span>
            </a>
        </div>
        <?php
    }

}
