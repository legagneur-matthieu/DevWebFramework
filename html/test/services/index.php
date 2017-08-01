<?php

class index_service { /** * Cette classe est la première appelée, elle ouvre les variables de session et la connexion a la base de donnée, <br /> * redéfinie la time zone et fait appel à ces méthodes privées avant d'appeler la class application (IDEM __construct()...) */

    public function __construct() {
        try {
            if (isset($_REQUEST["service"])) {
                date_default_timezone_set("Europe/Paris");
                $this->classloader();
                $this->entityloader();
                include "../class/config.class.php";
                application::$_bdd = new bdd();
                session::start(false);
                $this->serviceloader();
                $this->security_purge();
                $service = strtr($_REQUEST["service"], array("." => "", "/" => "", "\\" => "", "?" => "", "#" => ""));
                if (file_exists($service . ".service.php")) {
                    $service = new $service();
                } else {
                    dwf_exception::throw_exception(622, array("_s_" => $service));
                }
            } else {
                dwf_exception::throw_exception(621);
            }
        } catch (Exception $e) {
            dwf_exception::print_exception($e);
        }
    }

    /**     * Inclut toutes les classes du framework */
    private function classloader() {
        foreach (glob("../../../dwf/class/*.class.php") as $class) {
            include_once $class;
        }
    }

    /**     * Inclut les entités du projet */
    private function entityloader() {
        foreach (glob("../class/entity/*.class.php") as $class) {
            include_once $class;
        }
    }

    /**     * Inclut toutes les classes du dossier "service" se finissant par ".service.php" */
    private function serviceloader() {
        foreach (glob("*.service.php") as $class) {
            include_once $class;
        }
    }

    /**     * Supprime tous les fichiers se terminant par .php~ (trill) dans le dossier "service" <br /> * Cette fonction est recommandée sur les serveurs de production Linux pour des raisons de sécurité <br /> * certains hébergeurs tolèrent mal cette fonction, elle peut être désactivée en commentant la ligne "$this->security_purge();" dans le constructeur. */
    private function security_purge() {
        foreach (glob("*.php~") as $trill) {
            unlink($trill);
        }
    }

}

new index_service();
