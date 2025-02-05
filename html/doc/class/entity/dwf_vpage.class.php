<?php
/** Entité de la table dwf_vpage
* @autor entity_generator by LEGAGNEUR Matthieu */
class dwf_vpage {
/** id 
* @var int id */
private $_id;
/** mt 
* @var int mt */
private $_mt;
/** key 
* @var string key */
private $_key;
/** title 
* @var string title */
private $_title;
/** content 
* @var string content */
private $_content;
/** Indique si l'objet a été modifié ou non 
* @var boolean Indique si l'objet a été modifié ou non */
 private $_this_was_modified;
/** Indique si l'objet a été supprimé ou non 
* @var boolean Indique si l'objet a été supprimé ou non */
private $_this_was_delete;
/** Memento des instances 
* @var boolean Memento des instances */
private static $_entity_memento=[];
/** Entité de la table dwf_vpage */
public function __construct($data) { 
$this->set_id($data["id"]);
$this->set_mt($data["mt"]);
$this->set_key($data["key"]);
$this->set_title($data["title"]);
$this->set_content($data["content"]);
$this->_this_was_modified = false;
 }
/** Ajoute une entrée en base de donnée */
 public static function ajout( $mt, $key, $title, $content) { 
application::$_bdd->query("INSERT INTO dwf_vpage(mt, key, title, content) VALUES(:mt, :key, :title, :content);",[
":mt" => (int) $mt, ":key" => $key, ":title" => $title, ":content" => $content]); } 
/** Retourne la structure de l'entity au format json */
public static function get_structure() {
    return json_decode('[["id","int",true],["mt","int",false],["key","string",false],["title","string",false],["content","string",false]]', true);
}
/** Retourne le contenu de la table sous forme d'une collection 
*ATTENTION, PENSEZ A UTILISER bdd::p(); */
public static function get_collection($where = "", $params = []) {
    $col=[];
    foreach (dwf_vpage::get_table_array($where, $params) as $entity) {
        if ($entity != FALSE) {
            if (isset(self::$_entity_memento[$entity["id"]])) {
                $col[] = self::$_entity_memento[$entity["id"]];
            } else {
                $col[] = self::$_entity_memento[$entity["id"]] = new dwf_vpage($entity);
            }
        }
    }
    return $col;
}
/** Retourne le contenu de la table sous forme d'un tableau a 2 dimensions 
* ATTENTION, PENSEZ A UTILISER bdd::p(); */
public static function get_table_array($where = "", $params = []) {
    $data = application::$_bdd->fetch("select * from dwf_vpage" . (!empty($where) ? " where " . $where : "") . ";", $params);
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
/** Retourne le contenu de la table sous forme d'un tableau a 2 dimensions dont la clé est l'identifiant de l'entité 
* ATTENTION, PENSEZ A UTILISER bdd::p(); */
public static function get_table_ordored_array($where = "", $params = []) {
    $data = [];
    foreach (dwf_vpage::get_table_array($where, $params) as $value) {
        $data[$value["id"]] = $value;
        unset($data[$value["id"]]["id"]);
    }
    return $data;
}
/** Retourne le nombre d'entrées 
* ATTENTION PENSEZ A UTILISER bdd::p(); */
public static function get_count($where = "", $params = [] ) {
    $data = application::$_bdd->fetch("select count(*) as count from dwf_vpage".(!empty($where)?" where " . $where:"").";", $params);
    return $data[0]["count"];
}
/** Retourne une entité sous forme d'objet à partir de son identifiant 
* @return dwf_vpage|boolean */
public static function get_from_id($id) {
    if (isset(self::$_entity_memento[$id])) {
        return self::$_entity_memento[$id];
    } else {
        $data = self::get_table_array("id=:id;", [":id" => (int) $id]);
        return ((isset($data[0]) and $data[0] != FALSE) ? self::$_entity_memento[$id]=new dwf_vpage($data[0]) : false);
    }
}
/** Retourne le contenu de la table sous forme d'un objet json (utile pour les services) 
* ATTENTION, PENSEZ A UTILISER bdd::p(); 
* @return string Objet json */
public static function get_json_object($where = "", $params = []) {
    return json_encode(dwf_vpage::get_table_ordored_array($where, $params));
}
/** Supprime l'entité*/
public static function delete_by_id($id) {
    application::$_bdd->query("delete from dwf_vpage where id=:id;", [":id" => (int) $id]);
}
/** Supprime l'entité à la fin du script*/
public function delete() {
    $this->_this_was_delete=true;
}
/** Met a jour l'entité dans la base de donnée*/
public function update() { 
if ($this->_this_was_modified and !$this->_this_was_delete) { 
 $id = bdd::p($this->get_id());
 $mt = bdd::p($this->get_mt());
 $key = bdd::p($this->get_key());
 $title = bdd::p($this->get_title());
 $content = bdd::p($this->get_content());
 application::$_bdd->query("update dwf_vpage set id = :id, mt = :mt, key = :key, title = :title, content = :content where id=:id;",[":id" => $this->get_id(),":mt" => $this->get_mt(), ":key" => $this->get_key(), ":title" => $this->get_title(), ":content" => $this->get_content()]);
    }
    if($this->_this_was_delete){
        self::delete_by_id($this->get_id());
    }
    $this->_this_was_modified=false;
}public function get_id() {
    return $this->_id;
}
private function set_id($id) {
$this->_id = (int) $id;
 $this->_this_was_modified = true; }
public function get_mt() {
    return $this->_mt;
}
public function set_mt($mt) {
$this->_mt = (int) $mt;
 $this->_this_was_modified = true; }
public function get_key() {
    return $this->_key;
}
public function set_key($key) {
$this->_key = $key;
 $this->_this_was_modified = true; }
public function get_title() {
    return $this->_title;
}
public function set_title($title) {
$this->_title = $title;
 $this->_this_was_modified = true; }
public function get_content() {
    return $this->_content;
}
public function set_content($content) {
$this->_content = $content;
 $this->_this_was_modified = true; }
 public function __destruct() { 
     $this->update();
 }
}