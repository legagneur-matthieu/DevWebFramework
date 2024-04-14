<?php

/**
 * Cette classe permet de gérer et afficher des articles.
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class public_article {

    /**
     * Cette classe permet de gérer et afficher des articles.
     */
    public function __construct() {
        
    }

    /**
     * Affiche un module avec les dernière actualité ( les modules sont administrable depuis $this->admin() )
     * @param string $name nom du module
     */
    public function module($name = "default") {
        $where = "name='" . bdd::p($name) . "'";
        if (module_article::get_count($where) == 0) {
            module_article::ajout($name, 3, "[2]");
        }
        $module = module_article::get_table_array($where);
        $module = module_article::get_from_id($module[0]["id"]);
        $categories = cat_article::get_table_ordored_array("id in " . bdd::p(strtr($module->get_categories(), ["{" => "(", "}" => ")", "[" => "(", "]" => ")"])));
        $mod_cat = (array) json_decode($module->get_categories());
        $cat_article = [];
        foreach ($categories as $id => $row) {
            if ($id == 2) {
                $articles = article::get_table_array("categorie!='1' order by date desc limit 0,:lim", [":lim" => $module->get_nb()]);
                $row["nom"] = "Tous les articles";
            } else {
                $articles = article::get_table_array("categorie=:id order by date desc limit 0,:lim", [":id" => (int) $id, ":lim" => $module->get_nb()]);
            }
            foreach ($articles as $art) {
                $cat_article[$row["nom"]][] = $art;
            }
        }
        js::accordion();
        ?> 
        <div id = "accordion">
            <?php
            foreach ($cat_article as $cat => $articles) {
                ?> 
                <h3><?= $cat ?></h3>
                <div>
                    <?php
                    $this->media($articles);
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }

    /**
     * Affiche l'interface utilisateur des articles
     */
    public function article() {
        if (!isset($_GET["view"])) {
            $_GET["view"] = "categories";
        }
        switch ($_GET["view"]) {
            case "article":
                $this->_article();
                break;
            case "categories":
                $this->categories();
                break;
            case "categorie":
                $this->categorie();
                break;
            case "tag":
                $this->tags();
                break;
            default:
                $this->categories();
                break;
        }
    }

    /**
     * Affiche un article
     */
    private function _article() {
        if (isset($_GET["id"]) and math::is_int($_GET["id"]) and article::get_count("id=:id", [":id" => $_GET["id"]]) > 0) {
            $article = article::get_from_id($_GET["id"]);
            ?>
            <article>
                <header>
                    <h2><?= $article->get_titre(); ?></h2>
                    <?php
                    $date = explode(" ", $article->get_date());
                    $date = time::convert_date($date[0]) . " " . $date[1];
                    ?>
                    <p>
                        <small>Publié le <span><?= html_structures::time($article->get_date(), $date); ?></span> <br />
                            Catégorie <span><?= html_structures::a_link(application::get_url(array("view", "id")) . "view=categorie&amp;id=" . $article->get_categorie()->get_id(), $article->get_categorie()->get_nom()); ?></span>
                        </small>
                    </p>
                    <?php ?>
                </header>
                <hr/>
                <?= htmlspecialchars_decode(base64_decode($article->get_contenu())); ?>
                <hr/>
                <footer>
                    <?php
                    $tags = explode(",", $article->get_tags());
                    ?>
                    <p>                        
                        <?php
                        echo html_structures::glyphicon("tags", "tags");
                        foreach ($tags as $tag) {
                            ?> <a href=<?= application::get_url(array("view", "id")); ?>view=tag&amp;tag=<?= trim($tag); ?>" rel="tag"><?= trim($tag); ?></a>,
                            <?php
                        }
                        ?>
                    </p>
                </footer>
            </article>
            <?php
        } else {
            js::redir(application::get_url(array("view", "id")) . "view=categorie&id=2");
        }
    }

    /**
     * Affiche les catégories des articles
     */
    private function categories() {
        ?>
        <h2><small>Liste des catégories</small></h2>
        <ul class="nav nav-pills nav-stacked">
            <?php
            foreach (cat_article::get_table_array() as $cat) {
                ?>
                <li>
                    <?php
                    switch ($cat["id"]) {
                        case 1:
                            $cat["nom"] = "Articles non catégorisé";
                            break;
                        case 2:
                            $cat["nom"] = "Tout les articles";
                            break;
                    }
                    echo html_structures::a_link("index.php?page=" . $_GET["page"] . "&amp;view=categorie&amp;id=" . $cat["id"], $cat["nom"]);
                    ?>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    }

    /**
     * Affiche une catégorie d'articles
     */
    private function categorie() {
        if (isset($_GET["id"]) and math::is_int($_GET["id"]) and cat_article::get_count("id=:id", [":id" => $_GET["id"]]) > 0) {
            if ($_GET["id"] == 2) {
                $p = pagination::get_limits("p", 20, article::get_count("categorie!='1'"));
                $articles = article::get_table_array("categorie!='1' order by date desc limit :lim0,:lim1", [":lim0" => $p[0], ":lim1" => $p[1]]);
            } else {
                $p = pagination::get_limits("p", 20, article::get_count("categorie=:id", [":id" => $_GET["id"]]));
                $articles = article::get_table_array("categorie=:id order by date desc limit :lim0,:lim1", [":id" => (int) $_GET["id"], ":lim0" => $p[0], ":lim1" => $p[1]]);
            }
            $cat = cat_article::get_from_id($_GET["id"]);
            ?>
            <h2><small>Catégorie : <?= $cat->get_nom(); ?></small></h2>
            <?php
            $this->media($articles);
            pagination::print_pagination("p", $p[3]);
        } else {
            js::redir("index.php?page=" . $_GET["page"] . "&view=categories");
        }
    }

    /**
     * Affiche les tags
     */
    private function tags() {
        if (isset($_GET["tag"])) {
            $count = article::get_count("tags like '" . bdd::p($_GET["tag"]) . "'");
            if ($count == 0) {
                js::redir("index.php?page=" . $_GET["page"] . "&view=categories");
            } else {
                ?>
                <h2><small>Tag : <?= $_GET["tag"]; ?></small></h2>
                <?php
                $p = pagination::get_limits("p", 20, $count);
                $articles = article::get_table_array("tags like '" . bdd::p($_GET["tag"]) . "' order by date desc limit " . $p[0] . "," . $p[1]);
                $this->media($articles);
                pagination::print_pagination("p", $p[3]);
            }
        } else {
            js::redir("index.php?page=" . $_GET["page"] . "&view=categories");
        }
    }

    /**
     * Affiche les articles sous forme de media (Bootstrap)
     * @param array $articles
     */
    private function media($articles) {
        ?>
        <div class="media">
            <?php
            foreach ($articles as $art) {
                $contenu_prev = substr(strtr(strip_tags(htmlspecialchars_decode(base64_decode($art["contenu"]))), ["<p>" => "", "</p>" => ""]), 0, 200);
                $contenu_prev = substr($contenu_prev, 0, strrpos($contenu_prev, " ")) . " ...";
                $art["contenu"] = $contenu_prev;
                if (!empty($art["img"])) {
                    ?>
                    <div class="media-left">
                        <img class="media-object" src="<?= $art["img"] ?>" alt="">
                    </div>
                    <?php
                }
                ?>
                <div class="media-body">
                    <h4 class="media-heading"><a href="<?= application::get_url(array("view", "id")); ?>view=article&amp;id=<?= $art["id"] ?>"><?= $art["titre"]; ?></a></h4>
                    <?= $art["contenu"]; ?>
                </div>
                <hr />
                <?php
            }
            ?>
        </div>
        <?php
    }
}
