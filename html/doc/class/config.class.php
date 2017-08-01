<?php

/** * Cette classe sert de fichier de configuration, <br /> * elle contient: * <ul> * <li>les variables de connexion à la base de données</li> * <li>l 'algo utilisé pour les hash</li> * <li>les routes de l 'aplication</li> * </ul> * * mais vous pouvez également y ajouter des variables diverses qui vous seront utile */
class config { /* PDO */

    public static $_PDO_type = "sqlite";
    public static $_PDO_host = "";
    public static $_PDO_dbname = "doc";
    public static $_PDO_login = "";
    public static $_PDO_psw = "";
    /* hash */
    public static $_hash_algo = "whirlpool";
    /* routes */
    public static $_route_auth = array();
    public static $_route_unauth = array(); /* Data */
    public static $_title = "Documentation";
    public static $_favicon = "";
    public static $_debug = true;
    public static $_prefix = "doc";
    public static $_theme = "default";
    public static $_SMTP_host = "localhost";
    public static $_SMTP_auth = false;
    public static $_SMTP_login = "";
    public static $_SMTP_psw = "";
    public static $_sitemap = false;
    public static $_statistiques = false;

    public static function onbdd_connected() {
        self::$_route_auth = array(
            array("page" => "index", "title" => "Page d 'accueil", "text" => "Accueil", "description" => "Index de Documentation", "keyword" => "Index, Documentation"),
            array("page" => "deco", "title" => "Deconnexion", "text" => "Deconnexion"),
        );
        self::$_route_unauth = array(
            array("page" => "index", "title" => "Page d 'accueil", "text" => "Accueil", "description" => "Index de Documentation", "keyword" => "Index, Documentation, DWF"),
            array("page" => "web", "title" => "Framework PHP", "text" => "Framework PHP", "description" => "Documentation du framework PHP", "keyword" => "Framework, PHP, Documentation, DWF"),
            array("page" => "mobile", "title" => "Framework Mobile", "text" => "Framework Mobile", "description" => "Documentation du framework Mobile", "keyword" => "Framework, Mobile, Documentation, DWF"),
            array("page" => "tiers", "title" => "Librairies Tiers", "text" => "Librairies Tiers", "description" => "Librairies tiers dans les frameworks", "keyword" => "Librairies, Frameworks, PHP, Mobile, Documentation, DWF"),
        );
    }

}
