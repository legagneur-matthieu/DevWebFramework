<?php

/**
 * Cette classe permet de faire appel a ScraperAPI
 *
 * https://scraperapi.com
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class scraperapi {

    /**
     * Clé API de ScaperAPI 
     * @var string Clé API de ScaperAPI
     */
    private $_api_key;

    /**
     * Cette classe permet de faire appel a ScraperAPI
     *
     * https://scraperapi.com
     * @param string $api_key Clé API de ScaperAPI
     */
    public function __construct($api_key) {
        $this->_api_key = $api_key;
    }

    /**
     * Retourne le HTML de l'URL cible
     *
     * @param string $url URL à scraper
     * @return string HTML de l'URL cible
     */
    public function get($url) {
        return self::getHTML($this->_api_key, $url);
    }

    /**
     * Retourne le HTML de l'URL cible
     *
     * @param string $api_key Clé API de ScaperAPI
     * @param string $url URL à scraper
     * @return string HTML de l'URL cible
     */
    public static function getHTML($api_key, $url) {
        return file_get_contents("http://api.scraperapi.com?api_key={$api_key}&url={$url}");
    }

}
