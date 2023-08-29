<?php

/**
 * Créé une interface d'administration 'user-friendly' pour une table 
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class admin_controle {

    /**
     * Nom de l'entité
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
     * @param string $entity Nom de l'entité
     * @param array $relations association entre une clé étrangère et un tuple de la table étrangère : array("cle_etrangere1"=>"tuple_de_la_table_etrengerre1",...) <br />
     * exemple : array("rang"=>"nom");
     */
    public function __construct($entity, $relations = []) {
        $this->_entity = $entity;
        $this->_relations = $relations;
        $this->_structure = $entity::get_structure();
        foreach (array_keys($this->_relations)as $key) {
            foreach ($this->_structure as $value) {
                if ($key == $value[0]) {
                    $this->_relations_data[$key] = $value[1]::get_table_ordored_array();
                }
            }
        }
        $this->_data = $entity::get_table_array();
        $url = application::get_url(["action", "id"]);
        foreach ($this->_structure as $value) {
            if (in_array($value[1], ["bool", "boolean"])) {
                foreach ($this->_data as $key => $row) {
                    $this->_data[$key][$value[0]] = ($this->_data[$key][$value[0]] ? "Oui" : "Non");
                }
            }
            if (!$value[2] and $value[1] != "array") {
                $this->_head[] = ucfirst($value[0]);
            } elseif ($value[1] == "array") {
                foreach ($this->_data as $key => $row) {
                    unset($this->_data[$key][$value[0]]);
                }
            }
        }
        foreach ($this->_data as $key => $row) {
            foreach ($row as $k => $v) {
                if (application::$_bdd->verif_email($v)) {
                    $this->_data[$key][$k] = '<a href="mailto:' . $v . '">' . $v . '</a>';
                }
            }
            $m_d = html_structures::a_link($url . "action=modif&amp;id=" . $row["id"], html_structures::glyphicon("edit", "Modifier") . " Modifier", "btn btn-xs btn-primary");
            $m_d .= html_structures::a_link($url . "action=supp&amp;id=" . $row["id"], html_structures::glyphicon("remove", "Supprimer") . " Supprimer", "btn btn-xs btn-danger navbar-right");
            $this->_data[$key]["m_d"] = $m_d;
            foreach ($this->_relations as $k => $v) {
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
     * Affiche le tableau listant les entités
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
        $form = new form();
        foreach ($this->_structure as $element) {
            if (!$element[2]) {
                switch ($element[1]) {
                    case "int":
                    case "interger":
                        $form->input(ucfirst($element[0]), $element[0], "number");
                        break;
                    case "bool":
                    case "boolean":
                        $form->select(ucfirst($element[0]), $element[0], [[0, "Non", false], [1, "Oui", false]]);
                        break;
                    case "mail":
                        $form->input(ucfirst($element[0]), $element[0], "email");
                        break;
                    case "string":
                        if ($element[0] == "psw" or $element[0] == "password") {
                            $form->input(ucfirst($element[0]), $element[0], "password");
                        } else {
                            $form->input(ucfirst($element[0]), $element[0]);
                        }
                        break;
                    case "array":
                        $form->hidden($element[0], "[]");
                        break;
                    default:
                        $elem = $element[1];
                        $elem = $elem::get_table_array("1=1 order by " . bdd::p($this->_relations[$element[0]]));
                        $option = [];
                        foreach ($elem as $e) {
                            $option[] = [$e["id"], $e[$this->_relations[$element[0]]]];
                        }
                        $form->select(ucfirst($element[0]), $element[0], $option);
                        break;
                }
            }
        }
        $form->hidden("admin_form_ajout", "1");
        $form->submit("btn-primary", "Ajouter");
        echo $form->render();
    }

    /**
     * Exécution du formulaire d'ajout
     */
    private function ajout_exec() {
        if (isset($_POST["admin_form_ajout"])) {
            $req = "INSERT INTO " . bdd::p($this->_entity) . " (";
            $key = "";
            $value = "";
            foreach ($this->_structure as $element) {
                if (!$element[2]) {
                    $key .= " " . $element[0] . ",";
                    if ($element[0] == "psw" or $element[0] == "password") {
                        $value .= " '" . bdd::p(application::hash($_POST[$element[0]])) . "',";
                    } else {
                        $value .= " '" . bdd::p($_POST[$element[0]]) . "',";
                    }
                }
            }
            $req .= "{$key}__) VALUES ({$value}__);";
            $req = strtr($req, $from = [",__)" => ")"]);
            application::$_bdd->query($req);
            js::redir("");
        }
    }

    /**
     * Affiche le formulaire de modification
     */
    private function modif_form() {
        $url = application::get_url(["action", "id"]);
        $entity = $this->_entity;
        $object = $entity::get_from_id($_GET["id"]);
        $this->modif_exec($object, $url);
        echo html_structures::a_link($url, html_structures::glyphicon("arrow-left", "") . " Retour", "btn btn-primary");
        $form = new form();
        foreach ($this->_structure as $element) {
            if (!$element[2]) {
                $geter = "get_" . $element[0];
                switch ($element[1]) {
                    case "int":
                    case "interger":
                        $form->input(ucfirst($element[0]), $element[0], "number", $object->$geter());
                        break;
                    case "bool":
                    case "boolean":
                        $form->select(ucfirst($element[0]), $element[0], [[0, "Non", $object->$geter() == 0], [1, "Oui", $object->$geter() == 1]]);
                        break;
                    case "mail":
                        $form->input(ucfirst($element[0]), $element[0], "email", $object->$geter());
                        break;
                    case "string":
                        if ($element[0] == "psw" or $element[0] == "password") {
                            $form->input(ucfirst($element[0]), $element[0], "password");
                        } else {
                            $form->input(ucfirst($element[0]), $element[0], "text", $object->$geter());
                        }
                        break;
                    case "array":
                        $form->hidden($element[0], json_encode($object->$geter()));
                        break;
                    default:
                        $elem = $element[1];
                        $elem = $elem::get_table_array("1=1 order by " . bdd::p($this->_relations[$element[0]]));
                        $option = [];
                        foreach ($elem as $e) {
                            $selected = false;
                            if ($e["id"] == $object->$geter()->get_id()) {
                                $selected = true;
                            }
                            $option[] = [$e["id"], $e[$this->_relations[$element[0]]], $selected];
                        }
                        $form->select(ucfirst($element[0]), $element[0], $option);
                        break;
                }
            }
        }
        $form->hidden("admin_form_modif", "1");
        $form->submit("btn-primary", "Modifier");
        echo $form->render();
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
                            $object->$seter(application::hash($_POST[$element[0]]));
                        }
                    } else {
                        $object->$seter($_POST[$element[0]]);
                    }
                }
            }
            js::redir(strtr($url, ["&amp;" => "&"]));
        }
    }

    /**
     * Affiche le formulaire de suppression (pour confirmation)
     */
    private function supp_form() {
        $url = application::get_url(["action", "id"]);
        $this->supp_exec($url);
        $entity = $this->_entity;
        $data = $entity::get_table_array("id='" . bdd::p($_GET["id"]) . "';");
        foreach ($data as $key => $value) {
            foreach ($this->_relations as $k => $v) {
                $data[$key][$k] = $this->_data[$key][$k];
            }
            unset($data[$key]['id']);
        }
        foreach ($this->_structure as $value) {
            if (in_array($value[1], ["bool", "boolean"])) {
                foreach ($data as $key => $row) {
                    $data[$key][$value[0]] = ($data[$key][$value[0]] ? "Oui" : "Non");
                }
            }
            if ($value[1] == "array") {
                foreach ($data as $key => $row) {
                    unset($data[$key][$value[0]]);
                }
            }
        }
        js::datatable("supp_datatable");
        $form = new form("form-inline");
        echo tags::tag("p", ["class" => "text-center"], "ETES VOUS SUR DE VOULOIR SUPPRIMER CETTE ELÉMENT :") .
        html_structures::table($this->_head, $data, "supp_datatable") .
        $form->get_open_form() .
        $form->hidden("admin_form_supp", "1") .
        $form->submit("btn-danger", "Oui") .
        html_structures::a_link($url, "Non", "btn btn-primary") .
        $form->get_close_form();
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
            js::redir(strtr($url, ["&amp;" => "&"]));
        }
    }

}
