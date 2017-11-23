<?php

class index_service { /** * Cette classe est la première appelée, elle ouvre les variables de session et la connexion a la base de donnée, <br /> * redéfinie la time zone et fait appel à ces méthodes privées avant d'appeler la class application (IDEM __construct()...) */

    public function __construct() {
        try {
            $this->classloader();
            include "../class/config.class.php";
            if (isset($_REQUEST["service"])) {
                date_default_timezone_set("Europe/Paris");
                $this->entityloader();
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
            dwf_exception::print_exception($e, "", true);
        }
    }

    /**     * Inclut toutes les classes du framework */
    private function classloader() {
        spl_autoload_register(function($class) {
            $file = __DIR__ . "/../../../dwf/class/" . $class . ".class.php";
            if (file_exists($file)) {
                require_once $file;
            }
        });
    }

    /**     * Inclut les entités du projet */
    private function entityloader() {
        spl_autoload_register(function($class) {
            $file = __DIR__ . "/../class/entity/" . $class . ".class.php";
            if (file_exists($file)) {
                require_once $file;
            }
        });
    }

    /**     * Inclut toutes les classes du dossier "service" se finissant par ".service.php" */
    private function serviceloader() {
        spl_autoload_register(function($class) {
            $file = __DIR__ . "/" . $class . ".service.php";
            if (file_exists($file)) {
                require_once $file;
            }
        });
    }

    /**     * Supprime tous les fichiers se terminant par .php~ (trill) dans le dossier "service" <br /> * Cette fonction est recommandée sur les serveurs de production Linux pour des raisons de sécurité <br /> * certains hébergeurs tolèrent mal cette fonction, elle peut être désactivée en commentant la ligne "$this->security_purge();" dans le constructeur. */
    private function security_purge() {
        foreach (glob("*.php~") as $trill) {
            unlink($trill);
        }
    }

}

new index_service();
