<?php

/**
 * Cette classe permet de formater l'affichage d'un nombre dans un INPUT de type text
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class maskNumber {

    /**
     * Liste des INPUT formatés
     * @var array  Liste des INPUT formatés
     */
    private static $_inputs = [];

    /**
     * Formater l'affichage d'un nombre dans un INPUT de type text
     * @param string $name nom de l'input
     * @param boolean $integer true = int, false = float
     * @param string $thousands Symbole séparateur entre milliers, millions, milliards, ... (un espace par defaut)
     * @param string $decimal Symbole pour les décimeaux ( un point par défaut)
     */
    public static function set($name, $integer = false, $thousands = " ", $decimal = ".") {
        $int = ($integer ? "true" : "false");
        $id = strtr($name, ["[" => "_", "]" => ""]);
        echo tags::tag("script", ["type" => "text/javascript"], "$(document).ready(function () {maskNumber(\"{$id}\", {$int}, \"{$thousands}\",\"{$decimal}\");});");
        self::$_inputs[] = ["name" => $name, "thousands" => $thousands, "integer" => $integer, "decimal" => $decimal];
    }

    /**
     * Convertit les nombres formatés en type integer/float dans le tableau $_POST
     */
    public static function get() {
        foreach (self::$_inputs as $input) {
            $exp = explode("[", $input["name"]);
            $key = $exp[0];
            $index = (isset($exp[1]) ? (int) $exp[1] : false);
            if (isset($_POST[$key])) {
                if (is_array($_POST[$key])) {
                    $value = strtr($_POST[$key][$index], [
                        $input["thousands"] => "",
                        $input["decimal"] => ".",
                    ]);
                    $_POST[$key][$index] = ($input["integer"] ? (int) $value : (float) $value);
                } else {
                    $value = strtr($_POST[$key], [
                        $input["thousands"] => "",
                        $input["decimal"] => ".",
                    ]);
                    $_POST[$key] = ($input["integer"] ? (int) $value : (float) $value);
                }
            }
        }
    }

}
