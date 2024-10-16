<?php

/**
 * Cette classe permet de gérer et afficher des articles.
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class gestion_article {

    /**
     * Cette classe permet de gérer et afficher des articles.
     * @param bdd $bdd instance de BDD
     */
    public function __construct() {
        include __DIR__ . "/gestion_article/public_article.class.php";
        export_dwf::add_files([realpath(__DIR__."/gestion_article")]);
        $datas = [
            "cat_article" => [
                ["id", "int", true],
                ["nom", "string", false]
            ],
            "article" => [
                ["id", "int", true],
                ["date", "string", false],
                ["titre", "string", false],
                ["img", "string", false],
                ["contenu", "string", false],
                ["tags", "string", false],
                ["categorie", "cat_article", false]
            ],
            "module_article" => [
                ["id", "int", true],
                ["name", "string", false],
                ["nb", "int", false],
                ["categories", "string", false],
            ]
        ];
        foreach ($datas as $table => $data) {
            new entity_generator($data, $table, true, true);
        }
        if (cat_article::get_count("id='1' or id='2'") == 0) {
            application::$_bdd->query("insert into cat_article(id,nom) values ('1','Aucune'),('2','Toutes');");
        }
        if (!is_dir("./img/articles/media")) {
            mkdir("./img");
            mkdir("./img/articles");
            mkdir("./img/articles/media");
        }
    }

    /**
     * Affiche l'administration des articles
     */
    public function admin() {
        foreach (glob(__DIR__ . "/gestion_article/admin/*.class.php") as $class_admin) {
            include $class_admin;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {

            });
        </script>
        <?php

        $key = "admin";
        $route = [
            [$key => "g_article", "title" => "Gestion des articles", "text" => "Article"],
            [$key => "g_categorie", "title" => "Gestion des categories d'articles", "text" => "Categorie"],
            [$key => "g_module", "title" => "Gestion des modules", "text" => "Modules"]
            ];
        new sub_menu($this, $route, $key, "g_article");
    }

    /**
     * Ne pas appeler cette fontion : géré par $this->admin();
     * Affiche l'administration des articles
     */
    public function g_article() {
        (new g_article());
    }

    /**
     * Ne pas appeler cette fontion : gérée par $this->admin();
     * Affiche l'administration des catégories d'articles
     */
    public function g_categorie() {
        (new g_categorie_article());
    }

    /**
     * Ne pas appeler cette fontion : gérée par $this->admin();
     * Affiche l'administration des modules 
     */
    public function g_module() {
        (new g_module_article());
    }

    /**
     * Affiche un module avec les dernières actualités
     * @param string $name Nom du module
     */
    public function module($name = "default") {
        (new public_article())->module($name);
    }

    /**
     * Affiche le parcours des articles (publique)
     */
    public function article() {
        (new public_article())->article();
    }

}
