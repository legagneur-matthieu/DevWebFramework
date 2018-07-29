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
        $li = "";
        foreach (glob($dir . "/*") as $dirorfile) {
            $name = strtr($dirorfile, ["../" => "", "./" => "", $dir . "/" => ""]);
            if (is_dir($dirorfile)) {
                $id = strtr($dirorfile, [" " => "", "/" => "_"]);
                $a = tags::tag("a", ["href" => "#" . $id, "id" => $id], html_structures::glyphicon("folder-open", "Dossier") . " " . $name);
                $this->explore($dirorfile);
            } else {
                $a = tags::tag("a", ["href" => strtr($dirorfile, ["//" => "/"]), "target" => "_blank"], html_structures::glyphicon("file", "Fichier") . " " . $name);
            }
            $li .= tags::tag("li", [], $a);
        }
        echo tags::tag("ul", ["class" => "file_explorer"], $li);
    }

}
