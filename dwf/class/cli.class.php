<?php

/**
 * Cette classe constitue une "boite à outils", utile pour les applications en PHP-CLI (console)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class cli {

    /**
     * Taille de la dernière chaine affichée
     * @var int Taille de la dernière chaine affichée
     */
    private static $_len = 0;

    /**
     * Charge les classes du framework pour le cli 
     */
    public static function classloader() {
        spl_autoload_register(function ($class) {
            $file = __DIR__ . "/" . $class . ".class.php";
            if (file_exists($file)) {
                require_once $file;
            }
        });
    }

    /**
     * Demande une saisie "utilisateur"
     * @param string $label label de l'input
     * @return string retourne la saisie de l'utilisateur
     */
    public static function read($label) {
        self::write($label . "\n");
        return trim(fgets(STDIN));
    }

    /**
     * Affiche du texte sur une nouvelle ligne de la console
     * @param string $str
     */
    public static function write($str) {
        echo "\n" . self::accents($str);
        self::$_len = strlen($str);
    }

    /**
     * Réécrit la dernière ligne
     * @param string $str
     */
    public static function rewrite($str) {
        echo "\r" . self::accents($str);
        for ($i = 0; $i < (self::$_len - strlen($str)); $i++) {
            echo " ";
        }
        self::$_len = strlen($str);
    }

    /**
     * Marque un temps de pause
     * @param int $second Nombre de secondes que durera la pause
     * @param boolean $show Afficher un minuteur (true/false, false par defaut)
     */
    public static function wait($second, $show = false) {
        if ($show) {
            $mt = microtime(true) + $second;
            self::write(time::parse_time($second));
            while (microtime(true) <= $mt) {
                self::rewrite(time::parse_time($mt - microtime(true)));
            }
            self::rewrite("");
        } else {
            sleep($second);
        }
    }

    /**
     * Transforme les accents en leur forme hexa
     * @param string $str Chaine avec accents à transformer
     * @return string Chaine avec accents transformés
     */
    private static function accents($str) {
        return strtr($str, [
            'ü' => "\x81",
            'é' => "\x82",
            'â' => "\x83",
            'ä' => "\x84",
            'à' => "\x85",
            'ç' => "\x87",
            'ê' => "\x88",
            'ë' => "\x89",
            'è' => "\x8A",
            'ï' => "\x8B",
            'î' => "\x8C",
            'É' => "\x90"]
        );
    }

}
