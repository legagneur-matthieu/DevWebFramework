<?php

/**
 * Cette classe permet la création et l'utilisation de services
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class service {

    /**
     * Envoie une requête par méthode POST sans attendre de réponses !
     * 
     * @param string $url URL du service ( attention : les varibles GET sont ignorées !)
     * @param array $params Paramètres POST
     */
    public static function HTTP_POST($url, $params = []) {
        ob_start();
        $post = http_build_query($params);
        $parts = parse_url($url);
        $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
        $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Accept-Language: fr,fr-FR;q=0.8,en;q=0.5,en-US;q=0.3\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: " . strlen($post) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        if (isset($post)) {
            $out .= $post;
        }
        fwrite($fp, $out);
        fclose($fp);
    }

    /**
     * Envoie une requête par méthode POST et retourne la réponse
     * 
     * @param string $url URL du service ( attention : les varibles GET sont ignorées !)
     * @param array $params Paramètres POST
     * @param boolean $ssl utiliser le protocole HTTPS ? (true ou false, false par default)
     * @return string|boolean Réponse du service ou false en cas de problème.
     */
    public static function HTTP_POST_REQUEST($url, $params, $ssl = false) {
        $req = [
            'header' => "Accept-Language: fr,fr-FR;q=0.8,en;q=0.5,en-US;q=0.3\r\nContent-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($params)
        ];
        ($ssl ? $options['https'] = $req : $options['http'] = $req);
        return file_get_contents($url, false, stream_context_create($options));
    }

    /**
     * Envoie une requête par méthode GET et attend le résultat du service
     * 
     * @param string $url url du service (avec les paramètres get)
     * @return string|boolean Réponse du service ou false en cas de problème.
     */
    public static function HTTP_GET($url) {
        $str = "";
        $file = @file($url);
        if (isset($file) and ! empty($file)) {
            foreach ($file as $value) {
                $str .= $value;
            }
            return $str;
        } else {
            return false;
        }
    }

    /**
     * Retourne le statut d'une page (utile pour verifier l'état d'un service)
     * @param string $host adresse hÃ´te, exemple : www.duckduckgo.com
     * @param string $port port à verifier (80 par defaut)
     * @param string $page page à verifier (index.php par defaut)
     * @return string statut code ( sous forme : "HTTP/1.1 404 Not Found")
     */
    public static function HTTP_get_STATUS($host, $port = 80, $page = "index.php") {
        $fp = @fsockopen($host, $port, $errno, $errstr, 10);
        if ($fp) {
            fwrite($fp, "GET /$page HTTP/1.1\r\nHost: $host\r\nConnection: Close\r\n\r\n");
            $content = fgets($fp);
            fclose($fp);
            return $content;
        }
        return "HTTP/1.1 404 Not Found";
    }

    /**
     * Fonction de whitelite à mettre au début du service , si une IP non autorisée tente d'acceder au service, le script s'arrête sans executer la suite
     * 
     * @param array $IP_Allow liste des IP à autoriser
     */
    public static function security_check($IP_Allow = ["localhost", "127.0.0.1", "::1"]) {
        if (!in_array($_SERVER["REMOTE_ADDR"], $IP_Allow)) {
            exit(403);
        }
    }

    /**
     * Convertit une chaine XML en JSON
     * @param string $xml_string Chaine XML
     * @return string JSON
     */
    public static function xml_to_json($xml_string) {
        return json_encode(simplexml_load_string($xml_string));
    }

    /**
     * Convertit une chaine CSV en JSON
     * @param string $csv_string Chaine CSV
     * @return string JSON
     */
    public static function csv_to_json($csv_string) {
        return json_encode(array_map("str_getcsv", explode("\n", $csv_string)));
    }

}
