<?php

/**
 * Cet objet g�re la connexion et les traitements de la base de donn�es
 * Quelques fonctions servent � v�rifier ou prot�ger les donn�es
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class bdd {

    /**
     * Instance de PDO
     * 
     * @var PDO Instance de PDO
     */
    private $_pdo;
    
    /**
     * Tableau de d�bogage 
     * @var array Tableau de d�bogage 
     */
    public static $_debug = array(
        "nb_req" => 0,
        "memory" => 0,
        "satatements" => array()
    );

    /**
     * Cet objet g�re la connexion et les traitements de la base de donn�es
     * Quelques fonctions servent � v�rifier ou prot�ger les donn�es
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
     * Cette fonction sert � s�curiser une variable destin�e � �tre enregistr�e en base de donn�es 
     * contre les attaques SQL et JavaScript.
     * Cette fonction est syst�matiquement utilis�e dans les m�thodes "query" et "fetch" de la class bdd
     * 
     * @param string $var variable � prot�g�e
     * @return string variable prot�g�
     */
    public function protect_var($var) {
        return addslashes(htmlspecialchars(htmlspecialchars_decode($var)));
    }

    /**
     * Cette fonction suprimme les protections de la fonction protect_var() afin que la variable puisse �tre 
     * r�exploit�e sans probl�mes.
     * 
     * @param string $var variable � afficher
     * @return string variable affich�e
     */
    public function unprotect_var($var) {
        return stripcslashes($var);
    }

    /**
     * V�rifie la validit� d'une adresse mail
     * 
     * @param string $email email � v�rifier
     * @return boolean adresse valide : true/false
     */
    public function verif_email($email) {
        return boolval(filter_var($email, FILTER_VALIDATE_EMAIL));
    }

    /**
     * Ex�cute une requ�te type update, insert ou delete.
     * Pour plus de securit�: voir la m�thode protect_var.
     *
     * @param string|statement $statement requ�te SQL
     */
    public function query($statement) {
        self::$_debug["nb_req"] ++;
        self::$_debug["statements"][] = array("req" => $statement, "trace" => (new dwf_exception("Trace", 700))->getTraceAsString());
        if (isset(config::$_PDO_type) and config::$_PDO_type == "sqlite") {
            $statement = $this->mysql_to_sqlite($statement);
        }
        (strstr($statement, "select") ? dwf_exception::throw_exception(603, array("__m__" => "fetch", "__statement__" => $statement)) : true);
        ($this->_pdo->query($statement) ? true : dwf_exception::throw_exception(602, array("__statement__" => $statement)));
    }

    /**
     * Ex�cute une requ�te type select et renvoie un tableau � 2 dimensions contenant les donn�es.     * 
     * Pour plus de securit�: voir la m�thode protect_var.
     * 
     * @param string|statement $statement requ�te SQL
     * @return array tableau � deux dimensions contenant les donn�es 
     */
    public function fetch($statement) {
        self::$_debug["nb_req"] ++;
        self::$_debug["statements"][] = array("req" => $statement, "trace" => (new dwf_exception("Trace"))->getTraceAsString());
        if (isset(config::$_PDO_type) and config::$_PDO_type == "sqlite") {
            $statement = $this->mysql_to_sqlite($statement);
        }
        $memory = memory_get_usage();
        $data = $this->_pdo->query($statement);
        self::$_debug["memory"] += (memory_get_usage() - $memory);
        (strstr($statement, "select") ? true : dwf_exception::throw_exception(603, array("__m__" => "query", "__statement__" => $statement)));
        return ($data ? $data->fetchAll(PDO::FETCH_ASSOC) : dwf_exception::throw_exception(602, array("__statement__" => $statement)));
    }

    /**
     * Cette fonction permet de formater les requ�tes initialement pr�vues pour MySQL en requ�tes pour SQLite
     * @param string $statement Requ�te MySQL
     * @return string Requ�te SQLite
     */
    private function mysql_to_sqlite($statement) {
        $statement = strtr($statement, array(
            "`" => "",
            "\'" => "&apos;",
            "id int(11) NOT NULL AUTO_INCREMENT" => "id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT",
            "AUTO_INCREMENT" => "AUTOINCREMENT",
            "int(11)" => "INTEGER",
            "PRIMARY KEY (id) " => "",
            "ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 " => "",
        ));
        $statement = strtr($statement, array(
            "NULL, )" => "NULL)",
        ));
        return $statement;
    }

}
