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
        ?>
        <!DOCTYPE HTML>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Parcour des sites</title>
                <link rel="stylesheet" href="commun/src/dist/css/bootstrap.min.css" />
                <link rel="stylesheet" href="commun/src/dist/css/bootstrap-theme.min.css" />
                <link rel="stylesheet" href="commun/src/css/style.css" />
                <script type="text/javascript" src="commun/src/dist/jquery-ui/jquery.js"></script>
                <script type="text/javascript" src="commun/src/dist/jquery-ui/jquery-ui.min.js"></script>
            </head>
            <body>
                <header class="page-header label-info">
                    <h1>Parcours des projets <br /><small>Liste des projets présent dans DWF</small></h1>
                </header>
                <main class="contenu">
                    <?php
                    if (!isset($_GET["check_update"])) {
                        ?>
                        <div class="row">
                            <div class="col-sm-4 col-xs-6">
                                <h2>Vos projets</h2>
                                <p>
                                    <?php
                                    foreach (glob("*") as $site) {
                                        if (is_dir($site) and $site != "commun") {
                                            echo html_structures::a_link($site . "/", html_structures::glyphicon("folder-open", "") . " &nbsp;" . $site) . "<br />";
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="col-sm-4 col-xs-6">
                                <h2>Outils DWF</h2>
                                <p>
                                    <a href="commun/new_app.php"> <span class="glyphicon glyphicon-plus"></span> Ajouter un projet</a> <br />
                                    <a href="http://localhost/phpmyadmin/"> <span class="glyphicon glyphicon-hdd"></span> PHPMyAdmin</a> <br />
                                    <a href="index.php?check_update=1"> <span class="glyphicon glyphicon-cloud-download"></span> Mise à jour de DWF (Requiert GIT)</a>
                                </p>
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
    }

}

new parcour_sites();
