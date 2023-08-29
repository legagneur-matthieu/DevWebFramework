<?php

/**
 * Cette classe permet de generer des liens LURL
 * Ou un boutton LURL sponsorisé qui redirige vers la page courante
 * 
 * https://lurl.fr
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class lurl {

    /**
     * API KEY
     * @var string API KEY
     */
    private $_api_key;

    /**
     * Cette classe permet de generer des liens LURL
     * Ou un boutton LURL sponsorisé qui redirige vers la page courante
     *
     * https://lurl.fr
     * 
     * @param string $api_key Clé API de LURL 
     */
    public function __construct($api_key) {
        $this->_api_key = $api_key;
        entity_generator::generate([
            "lurl_links" => [
                ["id", "int", true],
                ["url", "string", false],
                ["lurl", "string", false],
            ]
        ]);
    }

    /**
     * Retoure une LURL a partir d'une URL 
     * (cette url sera enregistré en base de données)
     * @param string $url URL
     * @return string|boolean LURL 
     */
    public function get_lurl($url) {
        $lurl = lurl_links::get_collection("url='" . bdd::p($url) . "'");
        if (isset($lurl[0])) {
            $result = [
                "status" => "success",
                "shortenedUrl" => $lurl[0]->get_lurl(),
            ];
        } else {
            $result = @json_decode(file_get_contents("https://lurl.fr/api?api={$this->_api_key}&url={$url}&type=2"), TRUE);
            if ($result["status"] === "success") {
                lurl_links::ajout($url, $result["shortenedUrl"]);
            }
        }
        if ($result["status"] === 'error') {
            $log = new log_file(true);
            foreach ($result["message"] as $msg) {
                $log->warning("LURL $url : $msg");
            }
            return false;
        } else {
            return $result["shortenedUrl"];
        }
    }

    /**
     * Affiche un boutton LURL sponsorisé qui redirige vers la page courante
     * Cela permet a vos visiteur de vous soutenir en regardant une publicité
     * Limité a 1 click par page et par session 
     * 
     * @param string $text Test du boutton
     * @param string $title Title du boutton 
     */
    public function selfpage_support_btn($text = "Soutenez nous <br />(Publicité)", $title = "Soutenez nous en cliquant sur ce lien publicitaire") {
        if (!session::get_val("lurl")) {
            session::set_val("lurl", []);
        }
        $url = "{$_SERVER["REQUEST_SCHEME"]}://{$_SERVER["HTTP_HOST"]}{$_SERVER["REQUEST_URI"]}";
        if (!in_array($_SERVER["HTTP_HOST"], ["localhost", "127.0.0.1"])) {
            $get = (strpos($url, "?") ? "&lurl=1" : "?lurl=1");
            if ($lurl = $this->get_lurl($url . $get)) {
                if (!in_array($lurl, session::get_val("lurl"))) {
                    if (isset($_GET['lurl'])) {
                        session::set_val("lurl", array_merge(session::get_val("lurl"), [$lurl]));
                    } else {
                        echo html_structures::a_link($lurl, html_structures::bi("heart") . " $text", "btn btn-outline-danger text-center", $title);
                    }
                }
            }
        }
    }

}
