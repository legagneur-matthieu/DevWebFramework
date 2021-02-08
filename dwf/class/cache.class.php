<?php

/**
 * Cette classe permet de gérer une mise en cache ( côté serveur )
 * utilise session::set_val("cache",[]) et session::get_val("cache")
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class cache extends singleton {

    /**
     * Données du cache
     * @var array Données du cache
     */
    private $_cache;

    /**
     * Cette classe permet de gérer une mise en cache ( côté serveur )
     * utilise session::set_val("cache",[]) et session::get_val("cache")
     */
    public function __construct() {
        $this->load();
    }

    /**
     * Sauve les données du cache à la destruction de l'objet
     */
    public function __destruct() {
        $this->save();
    }

    /**
     * Charge les données du cache
     */
    private function load() {
        $this->_cache = (session::get_val("cache") ? session::get_val("cache") : []);
        $this->clear();
    }

    /**
     * Sauve les données du cache
     */
    private function save() {
        $this->clear();
        session::set_val("cache", $this->_cache);
    }

    /**
     * Vide le cache de toutes les données périmées
     */
    private function clear() {
        foreach ($this->_cache as $key => $value) {
            if (isset($this->_cache[$key]["timeout"]) and $this->_cache[$key]["timeout"] < microtime(true)) {
                unset($this->_cache[$key]);
            }
        }
    }

    /**
     * Stocke une donnée dans le cache pour un temps défini ou jusqu'à expiration de la session.
     * @param string $key Clé de la donnée dans le cache
     * @param mixed $value Valeur de la donnée
     * @param boolean|int $timeout Durée de vie de la donnée dans le cache en seconde (false par defaut : vie jusqu'à expriration de la session)
     */
    public static function set($key, $value, $timeout = false) {
        self::get_instance()->_cache[$key]["value"] = $value;
        if ($timeout !== false) {
            self::get_instance()->_cache[$key]["timeout"] = microtime(true) + $timeout;
        }
    }

    /**
     * Retourne une donnée du cache ( false si la donnée est inexistante ou expirée)
     * @param string $key Clé de la donnée dans le cache
     * @return boolean|mixed Donnée
     */
    public static function get($key) {
        if (isset(self::get_instance()->_cache[$key]["timeout"]) and self::get_instance()->_cache[$key]["timeout"] < microtime(true)) {
            unset(self::get_instance()->_cache[$key]);
            return false;
        }
        return (isset(self::get_instance()->_cache[$key]["value"]) ? self::get_instance()->_cache[$key]["value"] : false);
    }

    /**
     * Supprime tout le cache ou une donnée du cache
     * @param boolean|string $key Clé de la donnée dans le cache (false par defaut : efface tout le cache)
     */
    public static function del($key = false) {
        if ($key) {
            unset(self::get_instance()->_cache[$key]);
        } else {
            self::get_instance()->_cache = [];
        }
    }

}
