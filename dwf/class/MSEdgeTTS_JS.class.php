<?php

/**
 * Cette class est une integration JS de MSEdgeTTS
 * Attention, conformément aux CGU de Microsoft, l'utilisation de cette classe n'est pas autorisé dans le cadre d'un usage commercial
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class MSEdgeTTS_JS extends singleton {

    /**
     * Permet de vérifier que le fichier MSEdgeTTS.js a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que le fichier MSEdgeTTS.js a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette class est une integration JS de MSEdgeTTS
     * Attention, conformément aux CGU de Microsoft, l'utilisation de cette classe n'est pas autorisé dans le cadre d'un usage commercial
     */
    public function __construct() {
        if (!self::$_called) {
            self::$_called = true;
            echo html_structures::script("../commun/src/js/MSEdgeTTS.js") .
            tags::tag("audio", ["id" => "MSEdgeTTS_audio", "src" => ""]);
        }
    }

    /**
     * Retourne un bouton TTS
     * @param string $text texte a lire
     * @param string $voice Voix TTS disponine dans les constantes MSEdgeTTS.
     * @param int $rate Le taux de parole (par défaut : 0, entre -50 et +50).
     * @param int $pitch La hauteur de la voix (par défaut : 0, entre -50 et +50).
     * @return string Bouton TTS
     */
    public static function TTS($text, $voice = MSEdgeTTS::VOICE_FR_FR_DENISE, $rate = 0, $pitch = 0) {
        self::get_instance();
        return tags::tag("a", [
                    "data-tts" => "{'text':'$text','voice':'$voice','rate':$rate,'pitch':$pitch}",
                    "class" => "btn btn-light btn-sm"
                        ], html_structures::bi("volume-up", "Lire"));
    }
}
