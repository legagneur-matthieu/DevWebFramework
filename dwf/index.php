<?php

/**
 * Cette classe est la première appelée, elle ouvre les variables de session, <br />
 * redéfinit la time zone et fait appel à ces méthodes privées avant d'appeler la classe application (IDEM __construct()...)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class index {

    /**
     * Cette classe est la première appelée, elle ouvre les variables de session, <br />
     * redéfinit la time zone et fait appel à ces méthodes privées avant d'appeler la class application (IDEM __construct()...)
     */
    public function __construct() {
        website::$_class[__FILE__] = __CLASS__;
        ini_set("user_agent", "PHP (" . phpversion() . "; DevWebFramwork)");
        date_default_timezone_set('Europe/Paris');
        spl_autoload_register([__CLASS__, 'classloader']);
        time::chronometer_start("debug_exec");
        session::start();
        $this->security_purge();
        $app = new application();
    }

    /**
     * Inclut toutes les classes du dossier "class" se finissant par ".class.php". <br />
     * Vous pouvez créer vos propres classes avec cette extension pour les charger automatiquement avant de les utiliser dans votre application. <br />
     * Toutes les fonction statiques "onload()" sont appelées.
     */
    private static function classloader($class) {
        $file = __DIR__ . "/class/" . $class . ".class.php";
        if (file_exists($file)) {
            require_once $file;
            website::$_class[$file] = $class;
        }
    }

    /**
     * Supprime tous les fichiers se terminant par .php~ (trill) dans le dossier "class". <br />
     * Cette fonction est recommandée sur les serveurs de production Linux pour des raisons de sécurité. <br />
     * Certains hébergeurs tolèrent mal cette fonction, elle peut être désactivée en commentant la ligne "$this->security_purge();
      " dans le constructeur.
     */
    private function security_purge() {
        foreach (glob(__DIR__ . "/class/*.php~") as $trill) {
            unlink($trill);
        }
    }

}
