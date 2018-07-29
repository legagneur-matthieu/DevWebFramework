<?php

class parcour_sites {

    public function __construct() {
        if (!in_array($_SERVER["REMOTE_ADDR"], array("localhost", "127.0.0.1", "::1"))) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Status: 301 Moved Permanently", false, 301);
            header("Location: ./doc/index.php");
            exit();
        }
        spl_autoload_register(function($class) {
            if (file_exists($filename = "../dwf/class/" . $class . ".class.php")) {
                include_once $filename;
            }
        });
        ob_start();
        ?>
        <!DOCTYPE HTML>
        <html lang="fr">
            <?=
            tags::tag("head", [], tags::tag(
                            "meta", ["charset" => "UTF-8"]) .
                    tags::tag("meta", ["name" => "viewport", "content" => "width=device-width, initial-scale=1.0"]) .
                    tags::tag("title", [], "Parcour des sites") .
                    tags::tag("link", ["rel" => "stylesheet", "href" => "commun/src/dist/css/bootstrap.min.css"]) .
                    tags::tag("link", ["rel" => "stylesheet", "href" => "commun/src/dist/css/bootstrap-theme.min.css"]) .
                    tags::tag("link", ["rel" => "stylesheet", "href" => "commun/src/css/style.css"]) .
                    tags::tag("script", ["type" => "stylesheet", "src" => "commun/src/dist/jquery-ui/jquery.js"], "") .
                    tags::tag("script", ["type" => "stylesheet", "src" => "commun/src/dist/jquery-ui/jquery-ui.min.js"], "")
            );
            ?>
            <body>
                <?=
                tags::tag("header", ["class" => "page-header label-info"], tags::tag(
                                "h1", [], "Parcours des projets " . tags::tag("br") .
                                tags::tag("small", [], "Liste des projets présent dans DWF")
                        )
                );
                ?>
                <main class="contenu">
                    <?php
                    if (!isset($_GET["check_update"])) {
                        $sites = "";
                        foreach (glob("*") as $site) {
                            if (is_dir($site) and $site != "commun") {
                                $sites .= html_structures::a_link($site . "/", html_structures::glyphicon("folder-open") . " &nbsp;" . $site) . tags::tag("br");
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col-sm-4 col-xs-6">
                                <?= tags::tag("h2", [], "Vos projets") . tags::tag("p", [], $sites); ?>
                            </div>
                            <div class="col-sm-4 col-xs-6">
                                <?php
                                echo tags::tag("h2", [], "Outils DWF") .
                                tags::tag("p", [], tags::tag("a", ["href" => "commun/new_app.php"], html_structures::glyphicon("plus") . " Ajouter un projet") . tags::tag("br") .
                                        tags::tag("a", ["href" => "http://localhost/phpmyadmin/"], html_structures::glyphicon("hdd") . " PHPMyAdmin") . tags::tag("br") .
                                        tags::tag("a", ["href" => "index.php?check_update=1"], html_structures::glyphicon("cloud-download") . " Mise à jour de DWF (Requiert GIT)")
                                ) .
                                tags::tag("hr") .
                                tags::tag("h2", [], "DWF Status");
                                $DWFStatus = true;
                                if (!class_exists("tidy")) {
                                    $DWFStatus = false;
                                    echo tags::tag("div", ["class" => "alert alert-warning"], tags::tag("p", [], "L'extention PHP Tidy est recomandé"));
                                }
                                if ($DWFStatus) {
                                    echo tags::tag("div", ["class" => "alert alert-success"], tags::tag("p", [], "DWF est fonctionnel"));
                                }
                                ?>
                            </div>
                            <div class="col-sm-4"></div>
                        </div>
                        <?php
                    } else {
                        echo html_structures::a_link("index.php", html_structures::glyphicon("arrow-left", "") . " Retour au parcours", "btn btn-primary");
                        new update_dwf("../");
                    }
                    ?>
                </main>
                <footer> <hr /> </footer>
            </body>
        </html>
        <?php
        html5::render(ob_get_clean());
    }

}

new parcour_sites();
