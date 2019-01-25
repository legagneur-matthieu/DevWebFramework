<?php

/**
 * Cette classe permet d'afficher une carte exploitant OSM (OpenStreetMap)
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class leaflet {

    /**
     * Permet de vérifier que la librairie leaflet a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie leaflet a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Tableau indiqant la vue initiale de la carte : array("x" => 0, "y" => 0, "zoom" => 13)
     * @var array Tableau indiquant la vue initiale de la carte : array("x" => 0, "y" => 0, "zoom" => 13)
     */
    private $_view_init;

    /**
     * Identifiant CSS
     * @var string Identifiant CSS
     */
    private $_id;

    /**
     * Hauteur CSS 
     * @var string hauteur CSS 
     */
    private $_height;

    /**
     * Tableau contenant les marqueurs
     * @var array Tableau contenant les marqueurs
     */
    private $_markers = [];

    /**
     * Tableau contenant les zones circulaires
     * @var array Tableau contenant les zones circulaires
     */
    private $_circles = [];

    /**
     * Tableau contenant les polygones
     * @var array  Tableau contenant les polygones
     */
    private $_polygon = [];

    /**
     * Cette classe permet d'afficher une carte exploitant OSM (OpenStreetMap) !
     * @param array $view_init Tableau indiqant la vue initiale de la carte (x -> latitude , y -> longitude) : array("x" => 0, "y" => 0, "zoom" => 13)
     * @param string $id Identifiant CSS
     * @param string $height hauteur CSS 
     */
    public function __construct($view_init = ["x" => 0, "y" => 0, "zoom" => 13], $id = "leaflet", $height = "300px") {
        $this->_view_init = $view_init;
        $this->_id = $id;
        $this->_height = $height;
        if (!self::$_called) {
            compact_css::get_instance()->add_css_file("../commun/src/js/leaflet/leaflet.css");
            compact_css::get_instance()->add_css_file("../commun/src/js/leaflet/leaflet-routing/leaflet-routing-machine.css");
            echo html_structures::script("../commun/src/js/leaflet/leaflet.js") .
            html_structures::script("../commun/src/js/leaflet/leaflet-routing/leaflet-routing-machine.js");
            self::$_called = true;
        }
    }

    /**
     * Retourne la liste des markers
     * @return array liste des markers
     */
    public function get_markers() {
        return $this->_markers;
    }

    /**
     * Ajoute un marqueur sur la carte
     * @param float $x coordoné X (latitide) du marqueur
     * @param float $y coordoné Y (longitude) du marqueur
     * @param string $desc Description HTML du marqueur (s'affiche en "popup")
     * @param boolean $first Premier marqueur de l'itinéraire, à préciser si $this->optimise_itineraire() est utilisé
     */
    public function add_marker($x, $y, $desc, $first = false) {
        $this->_markers[] = ["x" => $x, "y" => $y, "desc" => $desc, "first" => $first];
    }

    /**
     * Ajoute une zone circulaire sur la carte
     * @param float $x Coordonnées X du centre du cercle
     * @param float $y Coordonnées Y du centre du cercle
     * @param int $rayon Rayon du cercle ( en métre )
     * @param string $desc Description HTML du cercle (s'affiche en "popup")
     * @param string $color couleur du cercle (couleur CSS)
     * @param float $opacity opacité du cercle ( entre 0 et 1 )
     */
    public function add_circle($x, $y, $rayon, $desc, $color = "lightblue", $opacity = "0.4") {
        $this->_circles[] = ["x" => $x, "y" => $y, "r" => $rayon, "desc" => $desc, "color" => $color, "opacity" => $opacity];
    }

    /**
     * Ajoute un polygone à partir d'un tableau de coordonnées
     * @param array $data tableau de coordonnées à 2 dimensions (associatif) : array( array("x"=>coordX,"y"=>coordY), array("x"=>coordX,"y"=>coordY))
     * @param string $desc
     */
    public function add_polygon($data, $desc) {
        $this->_polygon[] = ["data" => $data, "desc" => $desc];
    }

    /**
     * Optimise l'ordre des markers destinés à être affichés dans un itineraire 
     * A utiliser avant $this->print_map et $this->tracer_itineraire
     */
    public function optimise_itineraire() {
        //on copie les données dans un tableau temporaire
        $data_copy = $this->_markers;
        $data_distances = [];

        //on créé un tableau qui contient toutes les combinaisons de traget et les distances entre chaque points
        foreach ($this->_markers as $key => $a) {
            if ($a["first"]) {
                $first = $key;
            }
            unset($data_copy[$key]);
            foreach ($data_copy as $k => $b) {
                $data_distances[$k][$key] = $data_distances[$key][$k] = $d = math::distance_entre_deux_points($a["x"], $a["y"], $b["x"], $b["y"]);
            }
        }

        //on recupere le premier point
        $points = array_keys($data_distances);
        $path[] = $k = (isset($first) ? $points[$first] : $points[0]);
        // on construit le trajet le plus optimisé en prenant à chaque fois le trajet le plus petit entre deux points,
        //  on supprime le point précédent pour ne pas y retourner
        for ($i = 1; $i < count($points); $i++) {
            $kt = array_keys($data_distances[$k], min($data_distances[$k]));
            unset($data_distances[$k]);
            foreach ($data_distances as $key => $value) {
                unset($data_distances[$key][$k]);
            }
            $path[] = $k = $kt[0];
        }
        $markers = [];
        foreach ($path as $p) {
            $markers[] = $this->_markers[$p];
        }
        $this->_markers = $markers;
    }

    /**
     * Trace un itineraire à partir des markers
     * attention : fonction à appeler après this->print_map !
     * bug : le tracé est en conflit avec les bindPopup des markers
     * @param boolean $add_client_marker ajouter un marqueur sur la géolocalisation du client (false par defaut)
     */
    public function tracer_itineraire($add_client_marker = false) {
        if ($add_client_marker) {
            ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    navigator.geolocation.getCurrentPosition(function (pos) {
                    L.marker([pos.coords.latitude, pos.coords.longitude]).bindPopup("Vous êtes ici").addTo(map<?= $this->_id; ?>);
                            L.Routing.control({
                            waypoints: [
                                    L.Routing.waypoint(L.latLng(pos.coords.latitude, pos.coords.longitude), "Vous êtes ici"), <?php
            foreach ($this->_markers as $markers) {
                ?>
                                L.Routing.waypoint(L.latLng(<?= $markers["x"]; ?>, <?= $markers["y"]; ?>), "<?= $markers["desc"]; ?>"), <?php
            }
            ?>
                            ],
                                    Formatter: [
                                            language = "fr"
                                    ]
                            }).addTo(map<?= $this->_id; ?>);
                    });
                    }
                    );</script>
            <?php
        } else {
            ?>
            <script type="text/javascript">
                    $(document).ready(function () {
                        L.Routing.control({
                        waypoints: [
            <?php
            foreach ($this->_markers as $markers) {
                ?>
                            L.Routing.waypoint(L.latLng(<?= $markers["x"]; ?>, <?= $markers["y"]; ?>), "<?= $markers["desc"]; ?>"), <?php
            }
            ?>
                        ],
                        Formatter: [
                            language = "fr"
                        ]
                    }).addTo(map<?= $this->_id; ?>);
                });</script>
            <?php
        }
    }

    /**
     * Affiche la carte (les marqueurs, cercles et polygones doivent être ajoutés AVANT l'appel de cette fonction )
     */
    public function print_map() {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                map<?= $this->_id; ?> = L.map('<?= $this->_id; ?>').setView([<?= $this->_view_init["x"]; ?>, <?= $this->_view_init["y"]; ?>], <?= $this->_view_init["zoom"]; ?>);
                L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: ''}).addTo(map<?= $this->_id; ?>);
        <?php
        foreach ($this->_markers as $value) {
            ?>
                    L.marker([<?= $value["x"]; ?>,<?= $value["y"]; ?>]).bindPopup("<?= $value["desc"]; ?>").addTo(map<?= $this->_id; ?>);
            <?php
        }
        foreach ($this->_circles as $value) {
            ?>
                    L.circle([<?= $value["x"]; ?>,<?= $value["y"]; ?>], <?= $value["r"]; ?>, {color: '<?= $value["color"]; ?>', fillColor: '<?= $value["color"]; ?>', fillOpacity: <?= $value["opacity"]; ?>}).bindPopup("<?= $value["desc"]; ?>").addTo(map<?= $this->_id; ?>);
            <?php
        }
        foreach ($this->_polygon as $value) {
            ?>
                    L.polygon([<?php
            $poly = "";
            foreach ($value["data"] as $v) {
                $poly .= "[" . $v["x"] . "," . $v["y"] . "],";
            }
            $poly .= "___";
            echo strtr($poly, [",___" => ""]);
            ?>]).bindPopup("<?= $value["desc"]; ?>").addTo(map<?= $this->_id; ?>);
            <?php
        }
        ?>
            });
        </script>
        <?php
        echo tags::tag("div", ["id" => $this->_id, "style" => "height: " . $this->_height], "");
    }

}
