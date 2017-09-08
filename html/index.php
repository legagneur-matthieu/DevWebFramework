<?php

class parcour_sites {

    public function __construct() {
        if ($_SERVER["HTTP_HOST"] != "localhost") {
            header("HTTP/1.1 301 Moved Permanently");
            header("Status: 301 Moved Permanently", false, 301);
            header("Location: ./doc/index.php");
            exit();
        }
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
                    <h1>Parcours des sites <br /><small>Liste des sites présent dans DWF</small></h1>
                </header>
                <main class="contenu">
                    <ul>
                        <?php
                        foreach (glob("*") as $site) {
                            if (is_dir($site) and $site != "commun") {
                                ?>
                                <li><a href="<?php echo $site; ?>/"><?php echo $site; ?></a></li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </main>
                <footer> <hr /> </footer>
            </body>
        </html>
        <?php
    }

    private function classloader() {
        foreach (glob("../dwf/class/*.class.php") as $class) {
            include_once $class;
        }
    }

}

new parcour_sites();
