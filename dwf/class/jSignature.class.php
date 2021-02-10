<?php

/**
 * Créé un champ de formulaire pour les signatures numériques
 * Peut être appelé depuis la méthode statique form::jSignature()
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class jSignature {

    /**
     * Permet de vérifier que la librairie jSignature a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie jSignature a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Id CSS pour jSignature
     * @var string $id id CSS pour jSignature
     */
    private $_id;

    /**
     * Label
     * @var string $label Label 
     */
    private $_label;

    /**
     * Format de données retournées : svgbase64 (defaut), svg ou base30 
     * @var string $dataformat Format de données returnées : svgbase64 (defaut), svg ou base30 
     */
    private $_dataformat;

    /**
     * Créé un champ de formulaire pour les signatures numériques
     * 
     * @param string $id id CSS pour jSignature
     * @param string $label Label
     * @param string $dataformat Format de données returnées : svgbase64 (defaut), svg ou base30
     */
    public function __construct($id, $label = "Signature", $dataformat = "svgbase64") {
        if (!self::$_called) {
            echo html_structures::script("../commun/src/js/jSignature/jSignature.min.js");
            ?>
            <!--[if lt IE 9]>
                <script type="text/javascript" src="../commun/src/js/jSignature/flashcanvas.js"></script>
            <![endif]-->
            <?php
            self::$_called = true;
        }
        $this->_id = $id;
        $this->_label = $label;
        $this->_dataformat = $dataformat;
    }

    public function render() {
        $script = "$(document).ready(function () {
                $(\"#{$this->_id}_div\").jSignature();
                $(\"#{$this->_id}_reset\").click(function (e) {
                    e.preventDefault();
                    $(\"#{$this->_id}_div\").jSignature(\"reset\");
                    $(\"#{$this->_id}\").val(\"\");
                });
                $(\"#{$this->_id}_div\").change(function () {
                    $(\"#{$this->_id}\").val($(\"#{$this->_id}_div\").jSignature(\"getData\", \"{$this->_dataformat}\"));
                });
            });";
        return tags::tag("div", ["class" => "form-group"], tags::tag(
                                "label", ["for" => $this->_id], $this->_label) .
                        tags::tag("a", ["id" => $this->_id . "_reset", "class" => "btn btn-xs btn-primary"], "Reset") .
                        tags::tag("div", ["id" => $this->_id . "_div"], "") .
                        tags::tag("input", ["type" => "hidden", "id" => $this->_id, "name" => $this->_id, "value" => ""])
                ) . tags::tag("script", ["type" => "text/javascript"], $script);
    }

}
