<?php

/**
 * Cette classe permet de formater l'affichage d'un nombre dans un INPUTde type text
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class maskNumber {

    /**
     * Liste des INPUT formaté
     * @var array  Liste des INPUT formaté
     */
    private static $_inputs = [];

    /**
     * Formater l'affichage d'un nombre dans un INPUT de type text
     * @param string $name ID de l'input
     * @param boolean $integer true = int, false = float
     * @param string $thousands Symbole separateur entre milliers, millions, milliards, ... (un espace par defaut)
     * @param string $decimal Symbole pour les decimeaux ( un point par defaut)
     */
    public static function set($name, $integer = false, $thousands = " ", $decimal = ".") {
        $int = ($integer ? "true" : "false");
        echo tags::tag("script", ["type" => "text/javascript"], "$(document).ready(function () {maskNumber(\"{$name}\", {$int}, \"{$thousands}\",\"{$decimal}\");});");
        self::$_inputs[] = ["name" => $name, "thousands" => $thousands, "integer" => $integer, "decimal" => $decimal];
    }

    /**
     * Convertis les nombres formaté en type integer/float dans le tableau $_POST
     */
    public static function get() {
        foreach (self::$_inputs as $input) {
            if (isset($_POST[$input["name"]])) {
                $value = strtr($_POST[$input["name"]], [
                    $input["thousands"] => "",
                    $input["decimal"] => ".",
                ]);
                $_POST[$input["name"]] = ($input["integer"] ? (int) $value : (float) $value);
            }
        }
    }

}
