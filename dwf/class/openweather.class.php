<?php

/**
 * Cette classe permet d'afficher la météo d'une ville en temps réel (utilise openweather et nécéssite une clé API)
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class openweather {

    /**
     * Cette classe permet d'afficher la météo d'une ville en temps réel (utilise openweather et nécéssite une clé API)
     * 
     * @param string $api_key Clé de l'API
     * @param string $city Ville dont on désire la météo
     */
    public function __construct($api_key, $city) {
        $data = json_decode(file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&units=metric&appid=" . $api_key));
        if (stripos($city, ",")) {
            $city = explode(",", $city);
            $city = $city[0];
        }
        $_data = array(
            "dt" => "Météo de " . ucfirst($city) . " (" . date("d/m H:i", $data->dt) . ")",
            "weather" => $this->get_weater_fr($data->weather[0]->id),
            "temp" => "Température : " . ((int) $data->main->temp) . "Â°C",
            "pressure" => "Pression : " . ((int) $data->main->pressure) . " Hpa",
            "humidity" => "Humidité : " . ((int) $data->main->humidity) . "%",
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
            210 => array("img" => $img . "11d.png", "text" => "Orage léger"),
            211 => array("img" => $img . "11d.png", "text" => "Orage"),
            212 => array("img" => $img . "11d.png", "text" => "Lourd orage"),
            221 => array("img" => $img . "11d.png", "text" => "Orage déchiqueté"),
            230 => array("img" => $img . "11d.png", "text" => "Orage avec brouillard léger"),
            231 => array("img" => $img . "11d.png", "text" => "Orage avec brouillard"),
            232 => array("img" => $img . "11d.png", "text" => "Orage avec lourd brouillard"),
            300 => array("img" => $img . "09d.png", "text" => "brouillard d'intensité légère"),
            301 => array("img" => $img . "09d.png", "text" => "brouillard"),
            302 => array("img" => $img . "09d.png", "text" => "Lourde brouillard"),
            310 => array("img" => $img . "09d.png", "text" => "Pluie de brouillard légèr"),
            311 => array("img" => $img . "09d.png", "text" => "Pluie de brouillard"),
            312 => array("img" => $img . "09d.png", "text" => "Lourde pluie de brouillard"),
            313 => array("img" => $img . "09d.png", "text" => "Pluie intence et brouillard"),
            314 => array("img" => $img . "09d.png", "text" => "Lourde pluie intence et brouillard"),
            321 => array("img" => $img . "09d.png", "text" => "brouillard intence"),
            500 => array("img" => $img . "10d.png", "text" => "Pluie fine"),
            501 => array("img" => $img . "10d.png", "text" => "Pluie modérée"),
            502 => array("img" => $img . "10d.png", "text" => "Lourde pluie"),
            503 => array("img" => $img . "10d.png", "text" => "Très forte pluie"),
            504 => array("img" => $img . "10d.png", "text" => "Pluie extrême"),
            511 => array("img" => $img . "13d.png", "text" => "Pluie verglaÃ§ante"),
            520 => array("img" => $img . "09d.png", "text" => "Pluie intence d'intensité légère"),
            521 => array("img" => $img . "09d.png", "text" => "Pluie intence"),
            522 => array("img" => $img . "09d.png", "text" => "Lourde pluie intence"),
            531 => array("img" => $img . "09d.png", "text" => "Pluie intence déchiquetée"),
            600 => array("img" => $img . "13d.png", "text" => "Neige légère"),
            601 => array("img" => $img . "13d.png", "text" => "Neige"),
            602 => array("img" => $img . "13d.png", "text" => "Neige lourde"),
            611 => array("img" => $img . "13d.png", "text" => "Neige fondue"),
            612 => array("img" => $img . "13d.png", "text" => "Neige fondue intence"),
            615 => array("img" => $img . "13d.png", "text" => "Pluie fine et neige"),
            616 => array("img" => $img . "13d.png", "text" => "Pleut et la neige"),
            620 => array("img" => $img . "13d.png", "text" => "Neige intence légère"),
            621 => array("img" => $img . "13d.png", "text" => "Neige intence"),
            622 => array("img" => $img . "13d.png", "text" => "Neige intence lourde"),
            701 => array("img" => $img . "50d.png", "text" => "Brume"),
            711 => array("img" => $img . "50d.png", "text" => "Fumée"),
            721 => array("img" => $img . "50d.png", "text" => "Brume"),
            731 => array("img" => $img . "50d.png", "text" => "Sable, tourbillons de poussière"),
            741 => array("img" => $img . "50d.png", "text" => "Brouillard"),
            751 => array("img" => $img . "50d.png", "text" => "Sable"),
            761 => array("img" => $img . "50d.png", "text" => "Poussière"),
            762 => array("img" => $img . "50d.png", "text" => "Cendre volcanique"),
            771 => array("img" => $img . "50d.png", "text" => "Rafales"),
            781 => array("img" => $img . "50d.png", "text" => "Tornade"),
            800 => array("img" => $img . "01d.png", "text" => "Ciel clair"),
            801 => array("img" => $img . "02d.png", "text" => "Peu de nuages"),
            802 => array("img" => $img . "03d.png", "text" => "Nuages dispersés"),
            803 => array("img" => $img . "04d.png", "text" => "Nuages cassés"),
            804 => array("img" => $img . "04d.png", "text" => "Nuages couverts"),
            900 => array("img" => $img . "00d.png", "text" => "tornado"),
            901 => array("img" => $img . "00d.png", "text" => "tropical storm"),
            902 => array("img" => $img . "00d.png", "text" => "hurricane"),
            903 => array("img" => $img . "00d.png", "text" => "cold"),
            904 => array("img" => $img . "00d.png", "text" => "hot"),
            905 => array("img" => $img . "00d.png", "text" => "windy"),
            906 => array("img" => $img . "00d.png", "text" => "hail"),
            951 => array("img" => $img . "00d.png", "text" => "Calme"),
            952 => array("img" => $img . "00d.png", "text" => "Brise légère"),
            953 => array("img" => $img . "00d.png", "text" => "Brise douce"),
            954 => array("img" => $img . "00d.png", "text" => "Brise modérée"),
            955 => array("img" => $img . "00d.png", "text" => "Brise fraÃ®che"),
            956 => array("img" => $img . "00d.png", "text" => "Forte brise"),
            957 => array("img" => $img . "00d.png", "text" => "Vent violent, près de tempête"),
            958 => array("img" => $img . "00d.png", "text" => "Tempête"),
            959 => array("img" => $img . "00d.png", "text" => "Tempête sévère"),
            960 => array("img" => $img . "00d.png", "text" => "Tempête"),
            961 => array("img" => $img . "00d.png", "text" => "Tempête violente"),
            962 => array("img" => $img . "00d.png", "text" => "Ouragan"),
        );
        return '<span><img src="' . $weather[$id]["img"] . '" alt="" /><span></span>' . $weather[$id]["text"] . '</span>';
    }

    /**
     * Retourne la direction du vent en fonction de l'angle renseigné
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
        return number_format($deg, 1) . "Â° (" . $dir . ")";
    }

}
