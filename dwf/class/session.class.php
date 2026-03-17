<?php

/**
 * Cette classe gère les variables de sessions spécifiques à l'application actuelle
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class session {

    /**
     * Démarre la session avec vérification de l'ip et du navigateur
     * 
     * @param boolean $regenerate_id l'id de la session doit-il étre régénérée : à mettre à false si utilisé dans un service !
     */
    public static function start($regenerate_id = true) {
        $host = explode(':', $_SERVER['HTTP_HOST'])[0];
        if (filter_var($host, FILTER_VALIDATE_IP) and !filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            ini_set("session.cookie_secure", "0");
        } else {
            ini_set("session.cookie_secure", "1");
        }
        ini_set("session.cookie_samesite", "Lax");
        ini_set('session.cookie_httponly', "1");
        session_start();
        ($regenerate_id and self::get_auth() ? session_regenerate_id(true) : null);
        $fp = [];
        foreach ([
    "REMOTE_ADDR",
    "HTTP_USER_AGENT",
    "HTTP_ACCEPT",
    "HTTP_ACCEPT_LANGUAGE",
    "HTTP_ACCEPT_ENCODING"
        ] as $key) {
            (isset($_SERVER[$key]) ? $fp[] = $_SERVER[$key] : false);
        }
        $fps = [];
        if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {
            header('Accept-CH: Sec-CH-UA, Sec-CH-UA-Full-Version-List, Sec-CH-UA-Platform, Sec-CH-UA-Platform-Version, Sec-CH-UA-Mobile, Sec-CH-UA-Arch, Sec-CH-UA-Bitness, Sec-CH-UA-Model, Device-Memory, ECT');
            if (isset($_SERVER['HTTP_SEC_CH_UA'])) {
                foreach ([
            "HTTP_SEC_CH_UA",
            "HTTP_SEC_CH_UA_FULL_VERSION_LIST",
            "HTTP_SEC_CH_UA_PLATFORM",
            "HTTP_SEC_CH_UA_PLATFORM_VERSION",
            "HTTP_SEC_CH_UA_MOBILE",
            "HTTP_DEVICE_MEMORY",
            "HTTP_ECT",
                ] as $key) {
                    (isset($_SERVER[$key]) ? $fps[] = $_SERVER[$key] : false);
                }
            }
        }
        $hash = [
            application::hash(implode("_", $fp)),
            (count($fps) ? application::hash(implode("_", $fps)) : "")
        ];
        if (!self::get_val("security_token")) {
            self::set_val("security_token", $hash);
        } else {
            if (empty(self::get_val("security_token")[1]) and !empty($hash[1]) and self::get_val("security_token")[0] == $hash[0]) {
                self::set_val("security_token", $hash);
            }
        }
        if (self::get_val("security_token")[0] != $hash[0] or self::get_val("security_token")[1] != $hash[1]) {
            session_destroy();
            ?>
            <script type="text/javascript">
                localStorage.clear();
                sessionStorage.clear();
                window.location = "index.php";
            </script>
            <?php

            exit();
        }
    }

    /**
     * Set la valeur de $_SESSION[config::$_prefix . "_auth"] (true / false)
     * @param bool $value L'utilisateur est-il authentifié ? (true / false)
     */
    public static function set_auth($value) {
        self::set_val("auth", $value);
    }

    /**
     * Récupere la valeur de $_SESSION[config::$_prefix . "_auth"] (true / false)
     * @return bool L'utilisateur est-il authentifié ? (true / false)
     */
    public static function get_auth() {
        return self::get_val("auth");
    }

    /**
     * Set l'identifiant de l'utilisateur
     * @param int|user $user Identifiant ou Entity de l'utilisateur
     * @param string $entity non de l'entity/table utilisateur ("user" par défaut)
     */
    public static function set_user($user, $entity = "user") {
        self::set_val("user", (math::is_int($user) ? $user : $entity->get_id()));
    }

    /**
     * Récupere l'identifiant de l'utilisateur
     * @deprecated since version 21.22.12 use get_user_id() or get_user_entity()
     * @return int Identifiant de l'utilisateur
     */
    public static function get_user() {
        return self::get_user_id();
    }

    /**
     * Retourne l'identifiant de l'utilisateur
     * @return int Identifiant de l'utilisateur
     */
    public static function get_user_id() {
        return self::get_val("user");
    }

    /**
     * Retourne l'entity utilisateur
     * @param string $entity non de l'entity/table utilisateur ("user" par défaut)
     * @return user|boolean Entity utilisateur ou false
     */
    public static function get_user_entity($entity = "user") {
        return $entity::get_from_id(self::get_user_id());
    }

    /**
     * Set la langue de l'utilisateur
     * @param string $value Langue de l'utilisateur
     */
    public static function set_lang($value) {
        self::set_val("lang", $value);
    }

    /**
     * Récupere la langue de l'utilisateur
     * @return string Langue de l'utilisateur
     */
    public static function get_lang() {
        return self::get_val("lang");
    }

    /**
     * Set une varible de session spécifique à l'application actuelle
     * @param string $key Clé de la variable
     * @param int|string|bool $value Valeur de la variable
     */
    public static function set_val($key, $value) {
        $_SESSION[config::$_prefix . "_" . $key] = $value;
    }

    /**
     * Récupere une varible de session spécifique à l'application actuelle
     * @param string $key Clé de la variable
     * @return int|string|bool Valeur de la variable
     */
    public static function get_val($key) {
        return (isset($_SESSION[config::$_prefix . "_" . $key]) ? $_SESSION[config::$_prefix . "_" . $key] : false);
    }
}
