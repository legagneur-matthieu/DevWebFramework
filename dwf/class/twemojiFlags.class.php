<?php

/**
 * Cette classe permet d'obtenir un émoji drapeau correspondant au code pays renseigné
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class twemojiFlags {

    /**
     * Permet de vérifier que la librairie Twemoji a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie Twemoji a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Retourne un émoji drapeau correspondant au code pays renseigné
     * @param string $code code pays ("FR", "EN", "ES", ...)
     * @return string Emoji drapeau corespondant
     */
    public static function get($code) {
        if (!self::$_called) {
            compact_css::get_instance()->add_css_file("../commun/src/css/Twemoji/Twemoji.css");
            export_dwf::add_files([realpath("../commun/src/css/Twemoji")]);
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
