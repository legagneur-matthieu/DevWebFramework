<?php

class website {

    /**
     * Liste des classes metier et classes du framework
     * @var array  
     */
    public static $_class;

    /**
     * point de départ du site web
     */
    public function __construct() {
        $this->classloader();
        include_once "../../dwf/index.php";
        try {
            new index();
        } catch (Exception $e) {
            dwf_exception::print_exception($e);
        }
    }

    /**
     * Inclut toutes les classes du dossier "class" se finissant par ".class.php" <br />
     * Vous pouvez créer vos propres classes avec cette extension pour les charger automatiquement avant de les utiliser dans votre application
     */
    private function classloader() {
        foreach (glob(__DIR__ . "/class/*.class.php") as $class) {
            include_once $class;
            self::$_class[] = strtr($class, array(__DIR__ . "/class/" => "", ".class.php" => ""));
        }
        foreach (glob(__DIR__ . "/class/entity/*.class.php") as $class) {
            include_once $class;
        }
    }

}

new website();
