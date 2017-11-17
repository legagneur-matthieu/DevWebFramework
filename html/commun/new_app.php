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
        <header class="page-header label-info">
            <h1>Nouvelle Application <br /><small>Créer une nouvelle application avec DWF</small></h1>
        </header>
        <?php
    }

    /**
     * Formulaire de new_app
     */
    private function form() {
        form::new_form();
        ?>
        <div class="row">
            <div class="col-xs-4 border_right">
                <?php
                form::new_fieldset("Application");
                form::input("Nom du dossier (apparait dans l'url)", "dirname");
                form::input("Titre de l'application (apparait dans le \"title\" des page)", "title");
                form::input("Préfixe (technique, utilisé pour les sessions, log ...)", "prefix");
                $option = array();
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
                form::select("Hash (hash à utiliser pour chiffrer les mots de passe)", "hash", $option);
                form::select("Theme", "theme", array(
                    array("default", "Default"),
                    array("cerulean", "Cerulean"),
                    array("cosmo", "Cosmo"),
                    array("cyborg", "Cyborg"),
                    array("darkly", "Darkly"),
                    array("flatly", "Flatly"),
                    array("journal", "Journal"),
                    array("lumen", "Lumen"),
                    array("paper", "Paper"),
                    array("readable", "Readable"),
                    array("sandstone", "Sandstone"),
                    array("simplex", "Simplex"),
                    array("slate", "Slate"),
                    array("spacelab", "Spacelab"),
                    array("superhero", "Superhero"),
                    array("united", "United"),
                    array("yeti", "Yeti")
                ));
                form::checkbox("Services interne (un dossier de service sera créé dans le projet)", "srv", "srv");
                form::close_fieldset();
                ?>
            </div>
            <div class="col-xs-4 border_right">
                <?php
                form::new_fieldset("PDO");
                form::select("type", "pdo_type", array(
                    array("mysql", "MySQL", true),
                    array("sqlite", "SQLite (déconseillé !)"),
                ));
                form::input("Host", "pdo_host", "text", "localhost", false);
                form::input("Login", "pdo_login", "text", "", false);
                form::input("Password", "pdo_psw", "password", "", false);
                form::input("Database", "pdo_dbname");
                form::checkbox("Créer la base de donnée (si elle n'existe pas)", "dbcreate", "1", "", true);
                form::close_fieldset();
                ?> 
            </div>
            <div class="col-xs-4">
                <?php
                form::new_fieldset("SMTP");
                form::input("Host", "smtp_host", "text", "localhost");
                form::select("Auth", "smtp_auth", array(array("1", "true", true), array("0", "false")));
                form::input("Login", "smtp_login", "text", "", false);
                form::input("Password", "smtp_psw", "password", "", false);
                form::close_fieldset();
                ?> 
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4"></div>
            <div class="col-xs-4"></div>
            <div class="col-xs-4">
                <?php
                form::submit("btn-primary", "Créer");
                ?>
            </div>
        </div>
        <?php
        form::close_form();
        $this->js();
    }

    /**
     * Js du formulaire
     */
    private function js() {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#pdo_type").change(function () {
                    if ($("#pdo_type").val() == "sqlite") {
                        $("#pdo_host").attr("disabled", "disabled");
                        $("#pdo_host").attr("readonly", "readonly");
                        $("#pdo_login").attr("disabled", "disabled");
                        $("#pdo_login").attr("readonly", "readonly");
                        $("#pdo_psw").attr("disabled", "disabled");
                        $("#pdo_psw").attr("readonly", "readonly");
                    } else {
                        $("#pdo_host").removeAttr("disabled", "disabled");
                        $("#pdo_host").removeAttr("readonly", "readonly");
                        $("#pdo_login").removeAttr("disabled", "disabled");
                        $("#pdo_login").removeAttr("readonly", "readonly");
                        $("#pdo_psw").removeAttr("disabled", "disabled");
                        $("#pdo_psw").removeAttr("readonly", "readonly");
                    }
                });
                $("#smtp_auth").change(function () {
                    if ($("#smtp_auth").val() == 0) {
                        $("#smtp_login").attr("disabled", "disabled");
                        $("#smtp_login").attr("readonly", "readonly");
                        $("#smtp_psw").attr("disabled", "disabled");
                        $("#smtp_psw").attr("readonly", "readonly");
                    } else {
                        $("#smtp_login").removeAttr("disabled");
                        $("#smtp_login").removeAttr("readonly");
                        $("#smtp_psw").removeAttr("disabled");
                        $("#smtp_psw").removeAttr("readonly");
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
            js::alert($_POST["title"] . " a été créé avec succès !");
            js::redir("../" . $_POST["dirname"] . "/index.php");
        }
    }

    /**
     * Fonction de création des dossiers et fichiers des applications
     */
    private function create_dir() {
        $dir = "../" . strtolower($_POST["dirname"]);
        $dir_class = $dir . "/class";
        $dir_entity = $dir . "/class/entity";
        $file_index_entity = $dir_entity . "/index.php";
        $file_index = $dir . "/index.php";
        $file_pages = $dir_class . "/pages.class.php";
        $file_config = $dir_class . "/config.class.php";
        $file_htaccess = $dir_class . "/.htaccess";
        mkdir($dir);
        $this->check_create_dir($dir);
        mkdir($dir_class);
        $this->check_create_dir($dir_class);
        mkdir($dir_entity);
        $this->check_create_dir($dir_entity);
        /* index_entity */
        $index_entity = '<?php header("Location: ../../index.php");';
        $this->create_file($file_index_entity, $index_entity);
        /* htaccess */
        $htaccess = "Order Deny,Allow \n Deny from All \n Allow from localhost";
        $this->create_file($file_htaccess, $htaccess);
        /* index */
        $index = '<?php class website { /** * Liste des classes metier et classes natives chargé par le framework * @var array Liste des classes metier et classes natives chargé par le framework */ public static $_class; /** * point de départ du site web */ public function __construct() { self::$_class[__FILE__] = __CLASS__; spl_autoload_register([__CLASS__, "classloader"]); require_once "../../dwf/index.php"; try { new index(); } catch (Exception $e) { dwf_exception::print_exception($e); } } /** * Inclut toutes les classes du dossier "class" se finissant par ".class.php" * Vous pouvez créer vos propres classes avec cette extension pour les charger automatiquement avant de les utiliser dans votre application */ private static function classloader($class) { $file = __DIR__ . "/class/" . $class . ".class.php"; if (file_exists($file)) { require_once $file; self::$_class[$file] = $class; } } } new website(); ';
        $this->create_file($file_index, $index);
        /* page */
        $pages = '<?php /** * Cette classe sert de "Vue" à votre application, * vous pouvez y développer votre application comme bon vous semble : * HTML, créér et appeler une fonction "private" dans une fonction "public", faire appel à des classes exterieures ... * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> */ class pages { /** * Cette classe sert de "Vue" à votre application, * vous pouvez y développer votre application comme bon vous semble : * HTML, créé et appelle une fonction "private" dans une fonction "public", faire appel à des classes exterieures ... */ public function __construct() { new robotstxt();} /** * Entete des pages */ public function header() { ?> <header class="page-header label-info"> <h1>' . $_POST["title"] . ' <br /><small>Description de ' . $_POST["title"] . '</small></h1> </header> <?php } /** * Pied des pages */ public function footer() { ?> <footer> <hr /> <p> ' . date("Y") . '-<?php echo date("Y"); ?> D&eacute;velopp&eacute; par [VOUS]</p> <!--[if (IE 6)|(IE 7)]> <p><big>Ce site n\'est pas compatible avec votre version d\'internet explorer !</big></p> <![endif]--> </footer> <?php } /** * Fonction par défaut / page d\'accueil */ public function index() { ?> <p>[Votre contenu]</p> <?php } /** * Exemple de login */ public function login() { $auth = new auth("user", "login", "psw"); if (session::get_auth()) { js::redir("index.php"); } } public function deco() { auth::unauth(); js::redir("index.php"); } } ';
        $this->create_file($file_pages, $pages);
        /* config */
        $config = '<?php /** * Cette classe sert de fichier de configuration, <br /> * elle contient: * <ul> * <li>les variables de connexion à la base de données</li> * <li>l \'algo utilisé pour les hash</li> * <li>les routes de l \'aplication</li> * </ul> * * mais vous pouvez également y ajouter des variables diverses qui vous seront utile */ class config { /*PDO*/ public static $_PDO_type = "' . $_POST["pdo_type"] . '"; public static $_PDO_host = "' . $_POST["pdo_host"] . '"; public static $_PDO_dbname = "' . $_POST["pdo_dbname"] . '"; public static $_PDO_login = "' . $_POST["pdo_login"] . '"; public static $_PDO_psw = "' . $_POST["pdo_psw"] . '"; /*hash*/ public static $_hash_algo = "' . $_POST["hash"] . '"; /*routes*/ public static $_route_auth = array(); public static $_route_unauth = array(); /*Data*/ public static $_title = "' . $_POST["title"] . '"; public static $_favicon =""; public static $_debug = true; public static $_prefix = "' . $_POST["prefix"] . '"; public static $_theme = "' . $_POST["theme"] . '"; public static $_SMTP_host = "' . $_POST["smtp_host"] . '"; public static $_SMTP_auth = ' . $_POST["smtp_auth"] . '; public static $_SMTP_login = "' . $_POST["smtp_login"] . '"; public static $_SMTP_psw = "' . $_POST["smtp_psw"] . '"; public static $_sitemap = false; public static $_statistiques = false; public static function onbdd_connected() {self::$_route_auth = array(array("page" => "index", "title" => "Page d\'accueil", "text" => "ACCUEIL", "description" => "Index de devwebframework", "keyword" => "Index, devwebframework, DWF"),array("page" => "deco", "title" => "Deconnexion", "text" => "DECONNEXION"),); self::$_route_unauth = array(array("page" => "index", "title" => "Page d\'accueil", "text" => "ACCUEIL", "description" => "Index de devwebframework", "keyword" => "Index, devwebframework, DWF"), array("page" => "login", "title" => "Login", "text" => "LOGIN", "description" => "Connexion a devwebframework", "keyword" => "login, devwebframework, DWF"),); }}';
        $this->create_file($file_config, $config);
        if (isset($_POST["srv"])) {
            $dir_services = $dir . "/services";
            $file_services = '<?php class index_service { /** * Cette classe est la première appelée, elle ouvre les variables de session et la connexion a la base de donnée, <br /> * redéfinie la time zone et fait appel à ces méthodes privées avant d\'appeler la class application (IDEM __construct()...) */ public function __construct() { try { $this->classloader(); include "../class/config.class.php"; if (isset($_REQUEST["service"])) { date_default_timezone_set("Europe/Paris"); $this->entityloader(); application::$_bdd = new bdd(); session::start(false); $this->serviceloader(); $this->security_purge(); $service = strtr($_REQUEST["service"], array("." => "", "/" => "", "\\\\" => "", "?" => "", "#" => "")); if (file_exists($service . ".service.php")) { $service = new $service(); } else { dwf_exception::throw_exception(622, array("_s_" => $service)); } } else { dwf_exception::throw_exception(621); } } catch (Exception $e) { dwf_exception::print_exception($e, "", true); } } /** * Inclut toutes les classes du framework */ private function classloader() { foreach (glob("../../../dwf/class/*.class.php") as $class) { include_once $class; } } /** * Inclut les entités du projet */ private function entityloader() { foreach (glob("../class/entity/*.class.php") as $class) { include_once $class; } } /** * Inclut toutes les classes du dossier "service" se finissant par ".service.php" */ private function serviceloader() { foreach (glob("*.service.php") as $class) { include_once $class; } } /** * Supprime tous les fichiers se terminant par .php~ (trill) dans le dossier "service" <br /> * Cette fonction est recommandée sur les serveurs de production Linux pour des raisons de sécurité <br /> * certains hébergeurs tolèrent mal cette fonction, elle peut être désactivée en commentant la ligne "$this->security_purge();" dans le constructeur. */ private function security_purge() { foreach (glob("*.php~") as $trill) { unlink($trill); } }}new index_service();';
            mkdir($dir_services);
            $this->check_create_dir($dir_services);
            $this->create_file($dir_services . "/index.php", $file_services);
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
            exit();
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
            exit();
        }
    }

    /**
     * Inclut toutes les classes du dossier "dwf/class" se finissant par ".class.php" <br /> 
     * Vous pouvez créer vos propres classes avec cette extension pour les charger automatiquement avant de les utiliser dans votre application 
     */
    private function classloader() {
        foreach (glob("../../dwf/class/*.class.php") as $class) {
            include_once $class;
        }
    }

}

new new_app();
