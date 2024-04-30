<?php

/**
 * cette classe permet de veriffier qu'une page web respecte les normes W3C
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class w3c_validate {

    /**
     * Retourne le statut de la page passée en paramètre
     * @param string $url url à evaluer
     * @return string statut de la page au format JSON
     */
    public static function url($url) {
        return file_get_contents("https://validator.w3.org/nu/?doc={$url}&out=json");
    }
}
