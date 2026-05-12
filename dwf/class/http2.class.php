<?php

/**
 * Cette classe permet de gérer le header LINK de http/2.0
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class http2 extends singleton {

    /**
     * Tableau des links par catégories
     * @var array Tableau des links par catégories
     */
    private $_links = [
        "preload" => [],
        "mercure" => [],
        "dns_prefetch" => [],
        "preconnect" => [],
        "prefetch" => [],
        "prerender" => []
    ];

    /**
     * Permet de précharger une ressource (fichiers css, js ou immage)
     * @param string $file Fichier à précharger
     * @param string|boolean $as Precise le type de ressource (style, script, ..., FALSE pour ne pas renseigner le type)
     */
    public function preload($file, $as = false) {
        $this->_links[__FUNCTION__][] = "<$file>; rel=\"preload\"" . ($as ? "; $as" : "");
    }

    /**
     * Permet de préconnecter un serveur mercure (SSE)
     * @param string $mercure_url lien vers le serveur / topic mercure
     */
    public function mercure($mercure_url) {
        $this->_links[__FUNCTION__][] = "<$mercure_url>; rel=\"mercure\"";
    }

    /**
     * Permet de pré-résoudre le DNS d'un lien ou d'une ressource externe
     * @param string $file lien ou ressource a prè-résoudre
     * @param string|boolean $as Precise le type de ressource (style, script, ..., FALSE pour ne pas renseigner le type)
     */
    public function dns_prefetch($file, $as = false) {
        $this->_links[__FUNCTION__][] = "<$file>; rel=\"dns_prefetch\"" . ($as ? "; $as" : "");
    }

    /**
     * Permet de ce préconnecter a un lien ou une ressource
     * @param string $file lien ou ressource a prèconnecter
     * @param string|boolean $as Precise le type de ressource (style, script, ..., FALSE pour ne pas renseigner le type)
     */
    public function preconnect($file, $as = false) {
        $this->_links[__FUNCTION__][] = "<$file>; rel=\"preconnect\"" . ($as ? "; $as" : "");
    }

    /**
     * Permet de précharger un lien ou une ressource
     * @param string $file lien ou ressource a prècharger
     * @param string|boolean $as Precise le type de ressource (style, script, ..., FALSE pour ne pas renseigner le type)
     */
    public function prefetch($file, $as = false) {
        $this->_links[__FUNCTION__][] = "<$file>; rel=\"prefetch\"" . ($as ? "; $as" : "");
    }

    /**
     * Permet de prérendre un lien ou une ressource
     * @param string $file lien ou ressource a prèrendre
     * @param string|boolean $as Precise le type de ressource (style, script, ..., FALSE pour ne pas renseigner le type)
     */
    public function prerender($file, $as = false) {
        $this->_links[__FUNCTION__][] = "<$file>; rel=\"prerender\"" . ($as ? "; $as" : "");
    }

    /**
     * Cette methode envoi le header LINK si les conditions le permettent
     * /!\ Cette methode est appelé automatiquement dans html5.class.php
     */
    public function make_link() {
        if ($_SERVER["SERVER_PROTOCOL"] == "HTTP/2.0") {
            $header = "Link : ";
            foreach ($this->_links as $links) {
                if (count($links)) {
                    $header .= implode(",", $links) . ",";
                }
            }
            $header = strtr("{$header}__", [",__" => ""]);
            header($header, false);
        }
    }

    /**
     * Cette fonction permet d'afficher et débug le header LINK
     */
    public function print_link() {
        $header = "Link : ";
        foreach ($this->_links as $links) {
            if (count($links)) {
                $header .= implode(",", $links) . ",";
            }
        }
        $header = strtr("{$header}__", [",__" => ""]);
        debug::print_r($header);
    }

}
