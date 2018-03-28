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
    private static $_data;

    /**
     * Nom de la table correspondant aux entités (cf __construct())
     * 
     * @var string Nom de la table correspondant aux entités (cf __construct())
     */
    private static $_table;

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
        self::$_data = $data;
        self::$_table = $table;
        self::create_class_file(self::get_class(), $overwrite);
        if ($create_table) {
            application::$_bdd->query(self::create_sql_table());
        }
    }

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
    public static function generate($datas, $overwrite = false, $create_table = true) {
        $sql = "";
        foreach ($datas as $table => $data) {
            self::$_data = $data;
            self::$_table = $table;
            self::create_class_file(self::get_class(), $overwrite);
            if ($create_table) {
                $sql .= self::create_sql_table();
            }
        }
        if (!empty($sql)) {
            application::$_bdd->query($sql);
        }
    }

    /**
     * Génère le contenu de la classe entité
     * 
     * @return string la class générée
     */
    private static function get_class() {
        $n = "\n";
        //génére la class
        $class = "<?php" . $n
                . "/** Entité de la table " . self::$_table . $n
                . "* @autor entity_generator by LEGAGNEUR Matthieu */" . $n
                . "class " . self::$_table . " {" . $n;

        $tuple_construct = "";
        $p = "1__";
        $tuple_ajout = "";
        $tuple_etter = "";
        $tuple_destruct = "";
        foreach (self::$_data as $tuple) {
            //génére les atributs 
            $class .= "/** " . $tuple[0] . " " . $n
                    . "* @var " . $tuple[1] . ' ' . $tuple[0] . " */\n private $" . "_" . $tuple[0] . ";" . $n;
            //génére lecontenu du constructeur 
            $tuple_construct .= "$" . "this->set_" . $tuple[0] . "($" . "data[\"" . $tuple[0] . "\"]);" . $n;
            if (!$tuple[2]) {
                $p .= ", $" . $tuple[0];
                if (in_array($tuple[1], ["array"])) {
                    $tuple_ajout .= "$" . $tuple[0] . " = application::$" . "_bdd->protect_var(json_encode($" . $tuple[0] . "));" . $n;
                } else {
                    $tuple_ajout .= "$" . $tuple[0] . " = application::$" . "_bdd->protect_var($" . $tuple[0] . ");" . $n;
                }
            }
            //génére les geteur avec leur type si besoin
            if (!(in_array($tuple[1], ["int", "integer", "string", "mail", "array"]))) {
                $tuple_etter .= " /** @return " . $tuple[1] . " */" . $n;
            }
            $tuple_etter .= "public function get_" . $tuple[0] . "() {" . $n
                    . "    return $" . "this->_" . $tuple[0] . ";" . $n
                    . "}" . $n;

            //génére les seteur
            $tuple_etter .= $tuple[2] ? "private" : "public";
            $tuple_etter .= " function set_" . $tuple[0] . "($" . $tuple[0] . ") {" . $n;
            switch ($tuple[1]) {
                case "string":
                    $tuple_etter .= "$" . "this->_" . $tuple[0] . " = $" . $tuple[0] . ";" . $n;
                    break;
                case "int":
                case "integer":
                    $tuple_etter .= "$" . "this->_" . $tuple[0] . " = (int) $" . $tuple[0] . ";" . $n;
                    break;
                case "mail":
                    $tuple_etter .= "if (application::$" . "_bdd->verif_email($" . $tuple[0] . " )) { $" . "this->_" . $tuple[0] . " = $" . $tuple[0] . ";}" . $n;
                    break;
                case "array":
                    $tuple_etter .= "$" . "this->_" . $tuple[0] . " = (is_array($" . $tuple[0] . ") ? $" . $tuple[0] . ": json_decode($" . $tuple[0] . ", true));" . $n;
                    break;
                default:
                    $tuple_etter .= "$" . "this->_" . $tuple[0] . " = " . $tuple[1] . "::get_from_id($" . $tuple[0] . ");" . $n;
                    break;
            }
            $tuple_etter .= ' $this->_this_was_modified = true; }' . $n;
            //génére lecontenu du destructeur 
            switch ($tuple[1]) {
                case "int":
                case "integer":
                case "string":
                case "mail":
                    $tuple_destruct .= " $" . $tuple[0] . " = application::$" . "_bdd->protect_var($" . "this->get_" . $tuple[0] . "());" . $n;
                    break;
                case "array":
                    $tuple_destruct .= " $" . $tuple[0] . " = application::$" . "_bdd->protect_var(json_encode($" . "this->get_" . $tuple[0] . "()));" . $n;
                    break;
                default:
                    $tuple_destruct .= " $" . $tuple[0] . " = application::$" . "_bdd->protect_var($" . "this->get_" . $tuple[0] . "()->get_id());" . $n;
                    break;
            }
        }
        $class .= "/** Indique si l'objet a été modifié ou non " . $n
                . "* @var boolean Indique si l'objet a été modifié ou non */" . $n
                . " private $" . "_this_was_modified;" . $n;
        $class .= "/** Indique si l'objet a été supprimé ou non " . $n
                . "* @var boolean Indique si l'objet a été supprimé ou non */" . $n
                . "private $" . "_this_was_delete;" . $n;

        //génére le constructeur
        $class .= "/** Entité de la table " . self::$_table . " */\n public function __construct($" . "data) { " . $n;
        $class .= $tuple_construct;
        $class .= '$this->_this_was_modified = false;' . $n . ' }' . $n;

        //génére la fonction statique ( static ) d'ajout
        $class .= "/** Ajoute une entrée en base de donnée */" . $n
                . " public static function ajout(";
        $class .= strtr($p, array("1__," => "")) . ") { " . $n;
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
                $class .= "'\" . $" . self::$_data[$i][0] . " . \"'";
                if (isset(self::$_data[$i + 1][0])) {
                    $class .= ", ";
                }
            }
            $i++;
        }
        $class .= ");\"); } ";

        //génére la fonction statiques ( static ) get_structure
        $class .= "/** Retourne la structure de l'entity au format json */" . $n
                . "public static function get_structure() {" . $n
                . "    return json_decode('" . json_encode(self::$_data) . "', true);" . $n
                . "}" . $n;

        //génére la fonction statiques ( static ) get_collection
        $class .= "/** Retourne le contenu de la table sout forme d'une collection " . $n
                . "*ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */" . $n
                . "public static function get_collection($" . "where = \"\" ) {" . $n
                . "    $" . "col=false;" . $n
                . "    foreach (" . self::$_table . "::get_table_array($" . "where) as $" . "entity) {" . $n
                . "        if ($" . "entity != FALSE) {" . $n
                . "            $" . "col[]=new " . self::$_table . "($" . "entity);" . $n
                . "        }" . $n
                . "    }" . $n
                . "    return $" . "col;" . $n
                . "}" . $n;

        //génére la fonction statique ( static ) get_table_array()
        $class .= "/** Retourne le contenu de la table sout forme d'un tableau a 2 dimentions " . $n
                . "* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */" . $n
                . "public static function get_table_array($" . "where = \"\") {" . $n
                . "    $" . "data = application::$" . "_bdd->fetch(\"select * from " . self::$_table . "\" . (!empty($" . "where) ? \" where \" . $" . "where : \"\") . \";\");" . $n
                . "    $" . "tuples_array = [];" . $n
                . "    foreach (self::get_structure() as $" . "s) {" . $n
                . "        if ($" . "s[1] == \"array\") {" . $n
                . "            $" . "tuples_array[] = $" . "s[0];" . $n
                . "        }" . $n
                . "    }" . $n
                . "    foreach ($" . "data as $" . "key => $" . "value) {" . $n
                . "        foreach ($" . "tuples_array as $" . "t) {" . $n
                . "            $" . "data[$" . "key][$" . "t] = json_decode(stripslashes($" . "data[$" . "key][$" . "t]), true);" . $n
                . "        }" . $n
                . "    }" . $n
                . "    return $" . "data;" . $n
                . "}" . $n;

        //génére la fonction statique ( static ) get_table_ordored_array
        $class .= "/** Retourne le contenu de la table sout forme d'un tableau a 2 dimentions dont la clé est l'identifiant de l'entité " . $n
                . "* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */" . $n
                . "public static function get_table_ordored_array($" . "where = \"\") {" . $n
                . "    $" . "data = [];" . $n
                . "    foreach (" . self::$_table . "::get_table_array($" . "where) as $" . "value) {" . $n
                . "        $" . "data[$" . "value[\"id\"]] = $" . "value;" . $n
                . "        unset($" . "data[$" . "value[\"id\"]][\"id\"]);" . $n
                . "    }" . $n
                . "    return $" . "data;" . $n
                . "}" . $n;

        //génére la fonction statique ( static ) get_count()
        $class .= "/** Retourne le nombre d'entré " . $n
                . "* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); */" . $n
                . "public static function get_count($" . "where = \"\" ) {" . $n
                . "    $" . "data = application::$" . "_bdd->fetch(\"select count(*) as count from " . self::$_table . "\".(!empty($" . "where)?\" where \" . $" . "where:\"\").\";\");" . $n
                . "    return $" . "data[0]['count'];" . $n
                . "}" . $n;

        //génére la fonction statique ( static ) get_from_id
        $class .= "/** Retourne une entité sous forme d'objet a partir de son identifiant " . $n
                . "* @return " . self::$_table . "|boolean */" . $n
                . "public static function get_from_id($" . "id) {" . $n
                . "    $" . "data = self::get_table_array(\"id='\" . application::$" . "_bdd->protect_var($" . "id) . \"'\");" . $n
                . "    return ((isset($" . "data[0]) and $" . "data[0] != FALSE) ? new " . self::$_table . "($" . "data[0]) : false);" . $n
                . "}" . $n;

        //génére la fonction statique ( static ) get_json_object
        $class .= "/** Retourne le contenu de la table sout forme d'un objet json (utile pour les services) " . $n
                . "* ATTENTION PENSEZ A UTILISER application::$" . "_bdd->protect_var(); " . $n
                . "* @return string Objet json */" . $n
                . "public static function get_json_object($" . "where = \"\") {" . $n
                . "    return json_encode(" . self::$_table . "::get_table_ordored_array($" . "where));" . $n
                . "}" . $n;

        //génére la fonction statique ( static ) delete_by_id
        $class .= "/** Supprime l'entité*/" . $n
                . "public static function delete_by_id($" . "id) {" . $n
                . "    application::$" . "_bdd->query(\"delete from " . self::$_table . " where id='\" . application::$" . "_bdd->protect_var((int)$" . "id) . \"';\");" . $n
                . "}" . $n;

        //génére la fonction de suppresion
        $class .= "/** Supprime l'entité a la fin du script*/" . $n
                . "public function delete() {" . $n
                . "    $" . "this->_this_was_delete=true;" . $n
                . "}" . $n;

        //getter et setter
        $class .= $tuple_etter;

        //génére le destructeur
        $class .= ' public function __destruct() { if ($this->_this_was_modified and !$this->_this_was_delete) { ' . $n;
        $class .= $tuple_destruct;
        $class .= " application::$" . "_bdd->query(\"update " . self::$_table . " set ";
        $i = 0;
        while (isset(self::$_data[$i][0])) {
            $class .= self::$_data[$i][0] . " = '\".$" . self::$_data[$i][0] . ".\"'";
            if (isset(self::$_data[$i + 1][0])) {
                $class .= ", ";
            }
            $i++;
        }
        $class .= " where id = '\" . $" . "id . \"';\");"
                . "        }"
                . "        if($" . "this->_this_was_delete){"
                . "            self::delete_by_id($" . "this->get_id());"
                . "        }" . $n
                . "    }" . $n
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
     * @param string $class contenu du fichier a inscrire
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
