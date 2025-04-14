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
                $file = "class/entity/" . config::$_PDO_dbname . ".sqlite";
                if (!file_exists($file) and file_exists("../" . $file)) {
                    $file = "../" . $file;
                }
                $this->_pdo = new PDO("sqlite:$file");
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
     * @deprecated since version 21.23.08 remplacé par la méthode static bdd:p()
     * @param string $var variable à protégée
     * @return string variable protégé
     */
    public function protect_var($var) {
        return self::p($var);
    }

    /**
     * Cette fonction sert à sécuriser une variable destinée à être enregistrée en base de données 
     * contre les attaques SQL et JavaScript.
     * Cette fonction est systématiquement utilisée dans les méthodes "query" et "fetch" de la class bdd
     * 
     * @param string $var variable à protégée
     * @return string variable protégé
     */
    public static function p($var) {
        return addslashes(htmlspecialchars(htmlspecialchars_decode($var), ENT_NOQUOTES));
    }

    /**
     * Cette fonction supprime les protections de la fonction protect_var() afin que la variable puisse être 
     * reexploitée sans problème.
     * 
     * @deprecated since version 21.23.08 remplacé par la méthode static bdd:up()
     * @param string $var variable à afficher
     * @return string variable affichée
     */
    public function unprotect_var($var) {
        return stripcslashes($var);
    }

    /**
     * Cette fonction supprime les protections de la fonction protect_var() afin que la variable puisse être 
     * reexploitée sans problème.
     * 
     * @param string $var variable à afficher
     * @return string variable affichée
     */
    public static function up($var) {
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
     * ATTENTION : cette fonction a été créée temporairement en attendant la refonte
     * de entity_generator.class.php (qui a été faite en version 21.18.03 afin de gérer proprement des JSON en base de données)
     * cette fonction sera supprimée dans la version 21.18.04 de DWF !
     * 
     * Convertit un json provenant de la base de données en tableau
     * 
     * @param string $json json provenant de la base de données
     * @return array Tableau de données
     */
    public function json_decode($json) {
        return json_decode(strtr($json, ["&quot;" => '"']), true);
    }

    /**
     * Exécute une requête type update, insert ou delete.
     * Pour plus de securité: voir la méthode protect_var.
     *
     * @param string|statement $statement requête SQL
     * @param array $params paramètres à associer dans la requête préparé exemple : ":id"=>$id
     */
    public function query($statement, $params = []) {
        self::$_debug["nb_req"]++;
        self::$_debug["statements"][] = ["req" => $statement, "trace" => (new dwf_exception("Trace", 700))->getTraceAsString()];
        if (isset(config::$_PDO_type) and config::$_PDO_type == "sqlite") {
            $statement = $this->mysql_to_sqlite($statement);
        }
        list($statement, $params) = $this->params_filter($statement, $params);
        (!strstr(strtolower($statement), "select") ? true : dwf_exception::throw_exception(603, ["__m__" => "query", "__statement__" => $statement]));
        ($query = $this->_pdo->prepare($statement))->execute($params) ? true : dwf_exception::throw_exception(602, ["__statement__" => $statement]);
        self::$_debug["memory"] += ((memory_get_usage() - self::$_debug["memory"]));
        return (strstr(strtolower($statement), "insert")) ? $this->_pdo->lastInsertId() : false;
    }

    /**
     * Exécute une requête type select et renvoie un tableau à deux dimensions contenant les données.     * 
     * Pour plus de securité: voir la méthode protect_var.
     * 
     * @param string|statement $statement requête SQL
     * @param array $params paramètres à associer dans la requête préparé exemple : ":id"=>$id
     * @return array tableau à deux dimensions contenant les données 
     */
    public function fetch($statement, $params = []) {
        self::$_debug["nb_req"]++;
        self::$_debug["statements"][] = ["req" => $statement, "trace" => (new dwf_exception("Trace"))->getTraceAsString()];
        if (isset(config::$_PDO_type) and config::$_PDO_type == "sqlite") {
            $statement = $this->mysql_to_sqlite($statement);
        }
        list($statement, $params) = $this->params_filter($statement, $params);
        (strstr(strtolower($statement), "select") ? true : dwf_exception::throw_exception(603, ["__m__" => "query", "__statement__" => $statement]));
        (($query = $this->_pdo->prepare($statement))->execute($params) ? true : dwf_exception::throw_exception(602, ["__statement__" => $statement]));
        self::$_debug["memory"] += ((memory_get_usage() - self::$_debug["memory"]));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Filtre les paramètres à utiliser dans une requête préparée
     *
     * @param string $statement La requête avec des marqueurs de paramètres
     * @param array $params Les paramètres à filtrer
     * @return array la requete et les paramètres filtrés
     */
    private function params_filter($statement, $params) {
        preg_match_all("/:([a-zA-Z0-9_]+)/", $statement, $matches);
        $params = array_intersect_key($params, array_flip($matches[0]));
        foreach ($params as $marqueur => $param) {
            if (is_array($param)) {
                unset($params[$marqueur]);
                $marqueurs = [];
                foreach ($param as $i => $value) {
                    $marqueurs[] = $m = "{$marqueur}_{$i}";
                    $params[$m] = $value;
                }
                $statement = strtr($statement, [$marqueur => implode(",", $marqueurs)]);
            }
        }
        return [$statement, $params];
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
