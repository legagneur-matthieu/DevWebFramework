<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Twemoji
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class twemojiFlags {

    /**
     * Permet de vérifier que la librairie Twemoji a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie Twemoji a bien été appelée qu'une fois.
     */
    private static $_called = false;

    public static function get($code) {
        if (!self::$_called) {
            compact_css::get_instance()->add_css_file("../commun/src/css/Twemoji/Twemoji.css");
            self::$_called = true;
        }
        return tags::tag("span", ["class" => "TwemojiCountryFlags", "title" => "$code"],
                        implode('', array_map(
                                        function ($letter) {
                                            return mb_chr(ord($letter) % 32 + 0x1F1E5);
                                        }, str_split($code)
                                )
                        )
        );
    }

}
