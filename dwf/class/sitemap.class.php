<?php

/**
 * Cette classe gère les sitemaps de vos projets.
 * Elle permet d'ajouter, de supprimer et de générer des sitemaps au format XML.
 * Elle offre également des fonctionnalités pour afficher les URLs dans une liste HTML
 * et pour gérer les URLs via une interface d'administration.
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.fr>
 */
class sitemap extends singleton {

    /**
     * Tableau contenant les URLs du sitemap.
     * Chaque élément du tableau est un tableau associatif avec les clés "loc" et "title".
     * La clé "loc" correspond à l'URL de la page et la clé "title" correspond au titre de la page.
     * @var array
     */
    private $_urls = [];

    /**
     * Indique si le sitemap a été modifié depuis sa dernière écriture dans le fichier XML.
     * @var bool
     */
    private $_changed = false;

    /**
     * Chemin complet vers le fichier sitemap.xml.
     * @var string
     */
    private $_file;

    /**
     * Cette classe gère les sitemaps de vos projets.
     * Elle permet d'ajouter, de supprimer et de générer des sitemaps au format XML.
     * Elle offre également des fonctionnalités pour afficher les URLs dans une liste HTML
     * et pour gérer les URLs via une interface d'administration.
     */
    protected function __construct() {
        $this->_file = $_SERVER["DOCUMENT_ROOT"] . "/sitemap.xml";

        if (file_exists($this->_file)) {
            ($dom = new DOMDocument())->load($this->_file);
            foreach ($dom->getElementsByTagName("url") as $url) {
                $title = $url->getElementsByTagName("title")->item(0);
                $this->_urls[] = [
                    "loc" => $url->getElementsByTagName("loc")->item(0)->nodeValue,
                    "title" => ($title ? $title->nodeValue : "")
                ];
            }
        }
    }

    /**
     * Ajoute une nouvelle URL au sitemap avec éventuellement un titre personnalisé.
     * @param string $url L'URL de la page à ajouter au sitemap.
     * @param string $title (optionnel) Le titre personnalisé de la page.
     */
    public function add_url($url, $title = "") {
        if (class_exists("config") && config::$_sitemap && !session::get_auth() && stripos($url, "http://localhost/") === false && !in_array($url, array_column($this->_urls, "loc"))) {
            $this->_urls[] = ["loc" => $url, "title" => $title];
            $this->_changed = true;
        }
    }

    /**
     * Supprime une URL du sitemap en fonction de son attribut "loc".
     * @param string $loc L'URL de la page à supprimer du sitemap.
     */
    public function remove_url($loc) {
        foreach ($this->_urls as $key => $url) {
            if ($url["loc"] === $loc) {
                unset($this->_urls[$key]);
                $this->_changed = true;
                break;
            }
        }
    }

    /**
     * Génère une liste HTML des URLs du sitemap.
     */
    public function HTML() {
        $li = [];
        foreach ($this->_urls as $url) {
            $li[] = html_structures::a_link($url["loc"], $url["title"]);
        }
        echo html_structures::ul($li);
    }

    /**
     * Affiche une interface d'administration pour gérer les URLs du sitemap.
     * Cette interface permet de supprimer des URLs individuelles.
     */
    public function admin() {
        if (isset($_GET["loc"])) {
            $this->remove_url($_GET["loc"]);
            js::redir(application::get_url(["loc"]));
        }
        $data = [];
        foreach ($this->_urls as $url) {
            $data[] = [
                $url["loc"],
                $url["title"],
                html_structures::a_link(application::get_url() . "loc={$url["loc"]}", "Supprimer", "btn- btn-danger")
            ];
        }
        js::datatable();
        html_structures::table(["Loc", "Title", "Supprimer"], $data, "", "datatable");
    }

    /**
     * Destructeur de la classe.
     * Écrit les modifications apportées au sitemap dans le fichier XML s'il y a eu des changements.
     */
    public function __destruct() {
        if ($this->_changed) {
            sort($this->_urls);
            $sitemapContent = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $sitemapContent .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
            foreach ($this->_urls as $url) {
                $sitemapContent .= "\t<url>\n\t\t<loc>{$url["loc"]}</loc>\n";
                if (!empty($url["title"])) {
                    $sitemapContent .= "\t\t<title>{$url["title"]}</title>\n";
                }
                $sitemapContent .= "\t</url>\n";
            }
            $sitemapContent .= "</urlset>";
            file_put_contents($this->_file, $sitemapContent);
        }
    }
}
