<?php

/**
 * Cette classe génère les entités destinées à faire l'interface entre la base de données et le code <br />
 * ATTENTION A L'UTILISATION DU PARAMETRE $WHERE DANS LA METHODE STATIC ::GET_COLLECTION() DES ENTITES, PENSEZ A application::$_bdd->protect_var();
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class entity_generator {

    /**
     * Tableau de données à deux dimensions (cf __construct())
     * 
     * @var array Tableau de données à deux dimensions (cf __construct())
     */
    private static $_data;

    /**
     * Nom de la table correspondant aux entités (cf __construct())
     * 
     * @var string Nom de la table correspondant aux entités (cf __construct())
     */
    private static $_table;

    /**
     * Cette classe génère les entités destinées à faire l'interface entre la base de données et le code <br />
     * ATTENTION A L'UTILISATION DU PARAMETRE $WHERE DANS LA METHODE STATIC ::GET_COLLECTION() DES ENTITES, PENSEZ A application::$_bdd->protect_var();
     * 
     * @param array $data tableau de données à deux dimensions, correspondant aux tuples de la table correspondante , forme du tableau : <br />
     * array(array(nom_du_tuple, type_du_tuple, cle_primaire),...); <br />
     * le champ clé primaire n'est qu'un boolean à true ou false , s'il est a true, ce tuple n'aura pas de "set_" (appelé aussi seteur)
     * @param string $table nom de la table correspondant aux entités, ce nom sera également le "type" des entités générées
     * @param boolean $overwrite le générateur doit-il écraser les entités générées déjà existantes ? (true/false)
     * @param boolean $create_table le générateur doit-il créer la table de l'entité dans la base de données ? (true/false)
     */
    public function __construct($data, $table, $overwrite = false, $create_table = true) {
        self::$_data = $data;
        self::$_table = $table;
        self::create_class_file(self::get_class(), $overwrite);
        if ($create_table) {
            application::$_bdd->query(self::create_sql_table());
        }
    }

    /**
     * Cette classe génère les entités destinées à faire l'interface entre la base de données et le code <br />
     * ATTENTION A L'UTILISATION DU PARAMETRE $WHERE DANS LA METHODE STATIC ::GET_COLLECTION() DES ENTITES, PENSEZ A application::$_bdd->protect_var();
     * 
     * @param array $data tableau de données à deux dimensions, correspondant aux tuples de la table correspondante , forme du tableau : <br />
     * array(array(nom_du_tuple, type_du_tuple, cle_primaire),...); <br />
     * le champ clé primaire n'est qu'un boolean à true ou false , s'il est à true, ce tuple n'aura pas de "set_" (appelé aussi seteur)
     * @param string $table nom de la table correspondant aux entités, ce nom sera également le "type" des entités générées
     * @param boolean $overwrite le générateur doit-il écraser les entités générées déjà existantes ? (true/false)
     * @param boolean $create_table le générateur doit-il créer la table de l'entité dans la base de données ? (true/false)
     */
    public static function generate($datas, $overwrite = false, $create_table = true) {
        $sql = "";
        foreach ($datas as $table => $data) {
            self::$_data = $data;
            self::$_table = $table;
            self::create_class_file(self::get_class(), $overwrite);
            if ($create_table) {
                application::$_bdd->query(self::create_sql_table());
            }
        }
    }

    /**
     * Génère le contenu de la classe entité
     * 
     * @return string la class générée
     */
    private static function get_class() {
        //génére la classe
        $class = "<?php" . PHP_EOL
                . "/** Entité de la table " . self::$_table . PHP_EOL
                . "* @autor entity_generator by LEGAGNEUR Matthieu */" . PHP_EOL
                . "class " . self::$_table . " {" . PHP_EOL;

        $tuple_construct = "";
        $p = "1__";
        $tuple_ajout = "";
        $tuple_etter = "";
        $tuple_update = "";
        foreach (self::$_data as $tuple) {
            //génére les attributs 
            $class .= "/** " . $tuple[0] . " " . PHP_EOL
                    . "* @var " . $tuple[1] . ' ' . $tuple[0] . " */\n private $" . "_" . $tuple[0] . ";" . PHP_EOL;
            //génére le contenu du constructeur 
            $tuple_construct .= "$" . "this->set_" . $tuple[0] . "($" . "data[\"" . $tuple[0] . "\"]);" . PHP_EOL;
            if (!$tuple[2]) {
                $p .= ", $" . $tuple[0];
                if (in_array($tuple[1], ["array"])) {
                    $tuple_ajout .= "$" . $tuple[0] . " = application::$" . "_bdd->protect_var(json_encode($" . $tuple[0] . "));" . PHP_EOL;
                } else {
                    $tuple_ajout .= "$" . $tuple[0] . " = application::$" . "_bdd->protect_var($" . $tuple[0] . ");" . PHP_EOL;
                }
            }
            //génère les geteur avec leur type si besoin
            if (!(in_array($tuple[1], ["int", "integer", "bool", "boolean", "string", "mail", "array"]))) {
                $tuple_etter .= " /** @return " . $tuple[1] . " */" . PHP_EOL;
            }
            $tuple_etter .= "public function get_" . $tuple[0] . "() {" . PHP_EOL
                    . "    return $" . "this->_" . $tuple[0] . ";" . PHP_EOL
                    . "}" . PHP_EOL;

            //génére les seteur
            $tuple_etter .= $tuple[2] ? "private" : "public";
            $tuple_etter .= " function set_" . $tuple[0] . "($" . $tuple[0] . ") {" . PHP_EOL;
            switch ($tuple[1]) {
                case "string":
                    $tuple_etter .= "$" . "this->_" . $tuple[0] . " = $" . $tuple[0] . ";" . PHP_EOL;
                    break;
                case "int":
                case "integer":
                    $tuple_etter .= "$" . "this->_" . $tuple[0] . " = (int) $" . $tuple[0] . ";" . PHP_EOL;
                    break;
                case "bool":
                case "boolen":
                    $tuple_etter .= "$" . "this->_" . $tuple[0] . " = ($" . $tuple[0] . "?1:0);" . PHP_EOL;
                    break;
                case "mail":
                    $tuple_etter .= "if (application::$" . "_bdd->verif_email($" . $tuple[0] . " )) { $" . "this->_" . $tuple[0] . " = $" . $tuple[0] . ";}" . PHP_EOL;
                    break;
                case "array":
                    $tuple_etter .= "$" . "this->_" . $tuple[0] . " = (is_array($" . $tuple[0] . ") ? $" . $tuple[0] . ": json_decode($" . $tuple[0] . ", true));" . PHP_EOL;
                    break;
                default:
                    $tuple_etter .= "$" . "this->_" . $tuple[0] . " = " . $tuple[1] . "::get_from_id($" . $tuple[0] . ");" . PHP_EOL;
                    break;
            }
            $tuple_etter .= ' $this->_this_was_modified = true; }' . PHP_EOL;
            //génère le contenu de l'update 
            switch ($tuple[1]) {
                case "int":
                case "integer":
                case "bool":
                case "boolen":
                case "string":
                case "mail":
                    $tuple_update .= " $" . $tuple[0] . " = application::$" . "_bdd->protect_var($" . "this->get_" . $tuple[0] . "());" . PHP_EOL;
                    break;
                case "array":
                    $tuple_update .= " $" . $tuple[0] . " = application::$" . "_bdd->protect_var(json_encode($" . "this->get_" . $tuple[0] . "()));" . PHP_EOL;
                    break;
                default:
                    $tuple_update .= " $" . $tuple[0] . " = application::$" . "_bdd->protect_var($" . "this->get_" . $tuple[0] . "()->get_id());" . PHP_EOL;
                    break;
            }
        }
        $class .= "/** Indique si l'objet a été modifié ou non " . PHP_EOL
                . "* @var boolean Indique si l'objet a été modifié ou non */" . PHP_EOL
                . " private $" . "_this_was_modified;" . PHP_EOL;
        $class .= "/** Indique si l'objet a été supprimé ou non " . PHP_EOL
                . "* @var boolean Indique si l'objet a été supprimé ou non */" . PHP_EOL
                . "private $" . "_this_was_delete;" . PHP_EOL;
        $class .= "/** Memento des instances " . PHP_EOL
                . "* @var boolean Memento des instances */" . PHP_EOL
                . "private static $" . "_entity_memento=[];" . PHP_EOL;

        //génére le constructeur
        $class .= "/** Entité de la table " . self::$_table . " */\n public function __construct($" . "data) { " . PHP_EOL;
        $class .= $tuple_construct;
        $class .= '$this->_this_was_modified = false;' . PHP_EOL . ' }' . PHP_EOL;

        //génére la fonction statique ( static ) d'ajout
        $class .= "/** Ajoute une entrée en base de données */" . PHP_EOL
                . " public static function ajout(";
        $class .= strtr($p, ["1__," => ""]) . ") { " . PHP_EOL;
        $class .= $tuple_ajout;
        $class .= "application::$" . "_bdd->query(\"INSERT INTO " . self::$_table . "(";
        $i = 0;
        while (isset(self::$_data[$i][0])) {
            if (!self::$_data[$i][2]) {
                $class .= self::$_data[$i][0];
                if (isset(self::$_data[$i + 1][0])) {
                    $class .= ", ";
                }
            }
            $i++;
        }
        $class .= ") VALUES(";
        $i = 0;
        while (isset(self::$_data[$i][0])) {
            if (!self::$_data[$i][2]) {
                $class .= "'$" . self::$_data[$i][0] . "'";
                if (isset(self::$_data[$i + 1][0])) {
                    $class .= ", ";
                }
            }
            $i++;
        }
        $class .= ");\"); } " . PHP_EOL;

        //génére la fonction statique ( static ) get_structure
        $class .= "/** Retourne la structure de l'entity au format json */" . PHP_EOL
                . "public static function get_structure() {" . PHP_EOL
                . "    return json_decode('" . json_encode(self::$_data) . "', true);" . PHP_EOL
                . "}" . PHP_EOL;

        //génére la fonction statique ( static ) get_collection
        $class .= "/** Retourne le contenu de la table sout forme d'une collection " . PHP_EOL
                . "*ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */" . PHP_EOL
                . "public static function get_collection($" . "where = \"\" ) {" . PHP_EOL
                . "    $" . "col=[];" . PHP_EOL
                . "    foreach (" . self::$_table . "::get_table_array($" . "where) as $" . "entity) {" . PHP_EOL
                . "        if ($" . "entity != FALSE) {" . PHP_EOL
                . "            if (isset(self::$" . "_entity_memento[$" . "entity[\"id\"]])) {" . PHP_EOL
                . "                $" . "col[] = self::$" . "_entity_memento[$" . "entity[\"id\"]];" . PHP_EOL
                . "            } else {" . PHP_EOL
                . "                $" . "col[] = self::$" . "_entity_memento[$" . "entity[\"id\"]] = new " . self::$_table . "($" . "entity);" . PHP_EOL
                . "            }" . PHP_EOL
                . "        }" . PHP_EOL
                . "    }" . PHP_EOL
                . "    return $" . "col;" . PHP_EOL
                . "}" . PHP_EOL;

        //génére la fonction statique ( static ) get_table_array()
        $class .= "/** Retourne le contenu de la table sout forme d'un tableau a 2 dimensions " . PHP_EOL
                . "* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */" . PHP_EOL
                . "public static function get_table_array($" . "where = \"\") {" . PHP_EOL
                . "    $" . "data = application::$" . "_bdd->fetch(\"select * from " . self::$_table . "\" . (!empty($" . "where) ? \" where \" . $" . "where : \"\") . \";\");" . PHP_EOL
                . "    $" . "tuples_array = [];" . PHP_EOL
                . "    foreach (self::get_structure() as $" . "s) {" . PHP_EOL
                . "        if ($" . "s[1] == \"array\") {" . PHP_EOL
                . "            $" . "tuples_array[] = $" . "s[0];" . PHP_EOL
                . "        }" . PHP_EOL
                . "    }" . PHP_EOL
                . "    foreach ($" . "data as $" . "key => $" . "value) {" . PHP_EOL
                . "        foreach ($" . "tuples_array as $" . "t) {" . PHP_EOL
                . "            $" . "data[$" . "key][$" . "t] = json_decode(stripslashes($" . "data[$" . "key][$" . "t]), true);" . PHP_EOL
                . "        }" . PHP_EOL
                . "    }" . PHP_EOL
                . "    return $" . "data;" . PHP_EOL
                . "}" . PHP_EOL;

        //génére la fonction statique ( static ) get_table_ordored_array
        $class .= "/** Retourne le contenu de la table sous forme d'un tableau a 2 dimensions dont la clé est l'identifiant de l'entité " . PHP_EOL
                . "* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */" . PHP_EOL
                . "public static function get_table_ordored_array($" . "where = \"\") {" . PHP_EOL
                . "    $" . "data = [];" . PHP_EOL
                . "    foreach (" . self::$_table . "::get_table_array($" . "where) as $" . "value) {" . PHP_EOL
                . "        $" . "data[$" . "value[\"id\"]] = $" . "value;" . PHP_EOL
                . "        unset($" . "data[$" . "value[\"id\"]][\"id\"]);" . PHP_EOL
                . "    }" . PHP_EOL
                . "    return $" . "data;" . PHP_EOL
                . "}" . PHP_EOL;

        //génére la fonction statique ( static ) get_count()
        $class .= "/** Retourne le nombre d'entrées " . PHP_EOL
                . "* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */" . PHP_EOL
                . "public static function get_count($" . "where = \"\" ) {" . PHP_EOL
                . "    $" . "data = application::$" . "_bdd->fetch(\"select count(*) as count from " . self::$_table . "\".(!empty($" . "where)?\" where \" . $" . "where:\"\").\";\");" . PHP_EOL
                . "    return $" . "data[0]['count'];" . PHP_EOL
                . "}" . PHP_EOL;

        //génére la fonction statique ( static ) get_from_id
        $class .= "/** Retourne une entité sous forme d'objet à partir de son identifiant " . PHP_EOL
                . "* @return " . self::$_table . "|boolean */" . PHP_EOL
                . "public static function get_from_id($" . "id) {" . PHP_EOL
                . "    if (isset(self::$" . "_entity_memento[$" . "id])) {" . PHP_EOL
                . "        return self::$" . "_entity_memento[$" . "id];" . PHP_EOL
                . "    } else {" . PHP_EOL
                . "        $" . "data = self::get_table_array(\"id='\" . application::$" . "_bdd->protect_var($" . "id) . \"'\");" . PHP_EOL
                . "        return ((isset($" . "data[0]) and $" . "data[0] != FALSE) ? self::$" . "_entity_memento[$" . "id]=new " . self::$_table . "($" . "data[0]) : false);" . PHP_EOL
                . "    }" . PHP_EOL
                . "}" . PHP_EOL;

        //génére la fonction statique ( static ) get_json_object
        $class .= "/** Retourne le contenu de la table sous forme d'un objet json (utile pour les services) " . PHP_EOL
                . "* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); " . PHP_EOL
                . "* @return string Objet json */" . PHP_EOL
                . "public static function get_json_object($" . "where = \"\") {" . PHP_EOL
                . "    return json_encode(" . self::$_table . "::get_table_ordored_array($" . "where));" . PHP_EOL
                . "}" . PHP_EOL;

        //génére la fonction statique ( static ) delete_by_id
        $class .= "/** Supprime l'entité*/" . PHP_EOL
                . "public static function delete_by_id($" . "id) {" . PHP_EOL
                . "    application::$" . "_bdd->query(\"delete from " . self::$_table . " where id='\" . application::$" . "_bdd->protect_var((int)$" . "id) . \"';\");" . PHP_EOL
                . "}" . PHP_EOL;

        //génére la fonction de suppresion
        $class .= "/** Supprime l'entité à la fin du script*/" . PHP_EOL
                . "public function delete() {" . PHP_EOL
                . "    $" . "this->_this_was_delete=true;" . PHP_EOL
                . "}" . PHP_EOL;
        //génére la fonction de mise à jour
        $class .= "/** Met a jour l'entité dans la base de donnée*/" . PHP_EOL
                . 'public function update() { ' . PHP_EOL
                . 'if ($this->_this_was_modified and !$this->_this_was_delete) { ' . PHP_EOL
                . $tuple_update . PHP_EOL
                . " application::$" . "_bdd->query(\"update " . self::$_table . " set ";
        $i = 0;
        while (isset(self::$_data[$i][0])) {
            $class .= self::$_data[$i][0] . " = '$" . self::$_data[$i][0] . "'";
            if (isset(self::$_data[$i + 1][0])) {
                $class .= ", ";
            }
            $i++;
        }
        $class .= " where id = '$" . "id';\");" . PHP_EOL
                . "    }" . PHP_EOL
                . "    if($" . "this->_this_was_delete){" . PHP_EOL
                . "        self::delete_by_id($" . "this->get_id());" . PHP_EOL
                . "    }" . PHP_EOL
                . "}";

        //getter et setter
        $class .= $tuple_etter;

        //génére le destructeur
        $class .= ' public function __destruct() { ' . PHP_EOL
                . "     $" . "this->update();" . PHP_EOL
                . " }" . PHP_EOL
                . "}";
        return $class;
    }

    /**
     * Ecrit la class/entité générée dans un fichier
     * 
     * @param string $class
     * @param boolean $overwrite
     */
    private static function create_class_file($class, $overwrite) {
        if (is_dir("class")) {
            $file = "class/entity/" . self::$_table . ".class.php";
            $exist_file = file_exists($file);
            if ($overwrite) {
                if ($exist_file) {
                    unlink($file);
                }
                self::write($file, $class);
            } else {
                if (!$exist_file) {
                    self::write($file, $class);
                }
            }
        }
    }

    /**
     * Fonction servant à l'écriture de la classe dans un fichier
     * 
     * @param string $file chemin et nom du fichier à créer
     * @param string $class contenu du fichier à inscrire
     */
    private static function write($file, $class) {
        file_put_contents($file, $class);
        dwf_exception::check_file_writed($file);
    }

    /**
     * créé la table de l'entité dans la base de données
     */
    private static function create_sql_table() {
        $query = "CREATE TABLE IF NOT EXISTS " . application::$_bdd->protect_var(self::$_table) . " (id int(11) NOT NULL AUTO_INCREMENT, ";
        foreach (self::$_data as $tuple) {
            if ($tuple[0] != "id") {
                if (in_array($tuple[1], ["string", "mail", "array"])) {
                    $query .= "" . application::$_bdd->protect_var($tuple[0]) . " text NOT NULL, ";
                } else {
                    $query .= "" . application::$_bdd->protect_var($tuple[0]) . " int(11) NOT NULL, ";
                }
            }
        }
        $query .= "PRIMARY KEY (id) ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        return $query;
    }

}
