<?php

/**
 * Cette classe est une boite à outils de débogage
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class debug {

    /**
     * Permet de verifier si la fonction show_report() a été appelée pour afficher le rapport à la fin de l'execution
     * @var boolean Permet de verifier si la fonction show_report() a été appelé
     */
    private static $_show_report = false;

    /**
     * Affiche la structure d'une variable ( optimisé pour les arrays et objets )
     * @param array|object $var variable à verifier 
     */
    public static function print_r($var) {
        ?>
        <pre><?php print_r($var); ?></pre>
        <?php
    }

    /**
     * Affiche le contenu et le type d'une variable ( optimisé pour les type nombres, chaines de caractères et les booleans )
     * @param int|string|boolean|double|float $var variable à verifier 
     */
    public static function var_dump($var) {
        ?>
        <pre><?php var_dump($var); ?></pre>
        <?php
    }

    /**
     * Affiche la trace de l'application pour arriver au point de débug ( trace des fichiers et méthodes qui ont été appelés)
     */
    public static function getTrace() {
        self::print_r((new dwf_exception("Trace"))->getTraceAsString());
    }

    /**
     * Affiche le rapport d'activités de PHP en bas de page
     * NE PAS UTILISER EN PRODUCTION !
     */
    public static function show_report() {
        self::$_show_report = true;
    }

    /**
     * Evenementiel : Affiche le rapport ( utilisez debug::show_report() !)
     */
    public static function onhtml_body_end() {
        if (self::$_show_report) {
            $limit = ini_get("memory_limit");
            $puissance = array(
                "B" => strtr(pow(10, 0), array("1" => "")),
                "K" => strtr(pow(10, 3), array("1" => "")),
                "M" => strtr(pow(10, 6), array("1" => "")),
                "G" => strtr(pow(10, 9), array("1" => "")),
                "T" => strtr(pow(10, 12), array("1" => "")),
            );
            $data = '<p>$_POST</p><pre>';
            foreach ($_POST as $key => $value) {
                $data .= $key . " => " . (!is_array($value) ? $value : json_encode($value)) . "\n";
            }
            $data .= '</pre><p>$_GET</p><pre>';
            foreach ($_GET as $key => $value) {
                $data .= $key . " => " . (!is_array($value) ? $value : json_encode($value)) . "\n";
            }
            $data .= '</pre><p>$_REQUEST</p><pre>';
            foreach ($_REQUEST as $key => $value) {
                $data .= $key . " => " . (!is_array($value) ? $value : json_encode($value)) . "\n";
            }
            $data .= '</pre><p>$_COOKIE</p><pre>';
            foreach ($_COOKIE as $key => $value) {
                $data .= $key . " => " . (!is_array($value) ? $value : json_encode($value)) . "\n";
            }
            $data .= '</pre><p>$_SESSION</p><pre>';
            foreach ($_SESSION as $key => $value) {
                $data .= $key . " => " . (!is_array($value) ? $value : htmlspecialchars(json_encode($value))) . "\n";
            }
            $data .= '</pre><p>STATEMENTS</p><ol>';
            foreach (bdd::$_debug["statements"] as $key => $value) {
                $data .= '<li title="' . $value["trace"] . '"><p>' . $value["req"] . '</p></li>';
            }
            $data .= '</ol>';
            $cl = "<dl class='dl-horizontal'>";
            foreach (website::$_class as $key => $value) {
                $cl .= "<dt>" . (in_array($value, array_keys(singleton::$_instances)) ? "<small>(singleton)</small> " : "") . $value . "</dt><dd>" . $key . "</dd>";
            }
            $cl .= "</dl>";
            $modal = new modal();
            ?>
            <div style="position: fixed; bottom: 0; width: 100%;margin: 0; padding: 0;margin-top: 600px;" class="alert alert-info">
                <script type="text/javascript">
                    $(document).ready(function () {
                        $("body").css("margin-bottom", "100px");
                    });
                </script>
                <p>Debug report :</p>
                <div class="row" style="width: 98%; margin-left: 1%;">
                    <div class="col-xs-2">
                        <p>
                            <?php
                            $modal->link_open_modal("<strong>Classes chargées : </strong>" . count(website::$_class), "debug_classloader", "Classes chargées", "Classes chargées", $cl, "")
                            ?>
                            <br />
                            <strong>Temp d'execution : </strong><?= time::parse_time(time::chronometer_get("debug_exec")); ?>
                        </p>
                    </div>
                    <div class="col-xs-4">
                        <p>
                            <strong>Memoire utilisée / limit : </strong>                              
                            <?php
                            echo number_format($memory = memory_get_usage(), 0, ".", " ") . " Octet / " . $limit . "o";
                            ?> <br />
                            <strong>Memoire utilisé (%) : </strong><?= memory_get_usage() / ((int) strtr($limit, $puissance)) . " %"; ?>
                        </p>
                    </div>
                    <div class="col-xs-4">
                        <p>
                            <strong>Nombre de requêtes SQL : </strong><?= bdd::$_debug["nb_req"]; ?><br />
                            <strong>Memoire utilisé par PHP pour SQL : </strong><?= number_format(bdd::$_debug["memory"], 0, ".", " "); ?> Octet<br />
                            <strong>Memoire utilisé par PHP pour SQL (%) : </strong><?= number_format(bdd::$_debug["memory"] / $memory * 100, 0, ".", " "); ?> %
                        </p>
                    </div>
                    <div class="col-xs-2">
                        <?php
                        $modal->link_open_modal("Données (post, get, session, ...)", "debug_data", "Données", "Données", $data, "");
                        ?>
                    </div>
                </div>
                <p>                    
            </div>
            <?php
        }
    }

}
