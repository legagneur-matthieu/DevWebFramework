<?php

/**
 * Cette classe permet de créer des évènements (listener et emiter)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class event extends singleton {

    /**
     * Liste des listener et des actions
     * @var array Liste des listener et des actions
     */
    private $_events = [];

    /**
     * Liste des listener et des actions à exécuter qu'une fois
     * @var array Liste des listener et des actions à exécuter qu'une fois
     */
    private $_events_once = [];

    /**
     * Ajoute une action à un listener (créé le listener s'il n'existe pas)
     * @param string $event_name nom du listener
     * @param callable $callable action à ajouter au listener
     */
    public static function on($event_name, callable $callable) {
        $obj = self::get_instance();
        if (!isset($obj->_events[$event_name])) {
            $obj->_events[$event_name] = [];
        }
        $obj->_events[$event_name][] = $callable;
    }

    /**
     * Ajoute une action à exécuter qu'une fois à un listener (créé le listener s'il n'existe pas)
     * @param string $event_name nom du listener
     * @param callable $callable action à ajouter au listener
     */
    public static function once($event_name, callable $callable) {
        $obj = self::get_instance();
        if (!isset($obj->_events_once[$event_name])) {
            $obj->_events_once[$event_name] = [];
        }
        $obj->_events_once[$event_name][] = $callable;
    }

    /**
     * Supprime tout les listener ou un listener en particulier
     * @param boolean|string $event_name nom du listener
     */
    public static function del($event_name = false) {
        $obj = self::get_instance();
        if ($event_name) {
            if (isset($obj->_events[$event_name])) {
                unset($obj->_events[$event_name]);
            }
            if (isset($obj->_events_once[$event_name])) {
                unset($obj->_events_once[$event_name]);
            }
        } else {
            $obj->_events = [];
            $obj->_events_once = [];
        }
    }

    /**
     * Déclenche les actions d'un listener
     * @param boolean|string $event_name nom du listener
     * @param mixed $parameter paramètres pour le listener
     */
    public static function run($event_name, $parameter = null) {
        $obj = self::get_instance();
        if (isset($obj->_events_once[$event_name])) {
            foreach ($obj->_events_once[$event_name] as $value) {
                call_user_func($value, $parameter);
                unset($obj->_events_once[$event_name]);
            }
        }
        if (isset($obj->_events[$event_name])) {
            foreach ($obj->_events[$event_name] as $value) {
                call_user_func($value, $parameter);
            }
        }
    }

}
