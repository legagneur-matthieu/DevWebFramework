<?php

/**
 * Classe de config pour new_app.php
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class config {

    /**
     * Title de new_app, utilisé par "new html5()"
     * @var string Title de de new_app, utilisé par "new html5()"
     */
    public static $_title = "Nouvelle Application";
    public static $_prefix = "new_app";
    public static $_sitemap = false;
}

/**
 * Cette classe permet de créer une nouvelle application dans DWF
 */
class new_app {

    /**
     * Cette classe permet de créer une nouvelle application dans DWF
     */
    public function __construct() {
        $this->classloader();
        service::security_check();
        new html5();
        $this->header();
        $this->form();
        $this->exec();
    }

    /**
     * Entête de new_app
     */
    private function header() {
        ?>
        <style type="text/css">
            form{
                width: 90%;
            }
            .border_right{
                border-right: lightgray solid 1px;
            }
        </style>
        <header class="page-header bg-info">
            <h1>Nouvelle Application <br /><small>Créer une nouvelle application avec DWF</small></h1>
        </header>
        <?php
    }

    /**
     * Formulaire de new_app
     */
    private function form() {
        $form = new form();
        echo $form->get_open_form();
        ?>
        <div class="row">
            <div class="col-sm-6 border_right">
                <?php
                echo $form->open_fieldset("Application") .
                $form->input("Nom du dossier (apparait dans l'url)", "dirname") .
                $form->input("Titre de l'application (apparait dans le \"title\" des page)", "title") .
                $form->input("Préfixe (technique, utilisé pour les sessions, log ...)", "prefix");
                $option = [];
                foreach (hash_algos() as $ha) {
                    switch (strlen(hash($ha, "test"))) {
                        case 8:
                            $force = "non sécurisé/déconseillé";
                            break;
                        case 32:
                            $force = "insuffisant";
                            break;
                        case 40:
                            $force = "très faible";
                            break;
                        case 48:
                            $force = "faible";
                            break;
                        case 56:
                            $force = "médiocre";
                            break;
                        case 64:
                            $force = "moyens";
                            break;
                        case 80:
                            $force = "fort";
                            break;
                        case 96:
                            $force = "très fort";
                            break;
                        case 128:
                            $force = "Excellent/conseillé";
                            break;
                    }
                    $option[] = array($ha, $ha . " (" . $force . ")", ($ha == "sha512"));
                }
                echo $form->select("Hash (hash à utiliser pour chiffrer les mots de passe)", "hash", $option);
                $option = [["default", "Default"]];
                foreach (bootstrap_theme::get_bootstrap_themes() as $theme) {
                    $option[] = [$theme, $theme];
                }
                echo $form->select("Theme", "theme", $option) .
                $form->checkbox("Services interne (un dossier de service sera créé dans le projet)", "srv", "srv") .
                $form->close_fieldset();
                ?>
            </div>
            <div class="col-sm-6">
                <?=
                $form->open_fieldset("Base de données (PDO)") .
                $form->select("type", "pdo_type", [
                    ["mysql", "MySQL", true],
                    ["sqlite", "SQLite"],
                ]) .
                $form->input("Host", "pdo_host", "text", "localhost", false) .
                $form->input("Login", "pdo_login", "text", "", false) .
                $form->input("Password", "pdo_psw", "password", "", false) .
                $form->input("Database", "pdo_dbname") .
                $form->checkbox("Créer la base de données (si elle n'existe pas)", "dbcreate", "1", "", true) .
                $form->close_fieldset();
                ?> 
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-sm-6 border_right">
                <?=
                $form->open_fieldset("Websocket") .
                $form->checkbox("Créer le serveur de websocket dans le projet", "ws", "ws") .
                $form->input("Host", "ws_host", "text", "0.0.0.0", false) .
                $form->input("Port", "ws_port", "number", "9000", false) .
                $form->select("SSL", "ws_ssl", [["1", "true"], ["0", "false", true]]) .
                $form->close_fieldset();
                ?>
            </div>
            <div class="col-sm-6">
                <?=
                $form->open_fieldset("SMTP") .
                $form->input("Host", "smtp_host", "text", "localhost") .
                $form->select("Auth", "smtp_auth", [["1", "true", true], ["0", "false"]]) .
                $form->input("Login", "smtp_login", "text", "", false) .
                $form->input("Password", "smtp_psw", "password", "", false) .
                $form->close_fieldset();
                ?> 
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-sm-5"></div>
            <div class="col-sm-7">
        <?= $form->submit("btn-primary", "Créer le projet"); ?>
            </div>
        </div>
        <hr />
        <?php
        echo $form->get_close_form();
        $this->js();
    }

