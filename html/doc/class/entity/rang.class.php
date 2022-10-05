<?php

/** Entité de la table rang
 * @autor entity_generator by LEGAGNEUR Matthieu */
class rang {

    /** id 
     * @var int id */
    private $_id;

    /** nom 
     * @var string nom */
    private $_nom;

    /** Indique si l'objet a été modifié ou non 
     * @var boolean Indique si l'objet a été modifié ou non */
    private $_this_was_modified;

    /** Indique si l'objet a été supprimé ou non 
     * @var boolean Indique si l'objet a été supprimé ou non */
    private $_this_was_delete;

    /** Entité de la table rang */
    public function __construct($data) {
        $this->set_id($data["id"]);
        $this->set_nom($data["nom"]);
        $this->_this_was_modified = false;
    }

    /** Ajoute une entrée en base de données */
    public static function ajout($nom) {
        $nom = application::$_bdd->protect_var($nom);
        application::$_bdd->query("INSERT INTO rang(nom) VALUES('" . $nom . "');");
    }

    /** Retourne la structure de l'entity au format json */
    public static function get_structure() {
        return json_decode('[["id","int",true],["nom","string",false]]', true);
    }

    /** Retourne le contenu de la table sous forme d'une collection 
     * ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); */
    public static function get_collection($where = "") {
        $col = false;
        foreach (rang::get_table_array($where) as $entity) {
            if ($entity != FALSE) {
                $col[] = new rang($entity);
            }
        }
        return $col;
    }

    /** Retourne le contenu de la table sous forme d'un tableau à deux dimensions 
     * ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); */
    public static function get_table_array($where = "") {
        $data = application::$_bdd->fetch("select * from rang" . (!empty($where) ? " where " . $where : "") . ";");
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

    /** Retourne le contenu de la table sous forme d'un tableau à deux dimensions dont la clé est l'identifiant de l'entité 
     * ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); */
    public static function get_table_ordored_array($where = "") {
        $data = [];
        foreach (rang::get_table_array($where) as $value) {
            $data[$value["id"]] = $value;
            unset($data[$value["id"]]["id"]);
        }
        return $data;
    }

    /** Retourne le nombre d'entrée
     * ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); */
    public static function get_count($where = "") {
        $data = application::$_bdd->fetch("select count(*) as count from rang" . (!empty($where) ? " where " . $where : "") . ";");
        return $data[0]['count'];
    }

    /** Retourne une entité sous forme d'objet à partir de son identifiant 
     * @return rang|boolean */
    public static function get_from_id($id) {
        $data = self::get_table_array("id='" . application::$_bdd->protect_var($id) . "'");
        return ((isset($data[0]) and $data[0] != FALSE) ? new rang($data[0]) : false);
    }

    /** Retourne le contenu de la table sout forme d'un objet json (utile pour les services) 
     * ATTENTION PENSEZ A UTILISER application::$_bdd->protect_var(); 
     * @return string Objet json */
    public static function get_json_object($where = "") {
        return json_encode(rang::get_table_ordored_array($where));
    }

    /** Supprime l'entité */
    public static function delete_by_id($id) {
        application::$_bdd->query("delete from rang where id='" . application::$_bdd->protect_var((int) $id) . "';");
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

    public function get_nom() {
        return $this->_nom;
    }

    public function set_nom($nom) {
        $this->_nom = $nom;
        $this->_this_was_modified = true;
    }

    public function __destruct() {
        if ($this->_this_was_modified and ! $this->_this_was_delete) {
            $id = application::$_bdd->protect_var($this->get_id());
            $nom = application::$_bdd->protect_var($this->get_nom());
            application::$_bdd->query("update rang set id = '" . $id . "', nom = '" . $nom . "' where id = '" . $id . "';");
        } if ($this->_this_was_delete) {
            self::delete_by_id($this->get_id());
        }
    }

}
