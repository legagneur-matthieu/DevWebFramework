<?php

class website {

    /**
     * Liste des classes metier et classes natives chargé par le framework 
     * @var array Liste des classes metier et classes natives chargé par le framework 
     */
    public static $_class;

    /**
     * point de départ du site web 
     */
    public function __construct() {
        self::$_class[__FILE__] = __CLASS__;
        spl_autoload_register([__CLASS__, 'classloader']);
        require_once "../../dwf/index.php";
        try {
            new index();
        } catch (Exception $e) {
            dwf_exception::print_exception($e);
        }
    }

    /**
     * Inclut toutes les classes du dossier "class" se finissant par ".class.php" 
     * Vous pouvez créer vos propres classes avec cette extension pour les charger automatiquement avant de les utiliser dans votre application 
     */
    private static function classloader($class) {
        $file = __DIR__ . "/class/" . $class . ".class.php";
        if (file_exists($file)) {
            require_once $file;
            self::$_class[$file] = $class;
        }else {
            $file = __DIR__ . "/class/entity/" . $class . ".class.php";
            if (file_exists($file)) {
                require_once $file;
                self::$_class[$file] = $class;
            }
        }
    }

}

new website();
