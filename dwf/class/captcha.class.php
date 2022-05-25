<?php

/**
 * Cette classe génére un captcha,
 * Utilise la classe espeak pour l'accessibilité,
 * requère donc espeak installé sur le serveur
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class captcha {

    private static $_called = false;

    /**
     * Cette classe génére un captcha,
     * Utiliser la classe espeak pour l'accessibilité,
     * requère donc espeak installé sur le serveur
     * @var \Gregwar\Captcha\CaptchaBuilder
     */
    private $_captcha;

    public function __construct() {
        if (!self::$_called) {
            include_once __DIR__ . "/Gregwar/Captcha/PhraseBuilderInterface.php";
            include_once __DIR__ . "/Gregwar/Captcha/PhraseBuilder.php";
            include_once __DIR__ . "/Gregwar/Captcha/CaptchaBuilderInterface.php";
            include_once __DIR__ . "/Gregwar/Captcha/CaptchaBuilder.php";
            self::$_called = true;
        }
        $this->_captcha = new \Gregwar\Captcha\CaptchaBuilder();
    }

    /**
     * Retourne les données du captcha
     * @param espeak $espeak
     * @return array données du captcha
     */
    public function get($espeak = false) {
        $audio = "";
        foreach (str_split($this->_captcha->getPhrase()) as $leter) {
            if (ord($leter) <= 57) {
                $audio .= "$leter. ";
            } elseif (ord($leter) <= 90) {
                if ($leter == "Y") {
                    $leter = "I Grec";
                }
                $audio .= "$leter majuscule. ";
            } else {
                if ($leter == "y") {
                    $leter = "I Grec";
                }
                $audio .= ucwords($leter) . " minuscule. ";
            }
        }
        return [
            "phrase" => $this->_captcha->getPhrase(),
            "hash" => (sha1($this->_captcha->getPhrase())),
            "img" => ($this->_captcha->build()->inline()),
            "audio" => ($espeak ? $espeak->TTS($audio) : (new espeak())->TTS($audio))
        ];
    }

    /**
     * Verifie si le hash et la phrase corespondent (sha1)
     * @param string $hash Hash du captcha
     * @param string $phrase Phrase du captcha
     * @return boolean 
     */
    public static function check($hash, $phrase) {
        return $hash == sha1($phrase);
    }

}
