<?php

/**
 * Affiche un graphe d'analyse et de visualisation (jquery cytoscape)
 * Requiert une règle CSS sur l'ID CSS
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class cytoscape {

    /**
     * Permet de vérifier que la librairie cytoscape a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie cytoscape a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Affiche un graphe d'analyse et de visualisation (jquery cytoscape)
     * Requiert une règle CSS sur l'ID CSS
     * @param string $id ID CSS
     * @param array $data Données du graphe exemple : array("A"=>array("B","C"), "B"=>array("C"), "C"=>array("A"));
     */
    public function __construct($id, $data) {
        if (!self::$_called) {
            echo html_structures::script("../commun/src/js/cytoscape/cytoscape.min.js");
            self::$_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                cytoscape({
                    container: $("#<?= $id; ?>"),
                    elements: [<?= $this->mk_graf($data); ?>],
                    style: [
                        {selector: 'node', css: {'content': 'data(id)', 'width': '25px', 'background-color': 'lightblue'}},
                        {selector: 'edge', css: {'target-arrow-shape': 'triangle', 'line-color': 'lightgray', 'target-arrow-color': 'lightgray', 'curve-style': 'bezier'}}
                    ],
                    layout: {name: 'circle'}
                });
            });
        </script>
        <?php
        echo tags::tag("div", ["id" => $id, "class" => "cytoscape"], "");
    }

    /**
     * Fonction récursive qui formate les données pour le graphe
     * @param array $data Données du graphe exemple : array("A"=>array("B","C"), "B"=>array("C"), "C"=>array("A"));
     * @param string $kp clé parente
     * @return string cytoscape.elements
     */
    private function mk_graf($data, $kp = "") {
        $json = "";
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $json .= "{data: { id: '" . addslashes($key) . "' }}," . $this->mk_graf($value, $key);
            } else {
                if (!is_int($key) == true) {
                    $value = $key . " : " . $value;
                }
                $json .= "{data: { id: '" . addslashes($value) . "' }},";
            }
            if (!empty($kp) and ! is_int($kp)) {
                $json .= "{data: { source: '" . addslashes($kp) . "', target: '" . addslashes(is_array($value) ? $key : $value) . "' }},";
            }
        }
        return $json;
    }

}
