<?php
include_once 'graphique/phpGraph.class.php';

/**
 * Cette classe permet de créér des graphiques.
 * 
 * @author BERNARD Rodolphe <mr.rodolphe.bernard@gmail.com>
 */
class graphique extends phpGraph {

    /**
     * Permet de vérifier que la librairie flot a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie flot a bien été appelée qu'une fois.
     */
    public static $_flot_called = false;

    /**
     * Parametrage des couleurs dans l'ordre pour les graphiques
     * @var array
     */
    private $_stroke = array("yellow", "red", "cyan", "green", "blue", "darkgray", "gray", "gold", "darkmagenta", "darkred", "darkcyan", "darkgreen", "darkblue");

    /**
     * Paramètres par défaut du graphique.
     * @var array 
     */
    private $_options = array(
        'paddingTop' => 10, // entier
        'type' => 'line', // (string) ligne, barre, camembert, anneau, actions...
        'steps' => 2, // (int) 2 graduations on y-axis are separated by $steps units. "steps" is automatically calculated but we can set the value with integer. No effect on stock and h-stock charts
        'filled' => true, // (bool) pour remplir des lignes, des histogrammes, des disques
        'tooltips' => true, // (bool) pour montrer des indicateurs
        'circles' => true, // (bool) pour montrer des cercles sur le graphique(lignes ou histogrammes).
        'background' => "#ffffff", // (string) Couleur de fond de grille. Ne pas employer la notation courte (#fff) en raison de $this->__genColor();
        'opacity' => '0.45', // (float) Entre 0 et 1. 
        'gradient' => null, // (array) 2 couleurs de gauche à droite
        'titleHeight' => 1, // (int) Taille de titre principal
        'tooltipLegend' => null, // (string ou array) Affichage des textes dans l'indicateur avec la valeur. Chaque texte peut être personnalisé utilisant un tableau. 
        'legends' => null, // (string ou array or bool) Légende générale pour chaque ligne/histogramme/disque.
        'title' => null, // (string) Titre principal.Le titre va être deployé dans un indicateur aussi.
        'radius' => 100, // (int) rayon du camembert
        'diskLegends' => true, // (bool)Pour montrer les légendes autour d'un camembert
        'diskLegendsType' => 'label', // (string) Données, pourcentage ou label pour montrer autour d'un camembert comme légende.
        'diskLegendsLineColor' => 'darkgrey', // (string) Couleur des lignes qui joignent le camembert aux légendes
        'responsive' => true, // (bool) Pour éviter le svg pour être sensible (dimensions fixées)
        'paddingLegendX' => 10, //Nous ajoutons 10 unités dans le viewbox pour montrer la légende de x correctement
        'multi' => true, // Gérer le multi-affichage
        'height' => null, // définit la hauteur
    );

    /**
     * Cette classe permet de créér des graphiques.
     * 
     * @param int $width Largeur du graohique
     * @param int $height Hauteur du graphique
     * @param array $options Paramètres du graphique.
     */
    public function __construct($width = 300, $height = 300, $options = array()) {

        parent::__construct($width, $height, $options);
    }

    /**
     * Affiche un graphique (Librairie flot)
     * @param array $data array( <br />
     * array("label"=> "label1,<br />
     * "data"=> array(<br />
     *      array(x1,y1),<br />
     *      array(x2,y2),<br />
     *      array(x3,y3),<br />
     *      ...<br />
     * ),<br />
     * array("label"=> "label2,<br />
     * "data"=> array(<br />
     *      array(x1,y1),<br />
     *      array(x2,y2),<br />
     *      array(x3,y3),<br />
     *      ...<br />
     * ),<br />
     * ...<br />
     * );<br />
     * 
     * @param array $tricks Tableau de subtitution pour les graduations de l'axe X : array(array(x,"substitution"), ...);
     * @param string $id Id CSS du graphique
     */
    public function line($data, $tricks = array(), $id = "plot") {
        if (!self::$_flot_called) {
            ?> <script type="text/javascript" src="../commun/src/js/flot/jquery.flot.js"></script> <?php
            graphique::$_flot_called = TRUE;
        }
        $lim = array(
            "xmin" => 2147483647,
            "xmax" => -2147483647,
            "ymin" => 2147483647,
            "ymax" => -2147483647
        );
        foreach ($data as $d) {
            foreach ($d["data"] as $value) {
                if ($value[0] > $lim["xmax"]) {
                    $lim["xmax"] = $value[0];
                }
                if ($value[0] < $lim["xmin"]) {
                    $lim["xmin"] = $value[0];
                }
                if ($value[1] > $lim["ymax"]) {
                    $lim["ymax"] = $value[1];
                }
                if ($value[1] < $lim["ymin"]) {
                    $lim["ymin"] = $value[1];
                }
            }
        }
        ?> 
        <script type="text/javascript">
            $(document).ready(function () {
            options = {
            series: {
            lines: {show: true},
                    points: {show: true}
            },
                    xaxis: {
                    show: true,
                            min: <?php echo $lim["xmin"]; ?>,
                            max: <?php echo $lim["xmax"]; ?>,
        <?php
        if (count($tricks) > 0) {
            ?>
                        ticks: <?php echo json_encode($tricks); ?>
            <?php
        }
        ?>
                    },
                    yaxis: {
                    show: true,
                            min: <?php echo $lim["ymin"]; ?>,
                            max: <?php echo $lim["ymax"]; ?>
                    }
            }
            data = <?php echo json_encode($data); ?>;
            $("#<?php echo $id; ?>").plot(data, options);
            });
        </script>
        <div id="<?php echo $id; ?>" class="plot"></div>
        <?php
    }

    /**
     * Affiche un graphique "en camenbert" (Librairie phpgraph)
     * @param array $data Tableau associatif des données : array("label1"=>valeur1,"label2"=>valeur2,...);
     * @param array $options surcharge des options
     */
    public function pie($data, $options = array()) {
        $datas = array();
        foreach ($data as $key => $value) {
            $datas[$key . " : " . $value] = $value;
        }
        foreach ($this->_options as $key => $value) {
            if (!isset($options[$key])) {
                $options[$key] = $value;
            }
        }
        if (!isset($options["stroke"])) {
            $options["stroke"] = $this->_stroke;
        }
        //$options["height"] = max($data);
        $options["type"] = "pie";
        echo $this->draw($datas, $options);
    }

    /**
     * Affiche un graphique "en anneau" (Librairie phpgraph)
     * @param array $data Tableau associatif des données : array("label1"=>valeur1,"label2"=>valeur2,...);
     * @param array $options surcharge des options
     */
    public function ring($data, $options = array()) {
        $datas = array();
        foreach ($data as $key => $value) {
            $datas[$key . " : " . $value] = $value;
        }
        foreach ($this->_options as $key => $value) {
            if (!isset($options[$key])) {
                $options[$key] = $value;
            }
        }
        if (!isset($options["stroke"])) {
            $options["stroke"] = $this->_stroke;
        }
        $options["height"] = max($data);
        $options["type"] = "ring";
        echo $this->draw($datas, $options);
    }

    /**
     * Affiche un graphe d'analyse et de visualisation (jquery cytoscape)
     * Requiert une régle CSS sur l'ID CSS
     * @param string $id ID CSS
     * @param array $data Données du graphe exemple : array("A"=>array("B","C"), "B"=>array("C"), "C"=>array("A"));
     */
    public function cytoscape($id, $data) {
        new cytoscape($id, $data);
    }

}
