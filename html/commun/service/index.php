<?php

/**
 * Cette classe est la première appelée, elle ouvre les variables de session, <br />
 * redéfinie la time zone et fait appel à ces méthodes privées avant d'appeler la class application (IDEM __construct()...)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class index_service {

    /**
     * Cette classe est la première appelée, elle ouvre les variables de session, <br />
     * redéfinie la time zone et fait appel à ces méthodes privées avant d'appeler la class application (IDEM __construct()...)
     */
    public function __construct() {
        if (isset($_REQUEST["service"])) {
            date_default_timezone_set('Europe/Paris');
            $this->classloader();
            $this->serviceloader();
            $this->security_purge();
            $service = strtr($_REQUEST["service"], array("." => "", "/" => "", "\\" => "", "?" => "", "#" => ""));
            if (file_exists($service . ".service.php")) {
                $service = new $service();
            } else {
                echo json_encode(array("error" => "service not found"));
            }
        } else {
            echo json_encode(array("error" => "undefined service"));
        }
    }

    /**
     * Inclut toutes les classes du dossier "class" se finissant par ".class.php" <br />
     * Vous pouvez créer vos propres classes avec cette extension pour les charger automatiquement avant de les utiliser dans votre application
     */
    private function classloader() {
        spl_autoload_register(function($class) {
            $file = __DIR__ . "/../../../dwf/class/" . $class . ".class.php";
            if (file_exists($file)) {
                require_once $file;
            }
        });
    }

    /**
     * Inclut toutes les classes du dossier "service" se finissant par ".service.php" <br />
     * pour générer les entités voir "class/entity_generator.php"
     */
    private function serviceloader() {
        spl_autoload_register(function($class) {
            $file = __DIR__ . "/" . $class . ".service.php";
            if (file_exists($file)) {
                require_once $file;
            }
        });
    }

    /**
     * Supprime tous les fichiers se terminant par .php~ (trill) dans le dossier "service" <br />
     * Cette fonction est recommandée sur les serveurs de production Linux pour des raisons de sécurité <br />
     * certains hébergeurs tolèrent mal cette fonction, elle peut être désactivée en commentant la ligne "$this->security_purge();" dans le constructeur.
     */
    private function security_purge() {
        foreach (glob("*.php~") as $trill) {
            unlink($trill);
        }
    }

}

new index_service();
