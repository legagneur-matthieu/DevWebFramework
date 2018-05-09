<?php

/**
 * Cette classe gère l'administration des modules d'articles
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class g_module_article {

    /**
     * Cette classe gère l'administration des modules d'articles
     */
    public function __construct() {
        if (isset($_GET["action"])) {
            if ($_GET["action"] == "mod_modif") {
                $this->g_module_modif();
            } elseif ($_GET["action"] == "mod_supp") {
                $this->g_module_supp();
            } else {
                $this->g_module_default();
            }
        } else {
            $this->g_module_default();
        }
    }

    /**
     * Gère la modification de catégories
     */
    private function g_module_modif() {
        if (module_article::get_count("id='" . application::$_bdd->protect_var($_GET["id"]) . "'") != 0) {
            $module = module_article::get_from_id($_GET["id"]);
            $cat = cat_article::get_table_ordored_array();
            form::new_form();
            form::new_fieldset("Modifier un module");
            form::input("Nom du module", "mod_nom", "text", $module->get_name());
            form::input("Nomre d'articles par catégories visible dans le module", "mod_nb", "number", $module->get_nb());
            $cat_mod = (array) json_decode($module->get_categories());
            foreach ($cat as $id => $row) {
                form::checkbox($row["nom"], "mod_cat[]", $id, "", (in_array($id, $cat_mod)));
            }
            form::submit("btn-default");
            form::close_fieldset();
            form::close_form();
            if (isset($_POST["mod_nom"])) {
                $mod_cat = array();
                foreach ($_POST["mod_cat"] as $value) {
                    $mod_cat[] = (int) $value;
                }
                $module->set_name($_POST["mod_nom"]);
                $module->set_nb($_POST["mod_nb"]);
                $module->set_categories(json_encode($mod_cat));
                js::alert("Votre module a bien été modifié");
                js::redir("");
            }
        }
    }

    /**
     * Gère la suppresion de catégories
     */
    private function g_module_supp() {
        application::$_bdd->query("delete from module_article where id='" . application::$_bdd->protect_var($_GET["id"]) . "';");
        js::alert("Le module a bien été supprimé");
        js::redir("index.php?page=" . $_GET["page"] . "&admin=g_module");
    }

    /**
     * Gère l'affichage et l'ajout de catégories
     */
    private function g_module_default() {
        $modules = module_article::get_table_array();
        $cat = cat_article::get_table_ordored_array();
        js::datatable();
        ?>
        <table class="table" id="datatable">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Nombre d'articles/catégories</th>
                    <th>catégories</th>
                    <th>supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($modules as $mod) {
                    ?>
                    <tr>
                        <?php ?>
                        <td><?php echo html_structures::a_link("index.php?page=" . $_GET["page"] . "&amp;admin=" . $_GET["admin"] . "&amp;action=mod_modif&amp;id=" . $mod["id"], $mod["name"]); ?></td>
                        <td><?php echo $mod["nb"]; ?></td>
                        <td> 
                            <ul>
                                <?php
                                foreach (((array) json_decode($mod["categories"])) as $c) {
                                    ?>
                                    <li>
                                        <?php
                                        echo $cat[$c]["nom"];
                                        ?>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </td>
                        <td><?php echo html_structures::a_link("index.php?page=" . $_GET["page"] . "&amp;admin=" . $_GET["admin"] . "&amp;action=mod_supp&amp;id=" . $mod["id"], html_structures::glyphicon("remove", "Supprimer"), "btn btn-xs btn-danger navbar-right"); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <hr />
        <p class="alert alert-warning">
            ATTENTION ! l'affichage d'un nouveau module nécéssite une intervention directement dans le code de l'application ! <br />
            Faites appele a un informaticien qualifié ou référez vous a la documentation.
        </p>
        <?php
        form::new_form();
        form::new_fieldset("Ajouter un module");
        form::input("Nom du module", "mod_nom");
        form::input("Nomre d'articles par catégories visible dans le module", "mod_nb", "number", "3");
        foreach ($cat as $id => $row) {
            form::checkbox($row["nom"], "mod_cat[]", $id);
        }
        form::submit("btn-default");
        form::close_fieldset();
        form::close_form();
        if (isset($_POST["mod_nom"])) {
            if (module_article::get_count("name='" . application::$_bdd->protect_var($_POST["mod_nom"]) . "'") == 0) {
                $mod_cat = array();
                foreach ($_POST["mod_cat"] as $value) {
                    $mod_cat[] = (int) $value;
                }
                module_article::ajout($_POST["mod_nom"], $_POST["mod_nb"], json_encode($mod_cat));
                js::alert("Votre module a bien été ajouté");
                js::redir("");
            } else {
                js::alert("ERREUR : Ce module existe déjà !");
            }
        }
    }

}
