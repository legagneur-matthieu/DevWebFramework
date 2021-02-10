<?php

/**
 * Inscrit les erreurs HTML du site dans le log.
 * requiert que le sitemap soit actif, et que le site soit en ligne 
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class w3c_validate {

    /**
     * Inscrit les erreurs HTML du site dans le log.
     * requiert que le sitemap soit actif, et que le site soit en ligne
     */
    public function __construct() {
        if (file_exists("sitemap.json")) {
            $log = new log_file(true);
            $site = (array) json_decode(file_get_contents("sitemap.json"));
            foreach ($site as $s) {
                $sjson = json_decode(self::validate_from_url($s->loc));
                foreach ($sjson->messages as $m) {
                    if ($m->type == "error") {
                        $log->info($s->loc . " ligne " . $m->lastLine . " : " . $m->message . " (" . $m->extract . ")");
                    }
                }
            }
        }
    }

    /**
     * Retourne le statut de la page passée en paramètre
     * @param string $url url à evaluer
     * @return string statut de la page au format JSON
     */
    public static function validate_from_url($url) {
        return file_get_contents("https://validator.w3.org/nu/?doc=" . $url . "&out=json");
    }

}
