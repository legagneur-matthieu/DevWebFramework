<?php

/**
 * Cette classe permet d'afficher et explorer une arborescence
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class file_explorer {

    /**
     * Cette classe permet d'afficher et explorer une arborescence
     * @param string $dir Dossier a explorer 
     */
    public function __construct($dir) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("div.file_explorer > ul.file_explorer").menu();
            });
        </script>
        <div class="file_explorer">
            <?php
            $this->explore($dir);
            ?>
        </div>
        <?php
    }

    /**
     * Explore et affiche l'arborescence
     * @param string $dir Dossier à explorer 
     */
    private function explore($dir) {
        ?>
        <ul class="file_explorer">
            <?php
            foreach (glob($dir . "/*") as $dirorfile) {
                ?>
                <li>
                    <?php
                    $name = strtr($dirorfile, array("../" => "", "./" => "", $dir . "/" => ""));
                    if (is_dir($dirorfile)) {
                        $id = strtr($dirorfile, array(" " => "", "/" => "_"));
                        ?>
                        <a href="#<?php echo $id; ?>" id="<?php echo $id; ?>"><?php echo html_structures::glyphicon("folder-open", "Dossier") . " " . $name; ?> </a>
                        <?php
                        $this->explore($dirorfile);
                    } else {
                        ?>
                        <a href="<?php echo strtr($dirorfile, array("//" => "/")); ?>" target="_blank"><?php echo html_structures::glyphicon("file", "Fichier") . " " . $name; ?></a>  
                        <?php
                    }
                    ?>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    }

}
