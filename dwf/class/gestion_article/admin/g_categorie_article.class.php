<?php

/**
 * Cette classe gère l'administration des catégories d'articles
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class g_categorie_article {

    /**
     * Cette classe gère l'administration des catégories d'articles
     * @param bdd $bdd Instance de BDD
     */
    public function __construct(bdd $bdd) {
        if (isset($_GET["action"])) {
            if ($_GET["action"] == "cat_modif") {
                $this->g_categorie_modif();
            } elseif ($_GET["action"] == "cat_supp") {
                $this->g_categorie_supp();
            } else {
                $this->g_categorie_default();
            }
        } else {
            $this->g_categorie_default();
        }
    }

    /**
     * Gère la modification de catégories
     */
    private function g_categorie_modif() {
        if ($_GET["id"] != 1 and $_GET["id"] != 2 and math::is_int($_GET["id"])) {
            if (cat_article::get_count("id='" . application::$_bdd->protect_var($_GET["id"]) . "'") != 0) {
                $cat = cat_article::get_from_id($_GET["id"]);
                form::new_form();
                form::new_fieldset("Modifer une categorie");
                form::input("Nom de la catégorie", "cat_nom", "", $cat->get_nom());
                form::submit("btn-default", "Modifier");
                form::close_fieldset();
                form::close_form();
                if (isset($_POST["cat_nom"])) {
                    $cat->set_nom($_POST["cat_nom"]);
                    js::alert("La catégorie a bien été modifié");
                    js::redir("index.php?page=" . $_GET["page"] . "&admin=g_categorie");
                }
            } else {
                js::alert("ERREUR : Cette catégorie n'existe pas !");
                js::redir("index.php?page=" . $_GET["page"] . "&admin=g_categorie");
            }
        } else {
            js::alert("ERREUR : Cette catérogie est protégé !");
            js::redir("index.php?page=" . $_GET["page"] . "&admin=g_categorie");
        }
    }

    /**
     * Gère la suppresion de catégories
     */
    private function g_categorie_supp() {
        if ($_GET["id"] != 1 and $_GET["id"] != 2 and math::is_int($_GET["id"])) {
            if (cat_article::get_count("id='" . application::$_bdd->protect_var($_GET["id"]) . "'") != 0) {
                $cat = cat_article::get_table_array("id='" . application::$_bdd->protect_var($_GET["id"]) . "'");
                $articles = article::get_collection("categorie='" . application::$_bdd->protect_var($cat[0]["id"]) . "'");
                if ($articles) {
                    foreach ($articles as $a) {
                        $a->set_categorie("1");
                    }
                }
                $modules = module_article::get_collection();
                if ($modules) {
                    foreach ($modules as $m) {
                        $categories = (array) json_decode($m->get_categories());
                        if (in_array($_GET["id"], $categories)) {
                            $m->set_categories(strtr($m->get_categories(), array($cat[0]["id"] . "," => "", "," . $cat[0]["id"] . "}" => "}", "," . $cat[0]["id"] . "]" => "]")));
                        }
                    }
                }
                application::$_bdd->query("delete from cat_article where id='" . application::$_bdd->protect_var($cat[0]["id"]) . "';");
                js::alert("La catégorie \"" . $cat[0]["nom"] . "\" a bien été supprimé");
            } else {
                js::alert("ERREUR : Cette catégorie n'existe pas !");
            }
        } else {
            js::alert("ERREUR : Cette catérogie est protégé !");
        }
        js::redir("index.php?page=" . $_GET["page"] . "&admin=g_categorie");
    }

    /**
     * Gère l'affichage et l'ajout de catégories
     */
    private function g_categorie_default() {
        $categogies = cat_article::get_table_array();
        js::datatable();
        ?>
        <table class="table" id="datatable">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($categogies as $cat) {
                    ?>
                    <tr>
                        <?php
                        if ($cat["id"] != 1 and $cat["id"] != 2) {
                            ?>
                            <td><?php echo html_structures::a_link("index.php?page=" . $_GET["page"] . "&amp;admin=" . $_GET["admin"] . "&amp;action=cat_modif&amp;id=" . $cat["id"], $cat["nom"]); ?></td>
                            <td><?php echo html_structures::a_link("index.php?page=" . $_GET["page"] . "&amp;admin=" . $_GET["admin"] . "&amp;action=cat_supp&amp;id=" . $cat["id"], html_structures::glyphicon("remove", "Supprimer"), "btn btn-xs btn-danger navbar-right"); ?></td>
                            <?php
                        } else {
                            ?>
                            <td><?php echo $cat["nom"]; ?></td>
                            <td></td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <hr />
        <?php
        form::new_form();
        form::new_fieldset("Ajouter une categorie");
        form::input("Nom de la catégorie", "cat_nom");
        form::submit("btn-default");
        form::close_fieldset();
        form::close_form();
        if (isset($_POST["cat_nom"])) {
            if (cat_article::get_count("nom='" . application::$_bdd->protect_var($_POST["cat_nom"]) . "'") == 0) {
                cat_article::ajout($_POST["cat_nom"]);
                js::alert("Votre catégorie a bien été ajouté");
                js::redir("");
            } else {
                js::alert("ERREUR : Cette catégorie existe déjà !");
            }
        }
    }

}
