<?php

/**
 * Cette classe est la premi�re appel�e, elle ouvre les variables de session, <br />
 * red�finit la time zone et fait appel � ces m�thodes priv�es avant d'appeler la classe application (IDEM __construct()...)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class index {

    /**
     * Cette classe est la premi�re appel�e, elle ouvre les variables de session, <br />
     * red�finit la time zone et fait appel � ces m�thodes priv�es avant d'appeler la class application (IDEM __construct()...)
     */
    public function __construct() {
        ini_set("user_agent", "PHP (".phpversion()."; DevWebFramwork)");
        date_default_timezone_set('Europe/Paris');
        $this->classloader();
        session::start();
        $this->security_purge();
        $app = new application();
    }

    /**
     * Inclut toutes les classes du dossier "class" se finissant par ".class.php". <br />
     * Vous pouvez cr�er vos propres classes avec cette extension pour les charger automatiquement avant de les utiliser dans votre application. <br />
     * Toutes les fonction statiques "onload()" sont appel�es.
     */
    private function classloader() {
        foreach (glob(__DIR__ . "/class/*.class.php") as $class) {
            include_once $class;
            website::$_class[] = strtr($class, array(__DIR__ . "/class/" => "", ".class.php" => ""));
        }
        application::event("onload");
    }

    /**
     * Supprime tous les fichiers se terminant par .php~ (trill) dans le dossier "class". <br />
     * Cette fonction est recommand�e sur les serveurs de production Linux pour des raisons de s�curit�. <br />
     * Certains h�bergeurs tol�rent mal cette fonction, elle peut �tre d�sactiv�e en commentant la ligne "$this->security_purge();" dans le constructeur.
     */
    private function security_purge() {
        foreach (glob(__DIR__ . "/class/*.php~") as $trill) {
            unlink($trill);
        }
    }

}
