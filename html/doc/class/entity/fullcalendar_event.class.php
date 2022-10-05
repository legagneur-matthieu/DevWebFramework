<?php
/** Entité de la table fullcalendar_event
* @autor entity_generator by LEGAGNEUR Matthieu */
class fullcalendar_event {
/** id 
* @var int id */
 private $_id;
/** calendar 
* @var string calendar */
 private $_calendar;
/** title 
* @var string title */
 private $_title;
/** start 
* @var string start */
 private $_start;
/** end 
* @var string end */
 private $_end;
/** url 
* @var string url */
 private $_url;
/** Indique si l'objet a été modifié ou non 
* @var boolean Indique si l'objet a été modifié ou non */
 private $_this_was_modified;
/** Indique si l'objet a été supprimé ou non 
* @var boolean Indique si l'objet a été supprimé ou non */
private $_this_was_delete;
/** Memento des instances 
* @var boolean Memento des instances */
private static $_entity_memento=[];
/** Entité de la table fullcalendar_event */
 public function __construct($data) { 
$this->set_id($data["id"]);
$this->set_calendar($data["calendar"]);
$this->set_title($data["title"]);
$this->set_start($data["start"]);
$this->set_end($data["end"]);
$this->set_url($data["url"]);
$this->_this_was_modified = false;
 }
/** Ajoute une entrée en base de donnée */
 public static function ajout( $calendar, $title, $start, $end, $url) { 
$calendar = application::$_bdd->protect_var($calendar);
$title = application::$_bdd->protect_var($title);
$start = application::$_bdd->protect_var($start);
$end = application::$_bdd->protect_var($end);
$url = application::$_bdd->protect_var($url);
application::$_bdd->query("INSERT INTO fullcalendar_event(calendar, title, start, end, url) VALUES('" . $calendar . "', '" . $title . "', '" . $start . "', '" . $end . "', '" . $url . "');"); } /** Retourne la structure de l'entity au format json */
public static function get_structure() {
    return json_decode('[["id","int",true],["calendar","string",false],["title","string",false],["start","string",false],["end","string",false],["url","string",false]]', true);
}
/** Retourne le contenu de la table sous forme d'une collection 
*ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); */
public static function get_collection($where = "" ) {
    $col=[];
    foreach (fullcalendar_event::get_table_array($where) as $entity) {
        if ($entity != FALSE) {
            if (isset(self::$_entity_memento[$entity["id"]])) {
                $col[] = self::$_entity_memento[$entity["id"]];
            } else {
                $col[] = self::$_entity_memento[$entity["id"]] = new fullcalendar_event($entity);
            }
        }
    }
    return $col;
}
/** Retourne le contenu de la table sous forme d'un tableau à deux dimensions 
* ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); */
public static function get_table_array($where = "") {
    $data = application::$_bdd->fetch("select * from fullcalendar_event" . (!empty($where) ? " where " . $where : "") . ";");
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
    foreach (fullcalendar_event::get_table_array($where) as $value) {
        $data[$value["id"]] = $value;
        unset($data[$value["id"]]["id"]);
    }
    return $data;
}
/** Retourne le nombre d'entrée
* ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); */
public static function get_count($where = "" ) {
    $data = application::$_bdd->fetch("select count(*) as count from fullcalendar_event".(!empty($where)?" where " . $where:"").";");
    return $data[0]['count'];
}
/** Retourne une entité sous forme d'objet à partir de son identifiant 
* @return fullcalendar_event|boolean */
public static function get_from_id($id) {
    if (isset(self::$_entity_memento[$id])) {
        return self::$_entity_memento[$id];
    } else {
        $data = self::get_table_array("id='" . application::$_bdd->protect_var($id) . "'");
        return ((isset($data[0]) and $data[0] != FALSE) ? self::$_entity_memento[$id]=new fullcalendar_event($data[0]) : false);
    }
}
/** Retourne le contenu de la table sous forme d'un objet json (utile pour les services) 
* ATTENTION, PENSEZ A UTILISER application::$_bdd->protect_var(); 
* @return string Objet json */
public static function get_json_object($where = "") {
    return json_encode(fullcalendar_event::get_table_ordored_array($where));
}
/** Supprime l'entité*/
public static function delete_by_id($id) {
    application::$_bdd->query("delete from fullcalendar_event where id='" . application::$_bdd->protect_var((int)$id) . "';");
}
/** Supprime l'entité à la fin du script*/
public function delete() {
    $this->_this_was_delete=true;
}
public function get_id() {
    return $this->_id;
}
private function set_id($id) {
$this->_id = (int) $id;
 $this->_this_was_modified = true; }
public function get_calendar() {
    return $this->_calendar;
}
public function set_calendar($calendar) {
$this->_calendar = $calendar;
 $this->_this_was_modified = true; }
public function get_title() {
    return $this->_title;
}
public function set_title($title) {
$this->_title = $title;
 $this->_this_was_modified = true; }
public function get_start() {
    return $this->_start;
}
public function set_start($start) {
$this->_start = $start;
 $this->_this_was_modified = true; }
public function get_end() {
    return $this->_end;
}
public function set_end($end) {
$this->_end = $end;
 $this->_this_was_modified = true; }
public function get_url() {
    return $this->_url;
}
public function set_url($url) {
$this->_url = $url;
 $this->_this_was_modified = true; }
 public function __destruct() { if ($this->_this_was_modified and !$this->_this_was_delete) { 
 $id = application::$_bdd->protect_var($this->get_id());
 $calendar = application::$_bdd->protect_var($this->get_calendar());
 $title = application::$_bdd->protect_var($this->get_title());
 $start = application::$_bdd->protect_var($this->get_start());
 $end = application::$_bdd->protect_var($this->get_end());
 $url = application::$_bdd->protect_var($this->get_url());
 application::$_bdd->query("update fullcalendar_event set id = '".$id."', calendar = '".$calendar."', title = '".$title."', start = '".$start."', end = '".$end."', url = '".$url."' where id = '" . $id . "';");        }        if($this->_this_was_delete){            self::delete_by_id($this->get_id());        }
    }
}