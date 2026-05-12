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
     * @param string $lang Langue de l'API (fr par defaut)
     */
    public function __construct($api_key, $city,$lang="fr") {
        $data = json_decode(file_get_contents("https://api.openweathermap.org/data/2.5/weather?q={$city}&lang={$lang}units=metric&appid={$api_key}"));
        if (stripos($city, ",")) {
            $city = explode(",", $city)[0];
        }
        $_data = [
            "dt" => "Météo de " . ucfirst($city) . " (" . date("d/m H:i", $data->dt) . ")",
            "weather" => $this->get_weater_fr($data->weather[0]->id),
            "temp" => "Température : " . ((int) $data->main->temp) . "°C",
            "pressure" => "Pression : " . ((int) $data->main->pressure) . " Hpa",
            "humidity" => "Humidité : " . ((int) $data->main->humidity) . "%",
            "wind" => "Vent : " . ((int) ($data->wind->speed * 3.6)) . "km/h, " . $this->get_cadinal($data->wind->deg),
        ];
        $span = "";
        foreach ($_data as $key => $value) {
            $span .= tags::tag("span", ["class" => $key], $value) . tags::tag("br");
        }
        echo tags::tag("div", ["class" => "openweather"], tags::tag("p", [], $span));
    }

    /**
     * Retourne l'icone et la traduction du temps
     * @param int $id id du temps
     * @return string Icone et traduction du temps
     */
    private function get_weater_fr($id) {
        $img = "../commun/openweather/";
        $weather = [
            200 => ["img" => "{$img}11d.png", "text" => "Orage avec pluie fine"],
            201 => ["img" => "{$img}11d.png", "text" => "Orage avec pluie"],
            202 => ["img" => "{$img}11d.png", "text" => "Orage avec forte pluie"],
            210 => ["img" => "{$img}11d.png", "text" => "Orage léger"],
            211 => ["img" => "{$img}11d.png", "text" => "Orage"],
            212 => ["img" => "{$img}11d.png", "text" => "Lourd orage"],
            221 => ["img" => "{$img}11d.png", "text" => "Orage déchiqueté"],
            230 => ["img" => "{$img}11d.png", "text" => "Orage avec brouillard léger"],
            231 => ["img" => "{$img}11d.png", "text" => "Orage avec brouillard"],
            232 => ["img" => "{$img}11d.png", "text" => "Orage avec lourd brouillard"],
            300 => ["img" => "{$img}09d.png", "text" => "Brouillard d'intensité légère"],
            301 => ["img" => "{$img}09d.png", "text" => "Brouillard"],
            302 => ["img" => "{$img}09d.png", "text" => "Lourd brouillard"],
            310 => ["img" => "{$img}09d.png", "text" => "Pluie de brouillard légèr"],
            311 => ["img" => "{$img}09d.png", "text" => "Pluie de brouillard"],
            312 => ["img" => "{$img}09d.png", "text" => "Lourde pluie de brouillard"],
            313 => ["img" => "{$img}09d.png", "text" => "Pluie intense et brouillard"],
            314 => ["img" => "{$img}09d.png", "text" => "Lourde pluie intense et brouillard"],
            321 => ["img" => "{$img}09d.png", "text" => "brouillard intense"],
            500 => ["img" => "{$img}10d.png", "text" => "Pluie fine"],
            501 => ["img" => "{$img}10d.png", "text" => "Pluie modérée"],
            502 => ["img" => "{$img}10d.png", "text" => "Lourde pluie"],
            503 => ["img" => "{$img}10d.png", "text" => "Très forte pluie"],
            504 => ["img" => "{$img}10d.png", "text" => "Pluie extrême"],
            511 => ["img" => "{$img}13d.png", "text" => "Pluie verglaçante"],
            520 => ["img" => "{$img}09d.png", "text" => "Pluie intense d'intensité légère"],
            521 => ["img" => "{$img}09d.png", "text" => "Pluie intense"],
            522 => ["img" => "{$img}09d.png", "text" => "Lourde pluie intense"],
            531 => ["img" => "{$img}09d.png", "text" => "Pluie intense déchiquetée"],
            600 => ["img" => "{$img}13d.png", "text" => "Neige légère"],
            601 => ["img" => "{$img}13d.png", "text" => "Neige"],
            602 => ["img" => "{$img}13d.png", "text" => "Neige lourde"],
            611 => ["img" => "{$img}13d.png", "text" => "Neige fondue"],
            612 => ["img" => "{$img}13d.png", "text" => "Neige fondue intense"],
            615 => ["img" => "{$img}13d.png", "text" => "Pluie fine et neige"],
            616 => ["img" => "{$img}13d.png", "text" => "Pluie et neige melées"],
            620 => ["img" => "{$img}13d.png", "text" => "Neige intense légère"],
            621 => ["img" => "{$img}13d.png", "text" => "Neige intense"],
            622 => ["img" => "{$img}13d.png", "text" => "Neige intense lourde"],
            701 => ["img" => "{$img}50d.png", "text" => "Brume"],
            711 => ["img" => "{$img}50d.png", "text" => "Fumée"],
            721 => ["img" => "{$img}50d.png", "text" => "Brume"],
            731 => ["img" => "{$img}50d.png", "text" => "Sable, tourbillons de poussières"],
            741 => ["img" => "{$img}50d.png", "text" => "Brouillard"],
            751 => ["img" => "{$img}50d.png", "text" => "Sable"],
            761 => ["img" => "{$img}50d.png", "text" => "Poussière"],
            762 => ["img" => "{$img}50d.png", "text" => "Cendre volcanique"],
            771 => ["img" => "{$img}50d.png", "text" => "Rafales"],
            781 => ["img" => "{$img}50d.png", "text" => "Tornade"],
            800 => ["img" => "{$img}01d.png", "text" => "Ciel clair"],
            801 => ["img" => "{$img}02d.png", "text" => "Peu de nuages"],
            802 => ["img" => "{$img}03d.png", "text" => "Nuages dispersés"],
            803 => ["img" => "{$img}04d.png", "text" => "Nuages cassés"],
            804 => ["img" => "{$img}04d.png", "text" => "Nuages couverts"],
            900 => ["img" => "{$img}00d.png", "text" => "tornado"],
            901 => ["img" => "{$img}00d.png", "text" => "tropical storm"],
            902 => ["img" => "{$img}00d.png", "text" => "hurricane"],
            903 => ["img" => "{$img}00d.png", "text" => "cold"],
            904 => ["img" => "{$img}00d.png", "text" => "hot"],
            905 => ["img" => "{$img}00d.png", "text" => "windy"],
            906 => ["img" => "{$img}00d.png", "text" => "hail"],
            951 => ["img" => "{$img}00d.png", "text" => "Calme"],
            952 => ["img" => "{$img}00d.png", "text" => "Brise légère"],
            953 => ["img" => "{$img}00d.png", "text" => "Brise douce"],
            954 => ["img" => "{$img}00d.png", "text" => "Brise modérée"],
            955 => ["img" => "{$img}00d.png", "text" => "Brise fraiche"],
            956 => ["img" => "{$img}00d.png", "text" => "Forte brise"],
            957 => ["img" => "{$img}00d.png", "text" => "Vent violent, près de tempête"],
            958 => ["img" => "{$img}00d.png", "text" => "Tempête"],
            959 => ["img" => "{$img}00d.png", "text" => "Tempête sévère"],
            960 => ["img" => "{$img}00d.png", "text" => "Tempête"],
            961 => ["img" => "{$img}00d.png", "text" => "Tempête violente"],
            962 => ["img" => "{$img}00d.png", "text" => "Ouragan"],
        ];
        return tags::tag("span",[], html_structures::img($weather[$id]["img"])).tags::tag("span",[],$weather[$id]["text"]);
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
        return number_format($deg, 1) . " ° (" . $dir . ")";
    }

}
