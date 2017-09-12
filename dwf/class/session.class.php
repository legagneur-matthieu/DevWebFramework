<?php

/**
 * Cette classe g�re les variables de sessions sp�cifiques � l'application actuelle
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class session {

    /**
     * D�marre la session avec v�rification de l'ip et du navigateur
     * 
     * @param boolean $regenerate_id l'id de la session doit-il �tre r�g�n�r� : � mettre � false si utilis� dans un service !
     */
    public static function start($regenerate_id = true) {
        session_start();
        if ($regenerate_id) {
            session_regenerate_id(true);
        }
        if (!self::get_val("ip")) {
            self::set_val("ip", $_SERVER["REMOTE_ADDR"]);
            self::set_val("browser", $_SERVER["HTTP_USER_AGENT"]);
        }
        if (self::get_val("ip") != $_SERVER["REMOTE_ADDR"] or self::get_val("browser") != $_SERVER["HTTP_USER_AGENT"]) {
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
     * @param bool $value L'utilisateur est-il authentifi� ? (true / false)
     */
    public static function set_auth($value) {
        $_SESSION[config::$_prefix . "_auth"] = $value;
    }

    /**
     * R�cupere la valeur de $_SESSION[config::$_prefix . "_auth"] (true / false)
     * @return bool L'utilisateur est-il authentifi� ? (true / false)
     */
    public static function get_auth() {
        return (isset($_SESSION[config::$_prefix . "_auth"]) ? $_SESSION[config::$_prefix . "_auth"] : false);
    }

    /**
     * Set l'identifiant de l'utilisateur
     * @param int $value Identifiant de l'utilisateur
     */
    public static function set_user($value) {
        $_SESSION[config::$_prefix . "_user"] = $value;
    }

    /**
     * R�cupere l'identifiant de l'utilisateur
     * @return int Identifiant de l'utilisateur
     */
    public static function get_user() {
        return (isset($_SESSION[config::$_prefix . "_user"]) ? $_SESSION[config::$_prefix . "_user"] : false);
    }

    /**
     * Set la langue de l'utilisateur
     * @param string $value Langue de l'utilisateur
     */
    public static function set_lang($value) {
        $_SESSION[config::$_prefix . "_lang"] = $value;
    }

    /**
     * R�cupere la langue de l'utilisateur
     * @return string Langue de l'utilisateur
     */
    public static function get_lang() {
        return (isset($_SESSION[config::$_prefix . "_lang"]) ? $_SESSION[config::$_prefix . "_lang"] : false);
    }

    /**
     * Set une varible de session sp�cifique � l'application actuelle
     * @param string $key Cl� de la variable
     * @param int|string|bool $value Valeur de la variable
     */
    public static function set_val($key, $value) {
        $_SESSION[config::$_prefix . "_" . $key] = $value;
    }

    /**
     * R�cupere une varible de session sp�cifique � l'application actuelle
     * @param string $key Cl� de la variable
     * @return int|string|bool Valeur de la variable
     */
    public static function get_val($key) {
        return (isset($_SESSION[config::$_prefix . "_" . $key]) ? $_SESSION[config::$_prefix . "_" . $key] : false);
    }

}