    /**
     * Js du formulaire
     */
    private function js() {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("title").text("<?= config::$_title ?>");
                $("#pdo_type").change(function () {
                    if ($("#pdo_type").val() == "sqlite") {
                        $("#pdo_host, #pdo_login, #pdo_psw").attr("disabled", "disabled").attr("readonly", "readonly");
                    } else {
                        $("#pdo_host, #pdo_login, #pdo_psw").removeAttr("disabled", "disabled").removeAttr("readonly", "readonly");
                    }
                });
                $("#smtp_auth").change(function () {
                    if ($("#smtp_auth").val() == 0) {
                        $("#smtp_login, #smtp_psw").attr("disabled", "disabled").attr("readonly", "readonly");
                    } else {
                        $("#smtp_login, #smtp_psw").removeAttr("disabled", "disabled").removeAttr("readonly", "readonly");
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Execution du formulaire
     */
    private function exec() {
        if (isset($_POST["dirname"])) {
            if ($_POST["smtp_auth"] == "0") {
                $_POST["smtp_auth"] = "false";
                $_POST["smtp_login"] = "";
                $_POST["smtp_psw"] = "";
            } else {
                $_POST["smtp_auth"] = "true";
            }
            if ($_POST["pdo_type"] == "sqlite") {
                $_POST["pdo_host"] = "";
                $_POST["pdo_login"] = "";
                $_POST["pdo_psw"] = "";
            }
            $this->create_dir();
            $this->create_database();
            js::alertify_alert_redir($_POST["title"] . " a été créé avec succès !", "../" . $_POST["dirname"] . "/index.php");
        }
    }

    /**
     * Fonction de création des dossiers et fichiers des applications
     */
    private function create_dir() {
        $dir = "../" . strtolower($_POST["dirname"]);
        $dir_class = $dir . "/class";
        $dir_entity = $dir . "/class/entity";
        $dir_src = $dir . "/src";
        $dir_src_compact = $dir . "/src/compact";
        $file_index_entity = $dir_entity . "/index.php";
        $file_index = $dir . "/index.php";
        $file_pages = $dir_class . "/pages.class.php";
        $file_config = $dir_class . "/config.class.php";
        $file_htaccess = $dir_class . "/.htaccess";
        mkdir($dir, 0775, true);
        $this->check_create_dir($dir);
        mkdir($dir_class, 0775, true);
        $this->check_create_dir($dir_class);
        mkdir($dir_entity, 0775, true);
        $this->check_create_dir($dir_entity);
        mkdir($dir_src, 0775, true);
        $this->check_create_dir($dir_src);
        mkdir($dir_src_compact, 0775, true);
        $this->check_create_dir($dir_src_compact);
        /* index_entity */
        $index_entity = '<?php header("Location: ../../index.php");';
        $this->create_file($file_index_entity, $index_entity);
        /* htaccess */
        $htaccess = "Order Deny,Allow \n Deny from All \n Allow from localhost";
        $this->create_file($file_htaccess, $htaccess);
        /* index */
        $this->create_file($file_index, file_get_contents("./new_app_tpl/index"));
        /* page */
        $pages = strtr(file_get_contents("./new_app_tpl/pages"), [
            "{{TITLE}}" => $_POST["title"],
            "{{YEAR}}" => date("Y")
        ]);
        $this->create_file($file_pages, $pages);
        /* config */
        $config = strtr(file_get_contents("./new_app_tpl/config"), [
            "{{PDO_TYPE}}" => $_POST["pdo_type"],
            "{{PDO_HOST}}" => $_POST["pdo_host"],
            "{{PDO_DBNAME}}" => $_POST["pdo_dbname"],
            "{{PDO_LOGIN}}" => $_POST["pdo_login"],
            "{{PDO_PSW}}" => $_POST["pdo_psw"],
            "{{HASH_ALGO}}" => $_POST["hash"],
            "{{TITLE}}" => $_POST["title"],
            "{{PREFIX}}" => $_POST["prefix"],
            "{{THEME}}" => $_POST["theme"],
            "{{SMTP_HOST}}" => $_POST["smtp_host"],
            '"{{SMTP_AUTH}}"' => $_POST["smtp_auth"],
            "{{SMTP_LOGIN}}" => $_POST["smtp_login"],
            "{{SMTP_PSW}}" => $_POST["smtp_psw"],
            "{{WS_HOST}}" => $_POST["ws_host"],
            "{{WS_PORT}}" => $_POST["ws_port"],
            '"{{WS_SSL}}"' => $_POST["ws_ssl"],
        ]);
        $this->create_file($file_config, $config);
        //services
        if (isset($_POST["srv"])) {
            $dir_services = $dir . "/services";
            mkdir($dir_services, 0775, true);
            $this->check_create_dir($dir_services);
            $this->create_file($dir_services . "/index.php", file_get_contents("./new_app_tpl/services"));
        }
        //websocket
        if (isset($_POST["ws"])) {
            $dir_ws = $dir . "/websocket";
            mkdir($dir_ws, 0775, true);
            $this->check_create_dir($dir_ws);
            $this->create_file($dir_ws . "/index.php", file_get_contents("./new_app_tpl/ws"));
        }
    }

    /**
     * Fonction de création de fichier
     * @param string $filename nom de fichier
     * @param string $data contenue
     */
    private function create_file($filename, $data) {
        file_put_contents($filename, $data);
        $this->check_create_dir($filename);
    }

    /**
     * Vérifie si un dossier ou un fichier est créé, ARRETE IMMEDIATEMENT LE SCRIPT EN CAS D'ERREUR
     * @param string $file_or_dir Dossier ou fichier
     */
    private function check_create_dir($file_or_dir) {
        if (!file_exists($file_or_dir)) {
            js::alert($file_or_dir . " n'a pu être créé, merci de vérifier droits du dossier HTML");
        }
    }

    /**
     * Créé la base de données
     */
    private function create_database() {
        try {
            switch ($_POST["pdo_type"]) {
                case "sqlite":
                    file_put_contents("../" . strtolower($_POST["dirname"]) . "/class/entity/" . $_POST["pdo_dbname"] . ".sqlite", "");
                    break;
                case "mysql":
                default:
                    (new PDO("mysql:host=" . $_POST["pdo_host"], $_POST["pdo_login"], $_POST["pdo_psw"]))->query("create database if not exists " . addslashes($_POST["pdo_dbname"]));
                    break;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Inclut toutes les classes du dossier "dwf/class" se finissant par ".class.php" <br /> 
     * Vous pouvez créer vos propres classes avec cette extension pour les charger automatiquement avant de les utiliser dans votre application 
     */
    private function classloader() {
        spl_autoload_register(function ($class) {
            $file = __DIR__ . "/../../dwf/class/" . $class . ".class.php";
            if (file_exists($file)) {
                require_once $file;
            }
        });
    }
}

new new_app();
