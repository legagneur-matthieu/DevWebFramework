<?php

/**
 * Cette classe permet de recuperer des information sur une adresse ip (géolocalisation, operateur ...)
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class ip_api extends singleton {

    /**
     * Verifie si ipapi.js a bien été appelé une fois
     * @var boolean Verifie si ipapi.js a bien été appelé une fois
     */
    private static $_called = false;

    /**
     * URL de ip-api
     * @var string URL de ip-api
     */
    private $base_url = "http://ip-api.com";

    /**
     * Tableau des limites de requetes 
     * @var array Tableau des limites de requetes 
     */
    private $ipapi = [];

    /**
     * Cette classe permet de recuperer des information sur une adresse ip (géolocalisation, operateur ...)
     */
    public function __construct() {
        entity_generator::generate([
            "ipapi" => [
                ["id", "int", true],
                ["ep", "string", false],
                ["rl", "int", false],
                ["ttl", "int", false],
            ]
        ]);
        if (ipapi::get_count() == 0) {
            ipapi::ajout("json", 45, time());
            ipapi::ajout("batch", 15, time());
        }
        $this->ipapi = [
            "json" => ipapi::get_collection("ep='json'")[0],
            "batch" => ipapi::get_collection("ep='batch'")[0]
        ];
    }

    /**
     * Retourne les informations de https://ip-api.com/json/$ip
     * Attention limité a 45 requetes par minutes
     * @param string $ip IP à géolocaliser
     * @return array|false Tableau des informations sur l'IP ou false en cas d'échec
     */
    public function json($ip) {
        return $this->sendRequest($ip);
    }

    /**
     * Retourne les informations de https://ip-api.com/batch
     * POST $ips
     * Attention limité a 15 requetes par minutes et 100 IP par requetes 
     * @param array $ips tableau d'IP à géolocaliser
     * @return array|false Tableau des informations sur les IPs ou false en cas d'échec
     */
    public function batch($ips) {
        return $this->sendRequest($ips);
    }

    /**
     * Envoie une requête HTTP à l'API et récupère la réponse
     * @param string $endpint "json/$ip" ou "batch"
     * @param string $method Méthode HTTP (GET ou POST)
     * @param string|null $postData Données POST (pour la méthode POST)
     * @return array|false Tableau des données de réponse ou false en cas d'échec
     */
    private function sendRequest($data) {
        if (is_array($data)) {
            $endpoint = "batch";
            $method = "POST";
            $rdata = json_encode($data);
            $url = "{$this->base_url}/{$endpoint}";
        } else {
            $endpoint = "json";
            $method = "GET";
            $rdata = urlencode($data);
            $url = "{$this->base_url}/{$endpoint}/{$rdata}";
        }
        if ($this->ipapi[$endpoint]->get_rl() > 0 or $this->ipapi[$endpoint]->get_ttl() < time()) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($method === 'POST') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $rdata);
            }
            $response = curl_exec($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $responseHeaders = substr($response, 0, $header_size);
            $response = substr($response, $header_size);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            preg_match('/X-Rl: (\d+)/', $responseHeaders, $rateLimitMatches);
            preg_match('/X-Ttl: (\d+)/', $responseHeaders, $resetTimeMatches);
            $rateLimitMatches = isset($rateLimitMatches[1]) ? intval($rateLimitMatches[1]) : 0;
            $resetTimeMatches = isset($resetTimeMatches[1]) ? time() + intval($resetTimeMatches[1]) + 1 : time() + 61;
            $this->ipapi[$endpoint]->set_rl($rateLimitMatches);
            $this->ipapi[$endpoint]->set_ttl($resetTimeMatches);
            $this->ipapi[$endpoint]->update();
            curl_close($ch);
            if ($httpCode === 200) {
                return json_decode($response, true);
            } else {
                set_time_limit(0);
                $time = $this->ipapi[$endpoint]->get_ttl() - time() + 1;
                if ($time > 0) {
                    sleep($time);
                    return $this->sendRequest($data);
                } else {
                    return false;
                }
            }
        } else {
            set_time_limit(0);
            $time = $this->ipapi[$endpoint]->get_ttl() - time() + 1;
            if ($time > 0) {
                sleep($time);
                return $this->sendRequest($data);
            } else {
                return false;
            }
        }
    }

    /**
     * Verifie si ipapi.js a bien été appelé une fois
     */
    private function call() {
        if (!self::$_called) {
            echo html_structures::script("../commun/src/js/ipapi/ipapi.js");
            self::$_called = true;
        }
    }

    /**
     * Retourne les informations de https://ip-api.com/json/$ip
     * Attention : 
     * - le navigateur client envoie la requete et renvoi le resultat !
     * - limité a 45 requetes par minutes et par utilisateur
     * @param string $ip IP à géolocaliser
     * @param function $callback fonction de callback
     * function($ip_data){}
     * @return array|false Tableau des informations sur l'IP ou false en cas de non réponse
     */
    public function json_browser($ip, $callback) {
        $this->call();
        ?>
        <script>
            $(document).ready(function () {
                ipapi.json("<?= $ip ?>");
            });
        </script>
        <?php
        if (isset($_GET["ipapi_data"])) {
            return $callback($_GET["ipapi_data"]);
        }
        return false;
    }

    /**
     * Retourne les informations de https://ip-api.com/batch
     * POST $ips
     * Attention limité a 15 requetes par minutes et 100 IP par requetes 
     * Attention : 
     * - le navigateur client envoie la requete et renvoi le resultat !
     * - limité a 15 requetes par minutes et 100 IP par requetes et par utilisateur
     * @param string $ip IP à géolocaliser
     * @param function $callback fonction de callback
     * function($ip_data){}
     * @return array|false Tableau des informations sur les IPs ou false en cas de non réponse
     */
    public function batch_browser($ips, $callback) {
        $this->call();
        ?>
        <script>
            $(document).ready(function () {
                ipapi.batch(<?= json_encode($ips) ?>);
            });
        </script>
        <?php
        if (isset($_GET["ipapi_data"])) {
            return $callback($_GET["ipapi_data"]);
        }
        return false;
    }
}
