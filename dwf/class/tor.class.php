<?php

/**
 * Cette classe permet de récuperer une ressource en passant par tor
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class tor {

    private $_host;
    private $_port;

    /**
     * Cette classe permet de récuperer une ressource en passant par tor
     * @param string $host IP du proxy tor
     * @param type $port Port du proxy tor
     */
    public function __construct($host = "127.0.0.1", $port = 9050) {
        $this->_host = $host;
        $this->_port = $port;
    }

    /**
     * 
     * @param string $url URL de la ressource à récuperer
     * @return mixed Ressource
     */
    public function wget($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PROXY, "http://{$this->_host}:{$this->_port}/");
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5_HOSTNAME);
        $output = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);
        return $output;
    }

}