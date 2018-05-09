<?php

/**
 * Cette classe permet d'afficher un graphique 
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class graphique {

    /**
     * Permet de vérifier que la librairie flot a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie flot a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Id CSS
     * @var string id CSS
     */
    private $_id;

    /**
     * Cette classe permet d'afficher un graphique 
     * @param string $id Id CSS du graphique     * 
     * @param array $size dimentions du graphique (par defaut : array("width"=>"600px","height"=>"300px")) 
     */
    public function __construct($id, $size = ["width" => "600px", "height" => "300px"]) {
        if (!self::$_called) {
            ?>
            <script type="text/javascript" src="../commun/src/js/flot/jquery.flot.min.js"></script>
            <script type="text/javascript" src="../commun/src/js/flot/jquery.flot.pie.min.js"></script>
            <script type="text/javascript" src="../commun/src/js/flot/jquery.flot.resize.min.js"></script>
            <script type="text/javascript">
                function labelFormatter(label, series) {
                    return "<p style='text-align:center; color:black; text-shadow:0 0 15px white'>" + label + " <br /> " + series.data[0][1] + " (" + Math.round(series.percent) + " %)</p>";
                }
            </script>
            <?php
            self::$_called = true;
        }
        ?>
        <div id="<?= $this->_id = $id ?>" style="width: <?= $size["width"] ?>;height:<?= $size["height"] ?>"></div>
        <?php
    }

    /**
     * Affiche un graphique par points
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
     * @param array $tricks Tableau de subtitution pour les graduations de l'axe X : array(array(x,"substitution"), ...);
     * @param boolean $show_points afficher les points sur le graphique ? (true/false, true par defaut)
     */
    public function points($data, $ticks = []) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $this->_id ?>").plot(<?= json_encode($data) ?>, {
                    series: {points: {show: true}},
                    grid: {hoverable: true, clickable: true},
                    xaxis: {show: true, <?= (count($ticks) > 0 ? "ticks: " . json_encode($ticks) : ""); ?>},
                    yaxis: {show: true}
                });
                $("<div id='<?= $this->_id ?>_tooltip'></div>").css({position: "absolute", display: "none", border: "1px solid black", padding: "2px", "background-color": "white", opacity: 0.80}).appendTo("body");
                $("#<?= $this->_id ?>").bind("plothover", function (event, pos, item) {
                    if (item) {
                        $("#<?= $this->_id ?>_tooltip").html("x : " + item.datapoint[0].toFixed(2) + ", y : " + item.datapoint[1].toFixed(2)).css({top: item.pageY + 5, left: item.pageX + 5}).fadeIn(200);
                    } else {
                        $("#<?= $this->_id ?>_tooltip").hide();
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Affiche un graphique lineaire
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
     * @param array $ticks Tableau de subtitution pour les graduations de l'axe X : array(array(x,"substitution"), ...);
     * @param boolean $show_points Afficher les points sur le graphique ? (true/false, true par defaut)
     * @param boolean $fill La zone entre la ligne et l'abscisse doit-il être coloré  ? (true/false, false par defaut)
     */
    public function line($data, $ticks = [], $show_points = true, $fill = false) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $this->_id ?>").plot(<?= json_encode($data) ?>, {
                    series: {lines: {show: true<?= ($fill ? ", fill: true" : "") ?>}, <?= ($show_points ? "points: {show: true}," : "") ?>},
                    grid: {hoverable: true, clickable: true},
                    xaxis: {show: true, <?= (count($ticks) > 0 ? "ticks: " . json_encode($ticks) : ""); ?>},
                    yaxis: {show: true}
                });
                $("<div id='<?= $this->_id ?>_tooltip'></div>").css({position: "absolute", display: "none", border: "1px solid black", padding: "2px", "background-color": "white", opacity: 0.80}).appendTo("body");
                $("#<?= $this->_id ?>").bind("plothover", function (event, pos, item) {
                    if (item) {
                        $("#<?= $this->_id ?>_tooltip").html("x : " + item.datapoint[0].toFixed(2) + ", y : " + item.datapoint[1].toFixed(2)).css({top: item.pageY + 5, left: item.pageX + 5}).fadeIn(200);
                    } else {
                        $("#<?= $this->_id ?>_tooltip").hide();
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Affiche un graphique en bares
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
     * @param array $tricks Tableau de subtitution pour les graduations de l'axe X : array(array(x,"substitution"), ...);
     */
    public function bars($data, $ticks = []) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $this->_id ?>").plot(<?= json_encode($data) ?>, {
                    series: {bars: {show: true}},
                    grid: {hoverable: true, clickable: true},
                    xaxis: {show: true, <?= (count($ticks) > 0 ? "ticks: " . json_encode($ticks) : ""); ?>},
                    yaxis: {show: true}
                });
                $("<div id='<?= $this->_id ?>_tooltip'></div>").css({position: "absolute", display: "none", border: "1px solid black", padding: "2px", "background-color": "white", opacity: 0.80}).appendTo("body");
                $("#<?= $this->_id ?>").bind("plothover", function (event, pos, item) {
                    if (item) {
                        $("#<?= $this->_id ?>_tooltip").html("x : " + item.datapoint[0].toFixed(2) + ", y : " + item.datapoint[1].toFixed(2)).css({top: item.pageY + 5, left: item.pageX + 5}).fadeIn(200);
                    } else {
                        $("#<?= $this->_id ?>_tooltip").hide();
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Affiche un graphique en "camambert"
     * @param array $data array( <br />
     *     array("label"=>"label1", <br />
     *         "data"=>10 <br />
     *     ), <br />
     *     array("label"=>"label2", <br />
     *         "data"=>20 <br />
     *     ) <br />
     * )
     */
    public function pie($data) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $this->_id ?>").plot(<?= json_encode($data) ?>, {
                    series: {pie: {show: true, radius: 1, label: {show: true, radius: 3 / 4, formatter: labelFormatter}}},
                    grid: {hoverable: true, clickable: true}
                });
            });
        </script>
        <?php
    }

    /**
     * Affiche un graphique en anneau
     * @param array $data array( <br />
     *     array("label"=>"label1", <br />
     *         "data"=>10 <br />
     *     ), <br />
     *     array("label"=>"label2", <br />
     *         "data"=>20 <br />
     *     ) <br />
     * )
     */
    public function ring($data) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $this->_id ?>").plot(<?= json_encode($data) ?>, {
                    series: {pie: {innerRadius: 0.5, show: true, radius: 1, label: {show: true, radius: 3 / 4, formatter: labelFormatter}}},
                    grid: {hoverable: true, clickable: true}
                });
            });
        </script>
        <?php
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
