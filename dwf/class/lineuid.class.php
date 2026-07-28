<?php

/**
 * Retourn un UID stable.
 * depend du fichier, de la ligne et du nombre d'appel de get_uid(); 
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class lineuid {

    /**
     * Compteurs
     * @var int[] Compteurs
     */
    private static $_counter = [];

    /**
     * Retourne le UID
     * @param int $lvl niveau de profondeur a lire : 
     * 0=appel directe lineuid::get_uid();
     * 1=lineuid::get_uid() est appelé dans le constructeur ou une methode static d'une class
     * 2=lineuid::get_uid() est appelé dans une methode d'une class
     * @param boolean $debug affiche le debug pour etre sur du niveau de profondeur récupéré
     * @return string UID
     */
    public static function get_uid($lvl = 1, $debug = false) {
        if (!isset(self::$_counter[$file = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[$lvl]["file"]])) {
            self::$_counter[$file] = [];
        }
        if (!isset(self::$_counter[$file][$line = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[$lvl]["line"]])) {
            self::$_counter[$file][$line] = 0;
        } else {
            self::$_counter[$file][$line]++;
        }
        $classid = sha1($key = implode(":", [
            $file, $line, self::$_counter[$file][$line]
        ]));
        if ($debug) {
            debug::print_r([$key, $classid]);
        }
        return $classid;
    }

    /**
     * Retourne les compteurs
     * @return int[] Compteurs
     */
    public static function get_counter() {
        return self::$_counter;
    }
}
