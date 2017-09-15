<?php

/**
 * Cette classe permet de générer un faux texte (Lorem ipsum)
 * (https://fr.wikipedia.org/wiki/Faux-texte)
 * Le texte est généré depuis le vocabulaire du texte de Cicero : De finibus. 
 * (http://www.thelatinlibrary.com/cicero/fin1.shtml)
 * http://www.thelatinlibrary.com/cicero/fin.shtml
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class lorem_ipsum {

    /**
     * Génére un Lorem ipsum
     * 
     * @param int $nb_words Nombre de mots à générer
     * @param boolean $first Le texte doit-il commencer par "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
     * @param boolean $full Utiliser uniquement le vocalulaire du Liber Primus (false, par défaut, 2732 mots) 
     * ou le vocalulaire de toute l'oeuvre (true, 10035 mots)"
     * @return string Lorem ipsum
     */
    public static function generate($nb_words, $first = false, $full = false) {
        $words = json_decode(file_get_contents(__DIR__ . "/lorem_ipsum/lorem_ipsum" . ($full ? "_full" : "") . ".json"), true);
        $cwords = count($words);
        $lorem = "";
        $ponct = ". ";
        if ($first) {
            if ($nb_words <= 8) {
                for ($i = 0; $i < $nb_words; $i++) {
                    $lorem .= ($i == 0 ? ucfirst($words[$i]) : $words[$i]) . ($i == 4 ? "," : "") . ($i == 7 ? "." : "") . " ";
                }
                return $lorem;
            } else {
                $lorem .= "Lorem ipsum dolor sit amet, consectetur adipiscing elit. ";
                $nb_words -= 8;
            }
        }
        for ($i = 0; $i < $nb_words; $i++) {
            if ($ponct == ". ") {
                $lorem .= ucfirst($words[rand(0, ($cwords - 1))]) . " ";
                $ponct = " ";
            } elseif ($ponct == ", ") {
                $lorem .= $words[rand(0, ($cwords - 1))] . " ";
                $ponct = " ";
            } else {
                if (rand(0, 10) == 1) {
                    switch (rand(0, 3)) {
                        case 1:
                        case 2:
                            $ponct = ", ";
                            break;
                        case 3:
                            $ponct = ". ";
                            break;
                        default:
                            $ponct = " ";
                            break;
                    }
                }
                $lorem .= $words[rand(0, ($cwords - 1))] . $ponct;
            }
        }
        return strtr($lorem . "_", array(", _" => ".", " _" => "."));
    }

}
