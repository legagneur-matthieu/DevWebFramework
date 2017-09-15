<?php

/**
 * Cette classe gère l'administration des articles
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class g_article {

    /**
     * Cette classe gère l'administration des articles
     */
    public function __construct() {
        if (isset($_GET["action"])) {
            if ($_GET["action"] == "modif") {
                $this->g_article_modif();
            } elseif ($_GET["action"] == "supp") {
                $this->g_article_supp();
            } else {
                $this->g_article_default();
            }
        } else {
            $this->g_article_default();
        }
    }

    /**
     * Gère la modification d'articles
     */
    private function g_article_modif() {
        if (article::get_count("id='" . application::$_bdd->protect_var($_GET["id"]) . "'") != 0) {
            $article = article::get_from_id($_GET["id"]);
            $categories = cat_article::get_table_ordored_array();
            $option = array();
            foreach ($categories as $key => $value) {
                $option[] = array($key, $value["nom"], ($key == $article->get_categorie()->get_id()));
            }
            $cke = js::ckeditor("contenu");
            form::new_form("", "#", "post", true);
            form::new_fieldset("Modifier un article");
            form::input("Titre de l'article", "titre", "text", $article->get_titre());
            form::datetimepicker("Différer la publication ( laissez vide pour ne pas différer )", "date");
            form::file("Image de préview", "img", false);
            form::textarea("Contenu de l'article", "contenu", htmlspecialchars_decode(base64_decode($article->get_contenu())));
            form::input("Tags (séparé par des viruges)", "tags", "text", $article->get_tags());
            form::select("Catégorie", "cat", $option);
            form::submit("btn-default");
            form::close_fieldset();
            form::close_form();
            if (isset($_POST["titre"])) {
                if (!empty($_POST["date"])) {
                    $date = form::get_datetimepicker_us("date");
                    if ($date < date("Y-m-d H:i")) {
                        $date = date("Y-m-d H:i");
                    }
                } else {
                    $date = date("Y-m-d H:i");
                }
                if (!empty($_FILES["img"]["name"])) {
                    unlink($article->get_img());
                    $_FILES["img"]["name"] = strtr($_FILES["img"]["name"], array(
                        "\"" => "",
                        "'" => "",
                        "\\" => "",
                        "/" => "",
                        "?" => "",
                        "!" => "",
                        "&" => "",
                        ";" => "",
                        "(" => "",
                        ")" => "",
                        "[" => "",
                        "]" => "",
                        "{" => "",
                        "}" => "",
                        ":" => "",
                        "," => "",
                        "@" => "",
                        "-" => "_"
                    ));
                    $img = "./img/articles/media/" . $_FILES["img"]["name"];
                    form::get_upload("img", "./img/articles/media", array("image/png", "image/jpg", "image/jpeg", "image/bmp"));
                    form::resize_img($img, $img, 0, 720);
                    $article->set_img($img);
                }
                $contenu = base64_encode($cke->parse($_POST["contenu"]));
                $article->set_date($date);
                $article->set_contenu($contenu);
                $article->set_titre(ucfirst($_POST["titre"]));
                $article->set_tags($_POST["tags"]);
                $article->set_categorie($_POST["cat"]);
                js::alert("Votre article a bien été modifié");
                js::redir("");
            }
        } else {
            js::alert("ERREUR : Cet article n'existe pas !");
            js::redir("index.php?page=" . $_GET["page"] . "&admin=g_article");
        }
    }

    /**
     * Gère la suppresion d'articles
     */
    private function g_article_supp() {
        application::$_bdd->query("delete from article where id='" . application::$_bdd->protect_var($_GET["id"]) . "';");
        js::alert("L'article a bien été supprimé");
        js::redir("index.php?page=" . $_GET["page"] . "&admin=g_article");
    }

    /**
     * Gère l'affichage et l'ajout d'articles
     */
    private function g_article_default() {
        $articles = article::get_table_array();
        $categories = cat_article::get_table_ordored_array();
        js::datatable();
        ?>
        <table class="table" id="datatable">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date</th>
                    <th>Catégorie</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($articles as $article) {
                    ?>
                    <tr>
                        <td><?php echo html_structures::a_link("index.php?page=" . $_GET["page"] . "&amp;action=modif&amp;id=" . $article["id"], $article["titre"]); ?></td>
                        <td><?php
                            $date = explode(" ", $article["date"]);
                            echo time::convert_date($date[0]) . " " . $date[1];
                            ?></td>
                        <td><?php echo $categories[$article["categorie"]]["nom"]; ?></td>
                        <td><?php echo html_structures::a_link("index.php?page=" . $_GET["page"] . "&amp;admin=" . $_GET["admin"] . "&amp;action=supp&amp;id=" . $article["id"], html_structures::glyphicon("remove", "Supprimer"), "btn btn-xs btn-danger navbar-right"); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <hr />
        <?php
        $option = array();
        foreach ($categories as $id => $row) {
            $option[] = array($id, $row["nom"]);
        }
        /* TODO : (bug mineur) CKE provoque une bad request dans la console */
        $cke = js::ckeditor("contenu");
        form::new_form("", "#", "post", true);
        form::new_fieldset("Ajouter un article");
        form::input("Titre de l'article", "titre");
        form::datetimepicker("Différer la publication ( laissez vide pour ne pas différer )", "date");
        form::file("Image de préview", "img", false);
        form::textarea("Contenu de l'article", "contenu");
        form::input("Tags (séparé par des viruges)", "tags");
        form::select("Catégorie", "cat", $option);
        form::submit("btn-default");
        form::close_fieldset();
        form::close_form();
        if (isset($_POST["titre"])) {
            if (!empty($_POST["date"])) {
                $date = form::get_datetimepicker_us("date");
                if ($date < date("Y-m-d H:i")) {
                    $date = date("Y-m-d H:i");
                }
            } else {
                $date = date("Y-m-d H:i");
            }
            $img = "";
            if (!empty($_FILES["img"]["name"])) {
                $_FILES["img"]["name"] = strtr($_FILES["img"]["name"], array(
                    "\"" => "",
                    "'" => "",
                    "\\" => "",
                    "/" => "",
                    "?" => "",
                    "!" => "",
                    "&" => "",
                    ";" => "",
                    "(" => "",
                    ")" => "",
                    "[" => "",
                    "]" => "",
                    "{" => "",
                    "}" => "",
                    ":" => "",
                    "," => "",
                    "@" => "",
                    "-" => "_"
                ));
                $img = "./img/articles/media/" . $_FILES["img"]["name"];
                form::get_upload("img", "./img/articles/media", array("image/png", "image/jpg", "image/jpeg", "image/bmp"));
                form::resize_img($img, $img, 0, 720);
            }
            $contenu = base64_encode($cke->parse($_POST["contenu"]));
            article::ajout($date, ucfirst($_POST["titre"]), $img, $contenu, $_POST["tags"], $_POST["cat"]);
            js::alert("Votre article a bien été créé");
            js::redir("");
        }
    }

}
