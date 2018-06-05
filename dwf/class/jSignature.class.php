<?php

/**
 * Description of jSignature
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class jSignature {

    private static $_called = false;

    public function __construct($id, $label = "Signature", $dataformat = "svgbase64") {
        if (!self::$_called) {
            ?>
            <!--[if lt IE 9]>
                <script type="text/javascript" src="../commun/src/js/jSignature/flashcanvas.js"></script>
            <![endif]-->
            <script type="text/javascript" src="../commun/src/js/jSignature/jSignature.min.js"></script>
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
        <div class="form-group">
            <label for="<?= $id ?>"><?= $label ?></label> <a id="<?= $id ?>_reset" class="btn btn-xs btn-default">Reset</a>
            <div id="<?= $id ?>_div"></div>
            <input type="hidden" id="<?= $id ?>" name="<?= $id ?>" value="" />
        </div>
        <?php
    }

}
