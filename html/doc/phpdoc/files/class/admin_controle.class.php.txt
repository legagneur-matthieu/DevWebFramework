<?php

/**
 * Créé une interface d'administration 'user-friendly' pour une table 
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class admin_controle {

    /**
     * Nom de l'entity
     * 
     * @var string Nom de l'entity
     */
    private $_entity;

    /**
     * Association entre une clé étrangère et un tuple de la table étrangère : array("cle_etrangere1"=>"tuple_de_la_table_etrengerre1",...) <br />
     * exemple : array("rang"=>"nom");
     * 
     * @var array association entre une clé étrangère et un tuple de la table étrangère
     */
    private $_relations;

    /**
     * Tableau ordonné des relations 
     * @var array Tableau ordonné des relations 
     */
    private $_relations_data;

    /**
     * entity::get_table_array()
     * 
     * @var array entity::get_table_array()
     */
    private $_data;

    /**
     * Entête du tableau
     * 
     * @var type 
     */
    private $_head;

    /**
     * entity::get_structure();
     * 
     * @var array entity::get_structure();
     */
    private $_structure;

    /**
     * Créé une interface d'administration 'user-friendly' pour une table 
     * @param string $entity Nom de l'entity
     * @param array $relations association entre une clé étrangère et un tuple de la table étrangère : array("cle_etrangere1"=>"tuple_de_la_table_etrengerre1",...) <br />
     * exemple : array("rang"=>"nom");
     */
    public function __construct($entity, $relations = array()) {
        $this->_entity = $entity;
        $this->_relations = $relations;
        foreach (array_keys($this->_relations)as $key) {
            $this->_relations_data[$key] = $key::get_table_ordored_array();
        }
        $this->_data = $entity::get_table_array();
        $this->_structure = $entity::get_structure();
        $url = application::get_url(array("action", "id"));
        foreach ($this->_structure as $value) {
            if (!$value[2]) {
                $this->_head[] = ucfirst($value[0]);
            }
        }
        foreach ($this->_data as $key => $row) {
            foreach ($row as $k => $v) {
                if (application::$_bdd->verif_email($v)) {
                    $this->_data[$key][$k] = '<a href="mailto:' . $v . '">' . $v . '</a>';
                }
            }
            $m_d = html_structures::a_link($url . "action=modif&amp;id=" . $row["id"], html_structures::glyphicon("edit", "Modifier") . " Modifier", "btn btn-xs btn-default");
            $m_d .= html_structures::a_link($url . "action=supp&amp;id=" . $row["id"], html_structures::glyphicon("remove", "Supprimer") . " Supprimer", "btn btn-xs btn-danger navbar-right");
            $this->_data[$key]["m_d"] = $m_d;
            foreach ($this->_relations as $k => $v) {
                foreach ($this->_structure as $ks => $vs) {
                    if ($vs[0] == $k) {
                        $rs = $vs[1];
                        break;
                    }
                }
                $this->_data[$key][$k] = $this->_relations_data[$k][$this->_data[$key][$k]][$v];
            }
            unset($this->_data[$key]["id"]);
        }
        if (!isset($_GET["action"])) {
            $_GET["action"] = "vue";
        }
        switch ($_GET["action"]) {
            case "modif":
                $this->modif_form();
                break;
            case "supp":
                $this->supp_form();
                break;
            default:
                $this->table();
                break;
        }
    }

    /**
     * Affiche le tableau qui liste les entity
     */
    private function table() {
        js::datatable();
        $url = "index.php?";
        foreach ($_GET as $key => $value) {
            $url .= $key . "=" . $value . "&amp;";
        }
        $head = $this->_head;
        $head[] = "Modifier / Supprimer";
        echo html_structures::table($head, $this->_data, "Tableau d'administration de l'entité : " . $this->_entity, "datatable") . "<hr />";
        $this->ajout_form();
    }

    /**
     * Affiche le formulaire d'ajout
     */
    private function ajout_form() {
        $this->ajout_exec();
        form::new_form();
        foreach ($this->_structure as $element) {
            if (!$element[2]) {
                if ($element[1] == "int" or $element[1] == "integer") {
                    form::input(ucfirst($element[0]), $element[0], "number");
                } elseif ($element[1] == "mail") {
                    form::input(ucfirst($element[0]), $element[0], "email");
                } elseif ($element[1] == "string") {
                    if ($element[0] == "psw" or $element[0] == "password") {
                        form::input(ucfirst($element[0]), $element[0], "password");
                    } else {
                        form::input(ucfirst($element[0]), $element[0]);
                    }
                } else {
                    $elem = $element[1];
                    $elem = $elem::get_table_array("1=1 order by " . application::$_bdd->protect_var($this->_relations[$element[0]]));
                    $option = array();
                    foreach ($elem as $e) {
                        $option[] = array($e["id"], $e[$this->_relations[$element[0]]]);
                    }
                    form::select(ucfirst($element[0]), $element[0], $option);
                }
            }
        }
        form::hidden("admin_form_ajout", "1");
        form::submit("btn-default", "Ajouter");
        form::close_form();
    }

    /**
     * Execution du formulaire d'ajout
     */
    private function ajout_exec() {
        if (isset($_POST["admin_form_ajout"])) {
            $req = "INSERT INTO " . application::$_bdd->protect_var($this->_entity) . " (";
            $key = "";
            $value = "";
            foreach ($this->_structure as $element) {
                if (!$element[2]) {
                    $key .= " " . $element[0] . ",";
                    if ($element[0] == "psw" or $element[0] == "password") {
                        $value .= " '" . application::$_bdd->protect_var(hash(config::$_hash_algo, $_POST[$element[0]])) . "',";
                    } else {
                        $value .= " '" . application::$_bdd->protect_var($_POST[$element[0]]) . "',";
                    }
                }
            }
            $req .= $key . "1) VALUES (" . $value . "1);";
            $req = strtr($req, $from = array(",1)" => ")"));
            application::$_bdd->query($req);
            js::redir("");
        }
    }

    /**
     * Affiche le formulaire de modification
     */
    private function modif_form() {
        $url = application::get_url(array("action", "id"));
        $entity = $this->_entity;
        $object = $entity::get_from_id($_GET["id"]);
        $this->modif_exec($object, $url);
        echo html_structures::a_link($url, html_structures::glyphicon("arrow-left", "") . " Retour", "btn btn-default");
        form::new_form();
        foreach ($this->_structure as $element) {
            if (!$element[2]) {
                $geter = "get_" . $element[0];
                if ($element[1] == "int" or $element[1] == "integer") {
                    form::input(ucfirst($element[0]), $element[0], "number", $object->$geter());
                } elseif ($element[1] == "mail") {
                    form::input(ucfirst($element[0]), $element[0], "email", $object->$geter());
                } elseif ($element[1] == "string") {
                    if ($element[0] == "psw" or $element[0] == "password") {
                        form::input(ucfirst($element[0]), $element[0], "password");
                    } else {
                        form::input(ucfirst($element[0]), $element[0], "text", $object->$geter());
                    }
                } else {
                    $elem = $element[1];
                    $elem = $elem::get_table_array("1=1 order by " . application::$_bdd->protect_var($this->_relations[$element[0]]));
                    $option = array();
                    foreach ($elem as $e) {
                        $selected = false;
                        if ($e["id"] == $object->$geter()->get_id()) {
                            $selected = true;
                        }
                        $option[] = array($e["id"], $e[$this->_relations[$element[0]]], $selected);
                    }
                    form::select(ucfirst($element[0]), $element[0], $option);
                }
            }
        }
        form::hidden("admin_form_modif", "1");
        form::submit("btn-default", "Modifier");
        form::close_form();
    }

    /**
     * Exécution du formulaire de modification
     * 
     * @param object $object l'entity a modifier
     * @param string $url application::get_url(array("action","id"));
     */
    private function modif_exec($object, $url) {
        if (isset($_POST["admin_form_modif"])) {
            foreach ($this->_structure as $element) {
                if (!$element[2]) {
                    $seter = "set_" . $element[0];
                    if ($element[0] == "psw" or $element[0] == "password") {
                        if (!empty($_POST[$element[0]])) {
                            $object->$seter(hash(config::$_hash_algo, $_POST[$element[0]]));
                        }
                    } else {
                        $object->$seter($_POST[$element[0]]);
                    }
                }
            }
            js::redir(strtr($url, $from = array("&amp;" => "&")));
        }
    }

    /**
     * Affiche le formulaire de suppression (pour confirmation)
     */
    private function supp_form() {
        $url = application::get_url(array("action", "id"));
        $this->supp_exec($url);
        $entity = $this->_entity;
        $data = $entity::get_table_array("id='" . application::$_bdd->protect_var($_GET["id"]) . "';");
        foreach ($data as $key => $value) {
            foreach ($this->_relations as $k => $v) {
                foreach ($this->_structure as $ks => $vs) {
                    if ($vs[0] == $k) {
                        $rs = $vs[1];
                        break;
                    }
                }
                $this->_data[$key][$k] = $this->_relations_data[$k][$this->_data[$key][$k]][$v];
            }
            unset($data[$key]['id']);
        }
        ?>
        <p class="text-center">ÊTES VOUS SUR DE VOULOIR SUPPRIMER CETTE ÉLÉMENT :</p>
        <?php

        js::datatable();
        echo html_structures::table($this->_head, $data, "datatable");
        form::new_form("form-inline");
        form::hidden("admin_form_supp", "1");
        form::submit("btn-danger", "Oui");
        echo html_structures::a_link($url, "Non", "btn btn-default");
        form::close_form();
    }

    /**
     * Exécution du formulaire de suppression
     * 
     * @param string $url application::get_url(array("action","id"));
     */
    private function supp_exec($url) {
        if (isset($_POST["admin_form_supp"])) {
            $entity = $this->_entity;
            $entity::delete_by_id($_GET["id"]);
            js::redir(strtr($url, $from = array("&amp;" => "&")));
        }
    }

}
