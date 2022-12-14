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
        session_start();
        ($regenerate_id ? session_regenerate_id(true) : null);
        $_SERVER["HTTP_USER_AGENT"] = (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "Unknown");
        $hash = hash(config::$_hash_algo, "{$_SERVER["REMOTE_ADDR"]}_{$_SERVER["HTTP_USER_AGENT"]}");
        (!self::get_val("security_token") ? self::set_val("security_token", $hash) : null);
        if (self::get_val("security_token") != $hash) {
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
