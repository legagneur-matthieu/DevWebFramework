<?php

/**
 * Cette classe permet de recharger automatiquement la page courante lorsqu'une
 * classe metier est modifié dans le dossier /class/ de votre projet
 * 
 * (Cette classe est déconsillé en production)
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class change_reload extends singleton {

    /**
     * Permet de vérifier que change_reload a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que change_reload a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette classe permet de recharger automatiquement la page courante lorsqu'une
     * classe metier est modifié dans le dossier /class/ de votre projet
     * 
     * (Cette classe est déconsillé en production)
     */
    public function __construct() {
        if (!self::$_called) {
            if (!file_exists("./change.php")) {
                file_put_contents("./change.php", file_get_contents(__DIR__ . "/change_reload/change"));
            }
            echo html_structures::script("../commun/src/js/change_reload.js");
            self::$_called = true;
        }
    }

    public static function clear() {
        if (!self::$_called && file_exists("./change.php")) {
            unlink("./change.php");
        }
    }

}
