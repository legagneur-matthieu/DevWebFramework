<?php

/**
 * Cet objet gère la connexion et les traitements de la base de données
 * Quelques fonctions servent à vérifier ou protéger les données
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class bdd extends singleton {

    /**
     * Instance de PDO
     * 
     * @var PDO Instance de PDO
     */
    private $_pdo;

    /**
     * Tableau de débogage 
     * @var array Tableau de débogage 
     */
    public static $_debug = [
        "nb_req" => 0,
        "memory" => 0,
        "satatements" => []
    ];

    /**
     * Cet objet gère la connexion et les traitements de la base de données
     * Quelques fonctions servent à vérifier ou protéger les données
     *
     */
    public function __construct() {
        $pdo_type = (isset(config::$_PDO_type) ? config::$_PDO_type : "mysql");
        switch ($pdo_type) {
            case "sqlite":
                $this->_pdo = new PDO("sqlite:class/entity/" . config::$_PDO_dbname . ".sqlite");
                $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                break;
            case "mysql":
            default:
                try {
                    $this->_pdo = new PDO("mysql:host=" . config::$_PDO_host . ";dbname=" . config::$_PDO_dbname, config::$_PDO_login, config::$_PDO_psw);
                } catch (PDOException $PDOe) {
                    if ($PDOe->getCode() == 1049) {
                        (new PDO("mysql:host=" . config::$_PDO_host . ";", config::$_PDO_login, config::$_PDO_psw))->query("CREATE DATABASE IF NOT EXISTS " . config::$_PDO_dbname . ";");
                        js::redir("");
                    } else {
                        try {
                            dwf_exception::throw_exception(601);
                        } catch (dwf_exception $e) {
                            dwf_exception::print_exception($e, "<p>" . utf8_encode($PDOe->getMessage()) . "</p>");
                            exit();
                        }
                    }
                }
                $this->query("SET NAMES UTF8;");
                break;
        }
    }

    /**
     * Cette fonction sert à sécuriser une variable destinée à être enregistrée en base de données 
     * contre les attaques SQL et JavaScript.
     * Cette fonction est systématiquement utilisée dans les méthodes "query" et "fetch" de la class bdd
     * 
     * @param string $var variable à protégée
     * @return string variable protégé
     */
    public function protect_var($var) {
        return addslashes(htmlspecialchars(htmlspecialchars_decode($var), ENT_NOQUOTES));
    }

    /**
     * Cette fonction suprimme les protections de la fonction protect_var() afin que la variable puisse être 
     * réexploitée sans problèmes.
     * 
     * @param string $var variable à afficher
     * @return string variable affichée
     */
    public function unprotect_var($var) {
        return stripcslashes($var);
    }

    /**
     * Vérifie la validité d'une adresse mail
     * 
     * @param string $email email à vérifier
     * @return boolean adresse valide : true/false
     */
    public function verif_email($email) {
        return boolval(filter_var($email, FILTER_VALIDATE_EMAIL));
    }

    /**
     * ATTENTION : cette fonction a été créé temporairement en attendant la refonte
     * de entity_generator.class.php (qui a été faite en version 21.18.03 afin de gérer proprement des JSON en base de donnée)
     * cette fonction sera supprimé dans la version 21.18.04 de DWF !
     * 
     * Convertis un json provenant de la base de donné en tableau
     * 
     * @param string $json json provenant de la base de donné
     * @return array Tableau de donnée
     */
    public function json_decode($json) {
        return json_decode(strtr($json, ["&quot;" => '"']), true);
    }

    /**
     * Exécute une requête type update, insert ou delete.
     * Pour plus de securité: voir la méthode protect_var.
     *
     * @param string|statement $statement requête SQL
     */
    public function query($statement) {
        self::$_debug["nb_req"] ++;
        self::$_debug["statements"][] = ["req" => $statement, "trace" => (new dwf_exception("Trace", 700))->getTraceAsString()];
        if (isset(config::$_PDO_type) and config::$_PDO_type == "sqlite") {
            $statement = $this->mysql_to_sqlite($statement);
        }
        (strstr($statement, "select") ? dwf_exception::throw_exception(603, ["__m__" => "fetch", "__statement__" => $statement]) : true);
        ($this->_pdo->query($statement) ? true : dwf_exception::throw_exception(602, ["__statement__" => $statement]));
    }

    /**
     * Exécute une requête type select et renvoie un tableau à 2 dimensions contenant les données.     * 
     * Pour plus de securité: voir la méthode protect_var.
     * 
     * @param string|statement $statement requête SQL
     * @return array tableau à deux dimensions contenant les données 
     */
    public function fetch($statement) {
        self::$_debug["nb_req"] ++;
        self::$_debug["statements"][] = ["req" => $statement, "trace" => (new dwf_exception("Trace"))->getTraceAsString()];
        if (isset(config::$_PDO_type) and config::$_PDO_type == "sqlite") {
            $statement = $this->mysql_to_sqlite($statement);
        }
        $memory = memory_get_usage();
        $data = $this->_pdo->query($statement);
        self::$_debug["memory"] += (memory_get_usage() - $memory);
        (strstr($statement, "select") ? true : dwf_exception::throw_exception(603, ["__m__" => "query", "__statement__" => $statement]));
        return ($data ? $data->fetchAll(PDO::FETCH_ASSOC) : dwf_exception::throw_exception(602, ["__statement__" => $statement]));
    }

    /**
     * Cette fonction permet de formater les requêtes initialement prévues pour MySQL en requêtes pour SQLite
     * @param string $statement Requête MySQL
     * @return string Requête SQLite
     */
    private function mysql_to_sqlite($statement) {
        $statement = strtr($statement, [
            "`" => "",
            "\'" => "&apos;",
            "id int(11) NOT NULL AUTO_INCREMENT" => "id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT",
            "AUTO_INCREMENT" => "AUTOINCREMENT",
            "int(11)" => "INTEGER",
            "PRIMARY KEY (id) " => "",
            "ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 " => "",
        ]);
        $statement = strtr($statement, [
            "NULL, )" => "NULL)",
        ]);
        return $statement;
    }

}
