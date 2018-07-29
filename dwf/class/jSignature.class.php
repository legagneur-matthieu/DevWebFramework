<?php

/**
 * Créé un champs de formulaire pour les signatures numeriques
 * Peut être appelé depuis la methode statique form::jSignature()
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
     * Créé un champs de formulaire pour les signatures numeriques
     * 
     * @param string $id id CSS pour jSignature
     * @param string $label Label
     * @param string $dataformat Format de donné returné : svgbase64 (defaut), svg ou base30
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
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?= $id ?>_div").jSignature();
                $("#<?= $id ?>_reset").click(function (e) {
                    e.preventDefault();
                    $("#<?= $id ?>_div").jSignature("reset");
                    $("#<?= $id ?>").val("");
                });
                $("#<?= $id ?>_div").change(function () {
                    $("#<?= $id ?>").val($("#<?= $id ?>_div").jSignature("getData", "<?= $dataformat ?>"));
                });
            });
        </script>
        <?php
        echo tags::tag("div", ["class" => "form-group"], tags::tag(
                        "label", ["for" => $id], $label) .
                tags::tag("a", ["id" => $id . "_reset", "class" => "btn btn-xs btn-default"], "Reset") .
                tags::tag("div", ["id" => $id . "_div"], "") .
                tags::tag("input", ["type" => "hidden", "id" => $id, "name" => $id, "value" => ""])
        );
    }

}
