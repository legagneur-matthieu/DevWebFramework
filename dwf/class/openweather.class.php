<?php

/**
 * Cette classe permet d'afficher la m�t�o d'une ville en temps r�el (utilise openweather et n�c�ssite une cl� API)
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class openweather {

    /**
     * Cette classe permet d'afficher la m�t�o d'une ville en temps r�el (utilise openweather et n�c�ssite une cl� API)
     * 
     * @param string $api_key Cl� de l'API
     * @param string $city Ville dont on d�sire la m�t�o
     */
    public function __construct($api_key, $city) {
        $data = json_decode(file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&units=metric&appid=" . $api_key));
        if (stripos($city, ",")) {
            $city = explode(",", $city);
            $city = $city[0];
        }
        $_data = array(
            "dt" => "M�t�o de " . ucfirst($city) . " (" . date("d/m H:i", $data->dt) . ")",
            "weather" => $this->get_weater_fr($data->weather[0]->id),
            "temp" => "Temp�rature : " . ((int) $data->main->temp) . "°C",
            "pressure" => "Pression : " . ((int) $data->main->pressure) . " Hpa",
            "humidity" => "Humidit� : " . ((int) $data->main->humidity) . "%",
            "wind" => "Vent : " . ((int) ($data->wind->speed * 3.6)) . "km/h, " . $this->get_cadinal($data->wind->deg),
        );
        ?>
        <div class="openweather">
            <p>
                <?php
                foreach ($_data as $key => $value) {
                    echo "<span class=\"" . $key . "\">" . $value . "</span><br />";
                }
                ?>                    
            </p>
        </div>
        <?php
    }

    /**
     * Retourne l'icone et la traduction du temps
     * @param int $id id du temps
     * @return string icone et traduction du temps
     */
    private function get_weater_fr($id) {
        $img = "../commun/openweather/";
        $weather = array(
            200 => array("img" => $img . "11d.png", "text" => "Orage avec pluie fine"),
            201 => array("img" => $img . "11d.png", "text" => "Orage avec pluie"),
            202 => array("img" => $img . "11d.png", "text" => "Orage avec forte pluie"),
            210 => array("img" => $img . "11d.png", "text" => "Orage l�ger"),
            211 => array("img" => $img . "11d.png", "text" => "Orage"),
            212 => array("img" => $img . "11d.png", "text" => "Lourd orage"),
            221 => array("img" => $img . "11d.png", "text" => "Orage d�chiquet�"),
            230 => array("img" => $img . "11d.png", "text" => "Orage avec brouillard l�ger"),
            231 => array("img" => $img . "11d.png", "text" => "Orage avec brouillard"),
            232 => array("img" => $img . "11d.png", "text" => "Orage avec lourd brouillard"),
            300 => array("img" => $img . "09d.png", "text" => "brouillard d'intensit� l�g�re"),
            301 => array("img" => $img . "09d.png", "text" => "brouillard"),
            302 => array("img" => $img . "09d.png", "text" => "Lourde brouillard"),
            310 => array("img" => $img . "09d.png", "text" => "Pluie de brouillard l�g�r"),
            311 => array("img" => $img . "09d.png", "text" => "Pluie de brouillard"),
            312 => array("img" => $img . "09d.png", "text" => "Lourde pluie de brouillard"),
            313 => array("img" => $img . "09d.png", "text" => "Pluie intence et brouillard"),
            314 => array("img" => $img . "09d.png", "text" => "Lourde pluie intence et brouillard"),
            321 => array("img" => $img . "09d.png", "text" => "brouillard intence"),
            500 => array("img" => $img . "10d.png", "text" => "Pluie fine"),
            501 => array("img" => $img . "10d.png", "text" => "Pluie mod�r�e"),
            502 => array("img" => $img . "10d.png", "text" => "Lourde pluie"),
            503 => array("img" => $img . "10d.png", "text" => "Tr�s forte pluie"),
            504 => array("img" => $img . "10d.png", "text" => "Pluie extr�me"),
            511 => array("img" => $img . "13d.png", "text" => "Pluie verglaçante"),
            520 => array("img" => $img . "09d.png", "text" => "Pluie intence d'intensit� l�g�re"),
            521 => array("img" => $img . "09d.png", "text" => "Pluie intence"),
            522 => array("img" => $img . "09d.png", "text" => "Lourde pluie intence"),
            531 => array("img" => $img . "09d.png", "text" => "Pluie intence d�chiquet�e"),
            600 => array("img" => $img . "13d.png", "text" => "Neige l�g�re"),
            601 => array("img" => $img . "13d.png", "text" => "Neige"),
            602 => array("img" => $img . "13d.png", "text" => "Neige lourde"),
            611 => array("img" => $img . "13d.png", "text" => "Neige fondue"),
            612 => array("img" => $img . "13d.png", "text" => "Neige fondue intence"),
            615 => array("img" => $img . "13d.png", "text" => "Pluie fine et neige"),
            616 => array("img" => $img . "13d.png", "text" => "Pleut et la neige"),
            620 => array("img" => $img . "13d.png", "text" => "Neige intence l�g�re"),
            621 => array("img" => $img . "13d.png", "text" => "Neige intence"),
            622 => array("img" => $img . "13d.png", "text" => "Neige intence lourde"),
            701 => array("img" => $img . "50d.png", "text" => "Brume"),
            711 => array("img" => $img . "50d.png", "text" => "Fum�e"),
            721 => array("img" => $img . "50d.png", "text" => "Brume"),
            731 => array("img" => $img . "50d.png", "text" => "Sable, tourbillons de poussi�re"),
            741 => array("img" => $img . "50d.png", "text" => "Brouillard"),
            751 => array("img" => $img . "50d.png", "text" => "Sable"),
            761 => array("img" => $img . "50d.png", "text" => "Poussi�re"),
            762 => array("img" => $img . "50d.png", "text" => "Cendre volcanique"),
            771 => array("img" => $img . "50d.png", "text" => "Rafales"),
            781 => array("img" => $img . "50d.png", "text" => "Tornade"),
            800 => array("img" => $img . "01d.png", "text" => "Ciel clair"),
            801 => array("img" => $img . "02d.png", "text" => "Peu de nuages"),
            802 => array("img" => $img . "03d.png", "text" => "Nuages dispers�s"),
            803 => array("img" => $img . "04d.png", "text" => "Nuages cass�s"),
            804 => array("img" => $img . "04d.png", "text" => "Nuages couverts"),
            900 => array("img" => $img . "00d.png", "text" => "tornado"),
            901 => array("img" => $img . "00d.png", "text" => "tropical storm"),
            902 => array("img" => $img . "00d.png", "text" => "hurricane"),
            903 => array("img" => $img . "00d.png", "text" => "cold"),
            904 => array("img" => $img . "00d.png", "text" => "hot"),
            905 => array("img" => $img . "00d.png", "text" => "windy"),
            906 => array("img" => $img . "00d.png", "text" => "hail"),
            951 => array("img" => $img . "00d.png", "text" => "Calme"),
            952 => array("img" => $img . "00d.png", "text" => "Brise l�g�re"),
            953 => array("img" => $img . "00d.png", "text" => "Brise douce"),
            954 => array("img" => $img . "00d.png", "text" => "Brise mod�r�e"),
            955 => array("img" => $img . "00d.png", "text" => "Brise fraîche"),
            956 => array("img" => $img . "00d.png", "text" => "Forte brise"),
            957 => array("img" => $img . "00d.png", "text" => "Vent violent, pr�s de temp�te"),
            958 => array("img" => $img . "00d.png", "text" => "Temp�te"),
            959 => array("img" => $img . "00d.png", "text" => "Temp�te s�v�re"),
            960 => array("img" => $img . "00d.png", "text" => "Temp�te"),
            961 => array("img" => $img . "00d.png", "text" => "Temp�te violente"),
            962 => array("img" => $img . "00d.png", "text" => "Ouragan"),
        );
        return '<span><img src="' . $weather[$id]["img"] . '" alt="" /><span></span>' . $weather[$id]["text"] . '</span>';
    }

    /**
     * Retourne la direction du vent en fonction de l'angle renseign�
     * @param float $deg angle
     * @return string angle et direction
     */
    private function get_cadinal($deg) {
        if ($deg > 22.5 and $deg <= 67.5) {
            $dir = "Nord-Est";
        } elseif ($deg > 67.5 and $deg <= 112.5) {
            $dir = "Est";
        } elseif ($deg > 112.5 and $deg <= 157.5) {
            $dir = "Sud-Est";
        } elseif ($deg > 157.5 and $deg <= 202.5) {
            $dir = "Sud";
        } elseif ($deg > 202.5 and $deg <= 247.5) {
            $dir = "Sud-Ouest";
        } elseif ($deg > 247.5 and $deg <= 292.5) {
            $dir = "Ouest";
        } elseif ($deg > 292.5 and $deg <= 337.5) {
            $dir = "Nord-Ouest";
        } else {
            $dir = "Nord";
        }
        return number_format($deg, 1) . "° (" . $dir . ")";
    }

}
