<?php

class parcour_sites {

    private $_default = "commun/conf/default.json";
    private $_project = [];

    public function __construct() {
        $this->init();
        ob_start();
        ?>
        <!DOCTYPE HTML>
        <html lang="fr">
            <?= $this->head(); ?>
            <body>
                <?= $this->header(); ?>
                <main class="contenu">
                    <?php
                    if (isset($_GET["check_update"])) {
                        echo html_structures::a_link("index.php", html_structures::glyphicon("arrow-left", "") . " Retour au parcours", "btn btn-primary");
                        new update_dwf("../");
                    } elseif (isset($_GET["phpini"])) {
                        echo html_structures::a_link("index.php", html_structures::glyphicon("arrow-left", "") . " Retour au parcours", "btn btn-primary");
                        phpini::admin();
                    } else {
                        $this->main($this->init_main());
                    }
                    ?>
                </main>
                <footer> <hr /> </footer>
            </body>
        </html>
        <?php
        html5::render(ob_get_clean());
    }

    private function init() {
        if (!file_exists($this->_default)) {
            file_put_contents($this->_default, json_encode(["project" => "doc"]));
        }
        $this->_project = json_decode(file_get_contents($this->_default), true);
        $this->location();
        $this->autoloader();
        if (isset($_POST["default_project"])) {
            file_put_contents($this->_default, json_encode(["project" => $_POST["default_project"]]));
            js::redir("index.php");
            exit();
        }
    }

    private function autoloader() {
        spl_autoload_register(function($class) {
            if (file_exists($filename = "../dwf/class/" . $class . ".class.php")) {
                include_once $filename;
            } elseif (file_exists($filename = "./dwf/class/" . $class . ".class.php")) {
                include_once $filename;
            }
        });
    }

    private function location() {
        if (!in_array($_SERVER["REMOTE_ADDR"], array("localhost", "127.0.0.1", "::1"))) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Status: 301 Moved Permanently", false, 301);
            header("Location: ./" . $this->_project["project"] . "/index.php");
            exit();
        }
    }

    private function head() {
        return tags::tag("head", [], tags::tag(
                                "meta", ["charset" => "UTF-8"]) .
                        tags::tag("meta", ["name" => "viewport", "content" => "width=device-width, initial-scale=1.0"]) .
                        tags::tag("title", [], "Parcours des sites") .
                        tags::tag("link", ["rel" => "stylesheet", "href" => "commun/src/dist/css/bootstrap-reboot.min.css"]) .
                        tags::tag("link", ["rel" => "stylesheet", "href" => "commun/src/dist/css/bootstrap-glyphicon.min.css"]) .
                        tags::tag("link", ["rel" => "stylesheet", "href" => "commun/src/dist/css/bootstrap-grid.min.css"]) .
                        tags::tag("link", ["rel" => "stylesheet", "href" => "commun/src/dist/css/bootstrap.min.css"]) .
                        tags::tag("link", ["rel" => "stylesheet", "href" => "commun/src/css/style.css"]) .
                        tags::tag("link", ["rel" => "stylesheet", "href" => "commun/src/js/alertify/themes/alertify.core.css"]) .
                        tags::tag("link", ["rel" => "stylesheet", "href" => "commun/src/js/alertify/themes/alertify.bootstrap.css"]) .
                        html_structures::script("commun/src/dist/jquery-ui/external/jquery/jquery.js") .
                        html_structures::script("commun/src/dist/jquery-ui/jquery-ui.min.js") .
                        html_structures::script("commun/src/js/alertify/lib/alertify.min.js")
        );
    }

    private function header() {
        return tags::tag("header", ["class" => "page-header bg-info"], tags::tag(
                                "h1", [], "Parcours des projets " . tags::tag("br") .
                                tags::tag("small", [], "Liste des projets présent dans DWF")
                        )
        );
    }

    private function init_main() {
        $sites = "";
        $option = [];
        foreach (glob("*") as $site) {
            if (is_dir($site) and $site != "commun") {
                $sites .= html_structures::a_link($site . "/", html_structures::glyphicon("folder-open") . " &nbsp;" . $site) . tags::tag("br");
                $option[] = [$site, $site, $site === $this->_project["project"]];
            }
        }
        $select = new form();
        $select->select("Projet par defaut (ou d'accueil)", "default_project", $option);
        $select->submit("btn-primary");
        return ["sites" => $sites, "select" => $select->render()];
    }

    private function main($s) {
        ?>
        <div class="row">
            <div class="col-sm-4 col-sm-6">
                <?= tags::tag("h2", [], "Vos projets") . tags::tag("p", [], $s["sites"]); ?>
            </div>
            <div class="col-sm-4 col-sm-6">
                <?php
                echo tags::tag("h2", [], "Outils DWF") .
                tags::tag("p", [], tags::tag("a", ["href" => "commun/new_app.php"], html_structures::glyphicon("plus") . " Ajouter un projet") . tags::tag("br") .
                        tags::tag("a", ["href" => "http://localhost/phpmyadmin/"], html_structures::glyphicon("hdd") . " PHPMyAdmin") . tags::tag("br") .
                        tags::tag("a", ["href" => "index.php?phpini=1"], html_structures::glyphicon("cog") . " PHPini") . tags::tag("br") .
                        tags::tag("a", ["href" => "index.php?check_update=1"], html_structures::glyphicon("cloud-download") . " Mise à jour de DWF (Requiert GIT)")
                ) .
                tags::tag("hr") . $s["select"] . tags::tag("hr") .
                tags::tag("h2", [], "DWF Status");
                $this->dwf_statut();
                ?>
            </div>
            <div class="col-sm-4"></div>
        </div>
        <?php
    }

    private function dwf_statut() {
        $DWFStatus = true;
        $conf = "./commun/conf/default.json";
        if (!is_writable($conf) or ! is_readable($conf)) {
            $DWFStatus = false;
            echo tags::tag("div", ["class" => "alert alert-warning"], tags::tag("p", [], "Le fichier " . $conf . " n'est pas accessible en lecture/ecriture"));
        }
        $conf = "../dwf/log";
        if (!is_writable($conf) or ! is_readable($conf)) {
            $DWFStatus = false;
            echo tags::tag("div", ["class" => "alert alert-warning"], tags::tag("p", [], "Le dossier " . $conf . " n'est pas accessible en lecture/ecriture"));
        }
        if (!class_exists("tidy")) {
            $DWFStatus = false;
            echo tags::tag("div", ["class" => "alert alert-warning"], tags::tag("p", [], "L'extention PHP Tidy est recomandé"));
        }
        if (!function_exists("gmp_init")) {
            $DWFStatus = false;
            echo tags::tag("div", ["class" => "alert alert-warning"], tags::tag("p", [], "L'extention PHP GMP est recomandé"));
        }
        if ($DWFStatus) {
            echo tags::tag("div", ["class" => "alert alert-success"], tags::tag("p", [], "DWF est fonctionnel"));
        }
    }

}

new parcour_sites();
