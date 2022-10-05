<?php

/** Entité de la table user
 * @autor entity_generator by LEGAGNEUR Matthieu */
class user {

    /** id 
     * @var int id */
    private $_id;

    /** login 
     * @var string login */
    private $_login;

    /** psw 
     * @var string psw */
    private $_psw;

    /** rang 
     * @var rang rang */
    private $_rang;

    /** Indique si l'objet a été modifié ou non 
     * @var boolean Indique si l'objet a été modifié ou non */
    private $_this_was_modified;

    /** Indique si l'objet a été supprimé ou non 
     * @var boolean Indique si l'objet a été supprimé ou non */
    private $_this_was_delete;

    /** Entité de la table user */
    public function __construct($data) {
        $this->set_id($data["id"]);
        $this->set_login($data["login"]);
        $this->set_psw($data["psw"]);
        $this->set_rang($data["rang"]);
        $this->_this_was_modified = false;
    }

    /** Ajoute une entrée en base de données */
    public static function ajout($login, $psw, $rang) {
        $login = application::$_bdd->protect_var($login);
        $psw = application::$_bdd->protect_var($psw);
        $rang = application::$_bdd->protect_var($rang);
        application::$_bdd->query("INSERT INTO user(login, psw, rang) VALUES('" . $login . "', '" . $psw . "', '" . $rang . "');");
    }

    /** Retourne la structure de l'entity au format json */
    public static function get_structure() {
        return json_decode('[["id","int",true],["login","string",false],["psw","string",false],["rang","rang",false]]', true);
    }

    /** Retourne le contenu de la table sous forme d'une collection 
     * ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); */
    public static function get_collection($where = "") {
        $col = false;
        foreach (user::get_table_array($where) as $entity) {
            if ($entity != FALSE) {
                $col[] = new user($entity);
            }
        }
        return $col;
    }

    /** Retourne le contenu de la table sous forme d'un tableau à deux dimensions 
     * ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); */
    public static function get_table_array($where = "") {
        $data = application::$_bdd->fetch("select * from user" . (!empty($where) ? " where " . $where : "") . ";");
        $tuples_array = [];
        foreach (self::get_structure() as $s) {
            if ($s[1] == "array") {
                $tuples_array[] = $s[0];
            }
        }
        foreach ($data as $key => $value) {
            foreach ($tuples_array as $t) {
                $data[$key][$t] = json_decode(stripslashes($data[$key][$t]), true);
            }
        }
        return $data;
    }

    /** Retourne le contenu de la table sous forme d'un tableau à 2 dimensions dont la clé est l'identifiant de l'entité 
     * ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); */
    public static function get_table_ordored_array($where = "") {
        $data = [];
        foreach (user::get_table_array($where) as $value) {
            $data[$value["id"]] = $value;
            unset($data[$value["id"]]["id"]);
        }
        return $data;
    }

    /** Retourne le nombre d'entrées
     * ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); */
    public static function get_count($where = "") {
        $data = application::$_bdd->fetch("select count(*) as count from user" . (!empty($where) ? " where " . $where : "") . ";");
        return $data[0]['count'];
    }

    /** Retourne une entité sous forme d'objet à partir de son identifiant 
     * @return user|boolean */
    public static function get_from_id($id) {
        $data = self::get_table_array("id='" . application::$_bdd->protect_var($id) . "'");
        return ((isset($data[0]) and $data[0] != FALSE) ? new user($data[0]) : false);
    }

    /** Retourne le contenu de la table sous forme d'un objet json (utile pour les services) 
     * ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); 
     * @return string Objet json */
    public static function get_json_object($where = "") {
        return json_encode(user::get_table_ordored_array($where));
    }

    /** Supprime l'entité */
    public static function delete_by_id($id) {
        application::$_bdd->query("delete from user where id='" . application::$_bdd->protect_var((int) $id) . "';");
    }

    /** Supprime l'entité à la fin du script */
    public function delete() {
        $this->_this_was_delete = true;
    }

    public function get_id() {
        return $this->_id;
    }

    private function set_id($id) {
        $this->_id = (int) $id;
        $this->_this_was_modified = true;
    }

    public function get_login() {
        return $this->_login;
    }

    public function set_login($login) {
        $this->_login = $login;
        $this->_this_was_modified = true;
    }

    public function get_psw() {
        return $this->_psw;
    }

    public function set_psw($psw) {
        $this->_psw = $psw;
        $this->_this_was_modified = true;
    }

    /** @return rang */
    public function get_rang() {
        return $this->_rang;
    }

    public function set_rang($rang) {
        $this->_rang = rang::get_from_id($rang);
        $this->_this_was_modified = true;
    }

    public function __destruct() {
        if ($this->_this_was_modified and ! $this->_this_was_delete) {
            $id = application::$_bdd->protect_var($this->get_id());
            $login = application::$_bdd->protect_var($this->get_login());
            $psw = application::$_bdd->protect_var($this->get_psw());
            $rang = application::$_bdd->protect_var($this->get_rang()->get_id());
            application::$_bdd->query("update user set id = '" . $id . "', login = '" . $login . "', psw = '" . $psw . "', rang = '" . $rang . "' where id = '" . $id . "';");
        } if ($this->_this_was_delete) {
            self::delete_by_id($this->get_id());
        }
    }

}
