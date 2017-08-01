 <?php 
/** Entité de la table fullcalendar_resource 
* @autor entity_generator by LEGAGNEUR Matthieu */
 class fullcalendar_resource { 
 
/** id 
* @var int id */
 private $_id;
/** title 
* @var string title */
 private $_title;
/** eventColor 
* @var string eventColor */
 private $_eventColor;
/** Indique si l'objet a été modifié ou non 
* @var boolean Indique si l'objet a été modifié ou non */
 private $_this_was_modified;
/** Indique si l'objet a été supprimé ou non 
* @var boolean Indique si l'objet a été supprimé ou non */
 private $_this_was_delete;
/** Entité de la table fullcalendar_resource */
 public function __construct($data) { $this->set_id($data["id"]);$this->set_title($data["title"]);$this->set_eventColor($data["eventColor"]);$this->_this_was_modified = false; }
/** Ajoute une entrée en base de donnée */
 public static function ajout( $title, $eventColor) { $title = application::$_bdd->protect_var($title);$eventColor = application::$_bdd->protect_var($eventColor);application::$_bdd->query("INSERT INTO fullcalendar_resource(title, eventColor) VALUES('" . $title . "', '" . $eventColor . "');"); } 
/** Retourne la structure de l'entity au format json */
 public static function get_structure() {return json_decode('[["id","int",true],["title","string",false],["eventColor","string",false]]', true);}
/** Retourne le contenu de la table sout forme d'une collection 
* ATTENTION PENSEZ A UTILISER application::$_bdd->protect_var(); */
 public static function get_collection($where = "" ) { $data=fullcalendar_resource::get_table_array($where); $col=false; foreach ($data as $entity) { if ($entity != FALSE) { $col[]=new fullcalendar_resource($entity); } } return $col;}
/** Retourne le contenu de la table sout forme d'un tableau a 2 dimentions 
* ATTENTION PENSEZ A UTILISER application::$_bdd->protect_var(); */
 public static function get_table_array($where = "") { $req = "select * from fullcalendar_resource"; if (!empty($where)) { $req.=" where " . $where; } $req.=" ;"; return application::$_bdd->fetch($req); }
/** Retourne le contenu de la table sout forme d'un tableau a 2 dimentions dont la clé est l'identifiant de l'entité 
* ATTENTION PENSEZ A UTILISER application::$_bdd->protect_var(); */
 public static function get_table_ordored_array($where = "") { $data = fullcalendar_resource::get_table_array($where); $datas = array(); foreach ($data as $value) { $datas[$value["id"]] = $value; unset($datas[$value["id"]]["id"]); } return $datas; }
/** Retourne le nombre d'entré 
* ATTENTION PENSEZ A UTILISER application::$_bdd->protect_var(); */
 public static function get_count($where = "" ) { $req = "select count(*) as count from fullcalendar_resource"; if (!empty($where)) { $req.=" where " . $where; } $req.=" ;"; $data = application::$_bdd->fetch($req); return $data[0]['count'];}
/** Retourne une entité sous forme d'objet a partir de son identifiant 
* @return fullcalendar_resource|boolean */
 public static function get_from_id($id) { $data = application::$_bdd->fetch("select * from fullcalendar_resource where id = '" . application::$_bdd->protect_var($id) . "';"); if (isset($data[0]) and $data[0] != FALSE) { return new fullcalendar_resource($data[0]); } else { return false; } }
/** Retourne le contenu de la table sout forme d'un objet json (utile pour les services) 
* ATTENTION PENSEZ A UTILISER application::$_bdd->protect_var(); 
* @return string Objet json */
 public static function get_json_object($where = "") { return json_encode(fullcalendar_resource::get_table_ordored_array($where)); }
/**Supprime l'entité*/public static function delete_by_id($id) { application::$_bdd->query("delete from fullcalendar_resource where id='" . application::$_bdd->protect_var($id) . "';"); }
/**Supprime l'entité a la fin du script*/public function delete() { $this->_this_was_delete=true;} public function get_id() { return $this->_id;}private function set_id($id) {$this->_id = (int) $id; $this->_this_was_modified = true; } public function get_title() { return $this->_title;}public function set_title($title) {$this->_title = $title; $this->_this_was_modified = true; } public function get_eventColor() { return $this->_eventColor;}public function set_eventColor($eventColor) {$this->_eventColor = $eventColor; $this->_this_was_modified = true; } public function __destruct() { if ($this->_this_was_modified and !$this->_this_was_delete) {  $id = application::$_bdd->protect_var($this->get_id()); $title = application::$_bdd->protect_var($this->get_title()); $eventColor = application::$_bdd->protect_var($this->get_eventColor()); application::$_bdd->query("update fullcalendar_resource set id = '".$id."', title = '".$title."', eventColor = '".$eventColor."' where id = '" . $id . "';"); }if($this->_this_was_delete){ self::delete_by_id($this->get_id());} } }