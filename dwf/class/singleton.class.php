<?php

/**
 * Cette classe sert de base pour créer des singleton.
 * Utilisez l'heritage :
 * class ma_classe extends singleton{ }
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class singleton {

    /**
     * Instances des classes sigleton
     * @var array Instances des classes sigleton
     */
    public static $_instances = [];

    /**
     * Retourne l'instance de la classe
     * @return object Retourne l'instance de la classe
     */
    public static function get_instance() {
        if (!isset(self::$_instances[$class = get_called_class()])) {
            if (count(func_get_args()) == 0) {
                self::$_instances[$class] = new $class();
            } else {
                (new ReflectionMethod($class, "__construct"))->invokeArgs(self::$_instances[$class] = new $class(), func_get_args());
            }
        }
        return self::$_instances[$class];
    }

}
