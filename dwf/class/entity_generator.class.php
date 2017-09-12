<?php

/**
 * Cette classe génère les entités destinées à faire l'interface entre la base de données et le code <br />
 * ATTENTION A L'UTILISATION DU PARAMETRE $WHERE DANS LA METHODE STATIC ::GET_COLLECTION() DES ENTITES, PENSSEZ A application::$_bdd->protect_var();
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class entity_generator {

    /**
     * Tableau de données à deux dimensions (cf __construct())
     * 
     * @var array Tableau de données à deux dimensions (cf __construct())
     */
    private $_data;

    /**
     * Nom de la table correspondant aux entités (cf __construct())
     * 
     * @var string Nom de la table correspondant aux entités (cf __construct())
     */
    private $_table;

    /**
     * Cette classe génère les entités destinées à faire l'interface entre la base de donnée et le code <br />
     * ATTENTION A L'UTILISATION DU PARAMETRE $WHERE DANS LA METHODE STATIC ::GET_COLLECTION() DES ENTITES, PENSSEZ A application::$_bdd->protect_var();
     * 
     * @param array $data tableau de données à deux dimensions, correspondant aux tuples de la table correspondante , forme du tableau : <br />
     * array(array(nom_du_tuple, type_du_tuple, cle_primaire),...); <br />
     * le champ clé primaire n'est qu'un boolean à true ou false , si il est a true, ce tuple n'aura pas de "set_" (appelé aussi seteur)
     * @param string $table nom de la table correspondant aux entités, ce nom sera également le "type" des entités générées
     * @param boolean $overwrite le générateur doit-il écraser les entités générées déjà existantes ? (true/false)
     * @param boolean $create_table le générateur doit-il créer la table de l'entité dans la base de données ? (true/false)
     */
    public function __construct($data, $table, $overwrite = false, $create_table = true) {
        $this->_data = $data;
        $this->_table = $table;
        $this->create_class_file($this->get_class(), $overwrite);
        if ($create_table) {
            $this->create_sql_table();
        }
    }

    /**
     * Génère le contenu de la classe entité
     * 
     * @return string la class générée
     */
    private function get_class() {
        //génére la class
        $class = " <?php \n/** Entité de la table " . $this->_table . " \n* @autor entity_generator by LEGAGNEUR Matthieu */\n class " . $this->_table . " { \n ";

        //génére les atributs 
        foreach ($this->_data as $tuple) {
            $class .= "\n/** " . $tuple[0] . " \n* @var " . $tuple[1] . ' ' . $tuple[0] . " */\n private $" . "_" . $tuple[0] . ';';
        }
        $class .= "\n/** Indique si l'objet a été modifié ou non \n* @var boolean Indique si l'objet a été modifié ou non */\n private $" . "_this_was_modified;";
        $class .= "\n/** Indique si l'objet a été supprimé ou non \n* @var boolean Indique si l'objet a été supprimé ou non */\n private $" . "_this_was_delete;";

        //génére le constructeur
        $class .= "\n/** Entité de la table " . $this->_table . " */\n public function __construct($" . "data) { ";
        foreach ($this->_data as $tuple) {
            $class .= "$" . "this->set_" . $tuple[0] . "($" . "data[\"" . $tuple[0] . "\"]);";
        }
        $class .= '$this->_this_was_modified = false; }';

        //génére la fonction statique ( static ) d'ajout
        $class .= "\n/** Ajoute une entrée en base de donnée */\n public static function ajout(";
        $p = "1__";
        foreach ($this->_data as $tuple) {
            if (!$tuple[2]) {
                $p .= ", $" . $tuple[0];
            }
        }
        $class .= strtr($p, array("1__," => "")) . ") { ";
        foreach ($this->_data as $tuple) {
            if (!$tuple[2]) {
                $class .= "$" . $tuple[0] . " = application::$" . "_bdd->protect_var($" . $tuple[0] . ");";
            }
        }
        $class .= "application::$" . "_bdd->query(\"INSERT INTO " . $this->_table . "(";
        $i = 0;
        while (isset($this->_data[$i][0])) {
            if (!$this->_data[$i][2]) {
                $class .= $this->_data[$i][0];
                if (isset($this->_data[$i + 1][0])) {
                    $class .= ", ";
                }
            }
            $i++;
        }
        $class .= ") VALUES(";
        $i = 0;
        while (isset($this->_data[$i][0])) {
            if (!$this->_data[$i][2]) {
                $class .= "'\" . $" . $this->_data[$i][0] . " . \"'";
                if (isset($this->_data[$i + 1][0])) {
                    $class .= ", ";
                }
            }
            $i++;
        }
        $class .= ");\"); } ";

        $class .= "\n/** Retourne la structure de l'entity au format json */\n public static function get_structure() {return json_decode('" . json_encode($this->_data) . "', true);}";

        //génére la fonction statiques ( static ) get_collection
        $class .= "\n/** Retourne le contenu de la table sout forme d'une collection \n* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */\n public static function get_collection($" . "where = \"\" ) { $" . "data=" . $this->_table . "::get_table_array($" . "where); $" . "col=false; foreach ($" . "data as $" . "entity) { if ($" . "entity != FALSE) { $" . "col[]=new " . $this->_table . "($" . "entity); } } return $" . "col;}";

        //génére la fonction statique ( static ) get_table_array()
        $class .= "\n/** Retourne le contenu de la table sout forme d'un tableau a 2 dimentions \n* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */\n public static function get_table_array($" . "where = \"\") { $" . "req = \"select * from " . $this->_table . "\"; if (!empty($" . "where)) { $" . "req.=\" where \" . $" . "where; } $" . "req.=\" ;\"; return application::$" . "_bdd->fetch($" . "req); }";

        //génére la fonction statique ( static ) get_table_ordored_array
        $class .= "\n/** Retourne le contenu de la table sout forme d'un tableau a 2 dimentions dont la clé est l'identifiant de l'entité \n* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */\n public static function get_table_ordored_array($" . "where = \"\") { $" . "data = " . $this->_table . "::get_table_array($" . "where); $" . "datas = array(); foreach ($" . "data as $" . "value) { $" . "datas[$" . "value[\"id\"]] = $" . "value; unset($" . "datas[$" . "value[\"id\"]][\"id\"]); } return $" . "datas; }";

        //génére la fonction statique ( static ) get_count()
        $class .= "\n/** Retourne le nombre d'entré \n* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */\n public static function get_count($" . "where = \"\" ) { $" . "req = \"select count(*) as count from " . $this->_table . "\"; if (!empty($" . "where)) { $" . "req.=\" where \" . $" . "where; } $" . "req.=\" ;\"; $" . "data = application::$" . "_bdd->fetch($" . "req); return $" . "data[0]['count'];}";

        //génére la fonction statique ( static ) get_from_id
        $class .= "\n/** Retourne une entité sous forme d'objet a partir de son identifiant \n* @return " . $this->_table . "|boolean */\n";
        $class .= " public static function get_from_id($" . "id) { $" . "data = application::$" . "_bdd->fetch(\"select * from " . $this->_table . " where id = '\" . application::$" . "_bdd->protect_var($" . "id) . \"';\"); if (isset($" . "data[0]) and $" . "data[0] != FALSE) { return new " . $this->_table . "($" . "data[0]); } else { return false; } }";

        //génére la fonction statique ( static ) get_json_object
        $class .= "\n/** Retourne le contenu de la table sout forme d'un objet json (utile pour les services) \n* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); \n* @return string Objet json */\n public static function get_json_object($" . "where = \"\") { return json_encode(" . $this->_table . "::get_table_ordored_array($" . "where)); }";

        //génére la fonction statique ( static ) delete_by_id
        $class .= "\n/**Supprime l'entité*/public static function delete_by_id($" . "id) { application::$" . "_bdd->query(\"delete from " . $this->_table . " where id='\" . application::$" . "_bdd->protect_var((int)$" . "id) . \"';\"); }";

        //génére la fonction de suppresion
        $class .= "\n/**Supprime l'entité a la fin du script*/public function delete() { $" . "this->_this_was_delete=true;}";

        //génére les geteur avec leur type si besoin
        foreach ($this->_data as $tuple) {
            if (!($tuple[1] == "int" or $tuple[1] == "integer" or $tuple[1] == "string" or $tuple[1] == "mail")) {
                $class .= " /** @return " . $tuple[1] . " */";
            }

            $class .= " public function get_" . $tuple[0] . "() { return $" . "this->_" . $tuple[0] . ";}";

            //génére les seteur
            $class .= $tuple[2] ? "private" : "public";
            $class .= " function set_" . $tuple[0] . "($" . $tuple[0] . ") {";
            if ($tuple[1] == "string") {
                $class .= "$" . "this->_" . $tuple[0] . " = $" . $tuple[0] . ";";
            } elseif ($tuple[1] == "int" or $tuple[1] == "integer") {
                $class .= "$" . "this->_" . $tuple[0] . " = (int) $" . $tuple[0] . ";";
            } elseif ($tuple[1] == "mail") {
                $class .= "if (application::$" . "_bdd->verif_email($" . $tuple[0] . " )) { $" . "this->_" . $tuple[0] . " = $" . $tuple[0] . ";}";
            } else {
                $class .= "$" . "this->_" . $tuple[0] . " = " . $tuple[1] . "::get_from_id($" . $tuple[0] . ");";
            }
            $class .= ' $this->_this_was_modified = true; }';
        }

        //génére le destructeur
        $class .= ' public function __destruct() { if ($this->_this_was_modified and !$this->_this_was_delete) { ';
        foreach ($this->_data as $tuple) {
            switch ($tuple[1]) {
                case "int":
                case "integer":
                case "string":
                case "mail":
                    $class .= " $" . $tuple[0] . " = application::$" . "_bdd->protect_var($" . "this->get_" . $tuple[0] . "());";
                    break;
                default:
                    $class .= " $" . $tuple[0] . " = application::$" . "_bdd->protect_var($" . "this->get_" . $tuple[0] . "()->get_id());";
                    break;
            }
        }
        $class .= " application::$" . "_bdd->query(\"update " . $this->_table . " set ";
        $i = 0;
        while (isset($this->_data[$i][0])) {
            $class .= $this->_data[$i][0] . " = '\".$" . $this->_data[$i][0] . ".\"'";
            if (isset($this->_data[$i + 1][0])) {
                $class .= ", ";
            }
            $i++;
        }
        $class .= " where id = '\" . $" . "id . \"';\"); }if($" . "this->_this_was_delete){ self::delete_by_id($" . "this->get_id());} } }";
        return $class;
    }

    /**
     * Ã‰crit la class/entité générée dans un fichier
     * 
     * @param string $class
     * @param boolean $overwrite
     */
    private function create_class_file($class, $overwrite) {
        if (is_dir("class")) {
            $file = "class/entity/" . $this->_table . ".class.php";
            $exist_file = file_exists($file);
            if ($overwrite) {
                if ($exist_file) {
                    unlink($file);
                }
                $this->write($file, $class);
            } else {
                if (!$exist_file) {
                    $this->write($file, $class);
                }
            }
        }
    }

    /**
     * Fonction servant à l'écriture de la classe dans un fichier
     * 
     * @param string $file chemin et nom du fichier à créer
     * @param string $class contenu du fichier a inscrire
     */
    private function write($file, $class) {
        file_put_contents($file, $class);
        dwf_exception::check_file_writed($file);
    }

    /**
     * créé la table de l'entité dans la base de données
     */
    private function create_sql_table() {
        $query = "CREATE TABLE IF NOT EXISTS " . application::$_bdd->protect_var($this->_table) . " (id int(11) NOT NULL AUTO_INCREMENT, ";
        foreach ($this->_data as $tuple) {
            if ($tuple[0] != "id") {
                if ($tuple[1] == "string" or $tuple[1] == "mail") {
                    $query .= "" . application::$_bdd->protect_var($tuple[0]) . " text NOT NULL, ";
                } else {
                    $query .= "" . application::$_bdd->protect_var($tuple[0]) . " int(11) NOT NULL, ";
                }
            }
        }
        $query .= "PRIMARY KEY (id) ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        application::$_bdd->query($query);
    }

}
