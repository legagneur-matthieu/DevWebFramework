<?php

/**
 * Cette classe converti un texte en flux audio.
 * /!\ Nececite que espeak soit installé sur le serveur !
 * http://espeak.sourceforge.net/
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class espeak {

    private $_espeak = "espeak";
    private $_amplitude = 100;
    private $_wordgap = 0;
    private $_pitch = 50;
    private $_speed = 175;
    private $_voice = "fr";
    private $_variant = "";
    private $_output = "base64";

    /**
     * Cette classe converti un texte en flux audio.
     * /!\ Nececite que espeak soit installé sur le serveur !
     * http://espeak.sourceforge.net/
     * 
     * @param string $espeak commande ou path de espeak
     */
    public function __construct($espeak = "espeak") {
        $this->_espeak = $espeak;
    }

    /**
     * commande ou path de espeak
     * @return string commande ou path de espeak
     */
    public function get_espeak() {
        return $this->_espeak;
    }

    /**
     * commande ou path de espeak
     * @param string $_espeak commande ou path de espeak
     */
    public function set_espeak($_espeak) {
        $this->_espeak = $_espeak;
        return $this;
    }

    /**
     * Amplitude
     * @return int Amplitude
     */
    public function get_amplitude() {
        return $this->_amplitude;
    }

    /**
     * Amplitude
     * @param int $_amplitude Amplitude (entre 0 et 100, 100 par defaut)
     */
    public function set_amplitude($_amplitude) {
        $this->_amplitude = (int) $_amplitude;
        return $this;
    }

    /**
     * Temps entre chaque mots (ms)
     * @return int Temps entre chaque mots (ms) 
     */
    public function get_wordgap() {
        return $this->_wordgap;
    }

    /**
     * Temps entre chaque mots (ms)
     * @param int $_wordgap Temps entre chaque mots (ms) (0 par defaut)
     */
    public function set_wordgap($_wordgap) {
        $this->_wordgap = (int) $_wordgap;
        return $this;
    }

    /**
     * Pitch
     * @return int Pitch (50 par defaut)
     */
    public function get_pitch() {
        return $this->_pitch;
    }

    /**
     * Pitch
     * @param int $_pitch Pitch (entre 0 et 99, 50 par defaut)
     */
    public function set_pitch($_pitch) {
        $this->_pitch = (int) $_pitch;
        return $this;
    }

    /**
     * Mots par minutes
     * @return int Mots par minutes 
     */
    public function get_speed() {
        return $this->_speed;
    }

    /**
     * Mots par minutes
     * @param int $_speed Mots par minutes (175 par defaut)
     */
    public function set_speed($_speed) {
        $this->_speed = (int) $_speed;
        return $this;
    }

    /**
     * Voix
     * @return string Voix 
     */
    public function get_voice() {
        return $this->_voice;
    }

    /**
     * Voix 
     * Pour utiliser les voix mbrola: set_espeak("espeak-ng")
     * @param string $_voice Voix ("fr" par defaut)
     */
    public function set_voice($_voice) {
        if (in_array($_voice, [
                    //lang
                    "af", "am", "an", "ar", "as", "az", "ba", "bg", "bn", "bpy",
                    "bs", "ca", "cmn", "cs", "cy", "da", "de", "el", "en", "en-029", "en-gb",
                    "en-gb-scotland", "en-gb-x-gbclan", "en-gb-x-gbcwmd", "en-gb-x-rp",
                    "en-us", "eo", "es", "es-419", "et", "eu", "fa", "fa-latn", "fi",
                    "fr-be", "fr-ch", "fr-fr", "fr", "ga", "gd", "gn", "grc", "gu", "hak",
                    "hi", "hr", "ht", "hu", "hy", "hyw", "ia", "id", "is", "it", "ja",
                    "jbo", "ka", "kk", "kl", "kn", "ko", "kok", "ku", "ky", "la", "lfn",
                    "lt", "lv", "mi", "mk", "ml", "mr", "ms", "mt", "my", "nb", "nci",
                    "ne", "nl", "om", "or", "pa", "pap", "pl", "pt", "pt-br", "py",
                    "quc", "ro", "ru", "ru-lv", "sd", "shn", "si", "sk", "sl", "sq",
                    "sr", "sv", "sw", "ta", "te", "tn", "tr", "tt", "ur", "uz", "vi",
                    "vi-vn-x-central", "vi-vn-x-south", "yue",
                    //voices
                    "Afrikaans", "afrikaans-mbrola-1", "Amharic", "Aragonese", "arabic-mbrola-1",
                    "arabic-mbrola-2", "Arabic", "Assamese", "Azerbaijani", "Bashkir", "Bulgarian",
                    "Bengali", "Bishnupriya_Manipuri", "Bosnian", "Catalan", "Chinese_(Mandarin)",
                    "czech-mbrola-1", "czech-mbrola-2", "Czech", "Welsh", "Danish", "German",
                    "german-mbrola-1", "german-mbrola-2", "german-mbrola-3", "german-mbrola-4",
                    "german-mbrola-6", "german-mbrola-5", "german-mbrola-7", "german-mbrola-8",
                    "greek-mbrola-2", "Greek", "greek-mbrola-1", "English_(Great_Britain)",
                    "english-mb-en1", "English_(America)", "English_(Scotland)", "English_(Lancaster)",
                    "English_(Received_Pronunciation)", "us-mbrola-2", "us-mbrola-1", "us-mbrola-3",
                    "English_(West_Midlands)", "en-german-1", "en-german-2", "en-german-3",
                    "en-german-4", "en-german-5", "en-german-6", "en-greek", "en-romanian",
                    "English_(Caribbean)", "en-dutch", "en-french", "en-hungarian", "en-swedish-f",
                    "en-afrikaans", "en-polish", "en-swedish", "Storm", "Esperanto", "spanish-mbrola-3",
                    "spanish-mbrola-4", "Spanish_(Spain)", "Spanish_(Latin_America)", "spanish-mbrola-1",
                    "spanish-mbrola-2", "mexican-mbrola-1", "mexican-mbrola-2", "venezuala-mbrola-1",
                    "Estonian", "estonian-mbrola", "Basque", "Persian", "persian-mb-ir1",
                    "Persian_(Pinglish)", "Finnish", "French_(Belgium)", "French_(France)",
                    "french-mbrola-1", "french-mbrola-4", "French_(Switzerland)", "french-mbrola-2",
                    "french-mbrola-3", "french-mbrola-5", "french-mbrola-6", "fr-canadian-mbrola-1",
                    "fr-canadian-mbrola-2", "Gaelic_(Irish)", "Gaelic_(Scottish)", "Guarani",
                    "Greek_(Ancient)", "Gujarati", "Hakka_Chinese", "hindi-mbrola-1", "hindi-mbrola-2",
                    "Hindi", "Croatian", "croatian-mbrola-1", "Haitian_Creole", "Hungarian",
                    "hungarian-mbrola-1", "Armenian_(East_Armenia)", "Armenian_(West_Armenia)",
                    "Interlingua", "Indonesian", "indonesian-mbrola-1", "Icelandic", "mbrola-icelandic",
                    "Italian", "italian-mbrola-3", "italian-mbrola-4", "italian-mbrola-1",
                    "italian-mbrola-2", "japanese-mbrola-1", "japanese-mbrola-2", "japanese-mbrola-3",
                    "Japanese", "Lojban", "Georgian", "Kazakh", "Greenlandic", "Kannada", "Korean",
                    "Konkani", "Kurdish", "Kyrgyz", "Latin", "latin-mbrola-1", "Lingua_Franca_Nova",
                    "Lithuanian", "lithuanian-mbrola-1", "lithuanian-mbrola-2", "Latvian",
                    "Māori", "maori-mbrola-1", "Macedonian", "Malayalam", "Marathi", "malay-mbrola-1",
                    "Malay", "Maltese", "Myanmar_(Burmese)", "Norwegian_Bokmål", "Nahuatl_(Classical)",
                    "Nepali", "Dutch", "dutch-mbrola-2", "dutch-mbrola-1", "dutch-mbrola-3", "Oromo",
                    "Oriya", "Punjabi", "Papiamento", "Polish", "polish-mbrola-1",
                    "Portuguese_(Portugal)", "Portuguese_(Brazil)", "brazil-mbrola-1",
                    "brazil-mbrola-2", "brazil-mbrola-3", "brazil-mbrola-4", "portugal-mbrola-1",
                    "Portuguese_(Brazil)roa/pt-BR(pt6)", "Portuguese_(Portugal)roa/pt(pt-pt5)", "Pyash",
                    "K'iche'", "Romanian", "romanian-mbrola-1", "Russian", "Russian_(Latvia)", "Sindhi",
                    "Shan_(Tai_Yai)", "Sinhala", "Slovak", "Slovenian", "Albanian", "Serbian", "Swedish",
                    "swedish-mbrola-1", "swedish-mbrola-2", "Swahili", "Tamil", "Telugu",
                    "telugu-mbrola-1", "Setswana", "Turkish", "turkish-mbrola-1", "Tatar", "Urdu",
                    "Uzbek", "Vietnamese_(Northern)", "Vietnamese_(Central)", "Vietnamese_(Southern)",
                    "Chinese_(Cantonese)"
                ])) {
            $this->_voice = $_voice;
        }
        return $this;
    }

    /**
     * Variant
     * @return string Variant 
     */
    public function get_variant() {
        return $this->_variant;
    }

    /**
     * Variant
     * @param type $_variant Variant (m1-7,f1-4,croak,whisper)
     */
    public function set_variant($_variant) {
        if (in_array($_variant, [
                    //m
                    "m1", "m2", "m3", "m4", "m5", "m6", "m7",
                    //f
                    "f1", "f2", "f3", "f4",
                    //effect
                    "croak", "croakf", "whisper", "whisperf"
                ])) {
            $this->_variant = $_variant;
        }
        return $this;
    }

    /**
     * Output
     * @return string Output
     */
    public function get_output() {
        return $this->_output;
    }

    /**
     * Output
     * @param string $_output Output (raw ou base64, base64 par defaut)
     * @return espeak
     */
    public function set_output($_output) {
        if (in_array($_output, ["raw", "base64"])) {
            $this->_output = $_output;
        }
        return $this;
    }

    /**
     * Retourne le texte en flux audio
     * @param string $text
     * @return string Flux audio (en base64 ou raw selon l'output, base64 par defaut)
     */
    public function TTS($text) {
        $text = strtr($text, ['"' => '\"', "\x22" => '\"', "%22" => '\"', "&#34;" => '\"', "&quot;" => '\"']);
        $cmd = "$this->_espeak -a $this->_amplitude -g $this->_wordgap -p $this->_pitch -s $this->_speed -v $this->_voice" .
                (empty($this->_variant) ? " " : "+$this->_variant ");
        $hash = sha1($text . $cmd);
        $path = (strpos(strtoupper(PHP_OS), "WIN") ? "%temp%\\" : "/tmp/") . "$hash.wav";
        $cmd .= " -w $path \"$text\"";
        exec($cmd);
        $output = ($this->_output == "base64" ? "data:audio/wav;base64," . base64_encode(file_get_contents($path)) : file_get_contents($path));
        unlink($path);
        return $output;
    }

}
