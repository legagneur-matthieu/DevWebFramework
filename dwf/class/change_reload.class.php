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
        if (!change_reload::$_called) {
            opcache_reset();
            $rs_ft = 0;
            foreach (glob("class/*.class.php") as $value) {
                $ft = filemtime($value);
                $rs_ft = ($ft > $rs_ft ? $ft : $rs_ft);
            }
            echo tags::tag("span", ["class" => "d-none", "id" => "DWF_Change"], $rs_ft) .
            html_structures::script("../commun/src/js/change_reload.js");
            change_reload::$_called = true;
        }
    }

}
