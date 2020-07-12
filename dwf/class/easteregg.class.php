<?php

/**
 * Cette classe permet d'afficher des "oeufs de PÃ¢ques" qui s'affichent à certaines dates de l'année
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class easteregg {

    /**
     * Cette classe permet d'afficher des "oeufs de PÃ¢ques" qui s'affichent à certaines dates de l'année    * 
     */
    public function __construct() {
        $date = date("dm");
        if (session::get_val("eggday")) {
            $date = session::get_val("eggday");
        }
        switch ($date) {
            case "3112"://jour de l'an
            case "0101":
                $this->jour_de_an();
                break;
            case "0601": // Epiphanie
                $this->epiphanie();
                break;
            case "1402": // Saint-Valentin
                $this->valentin();
                break;
//            case "0003": // PÃ¢ques ? (dimanche qui suit la première pleine lune aprés le 21 mars ?)
//                break;
            case "2103": // Printemps
                $this->printemp();
                break;
            case "0104": // 1er avril
                $this->fish();
                break;
            case "0105": // Fête du travail
                $this->muguet();
                break;
            case "2505": // Fête des mères (dernier dimanche de mai) à finir
            case "2605":
            case "2705":
            case "2805":
            case "2905":
            case "3005":
            case "3105":
                $this->fete_mere();
                break;
            case "2106": //Eté
                $this->ete();
            case "1606": // Fête des pères (3eme dimanche de juin) à finir
            case "1706":
            case "1806":
            case "1906":
            case "2006":
            case "2206":
                $this->fete_pere();
                break;
            case "1407": // Fête Nationale
                $this->fete_national();
                break;
            case "2309": // Automne
                $this->automne();
                break;
            case "2511": // Sainte-Catherine
                $this->catherine();
                break;
            case "2212": //Hiver
            case "2312":
            case "2412": //NoÃ«l
            case "2512":
                $this->noel();
                break;
//            case "0005": // jeudi de l'ascension
//                break;
//            case "0805": // victoire 1945
//                break;
//            case "0005": // PentecÃ´te (49 jours après PÃ¢ques)?
//                break;
//            case "1508": // Assomption
//                break;
//            case "0111": // Toussaint
//                break;
//            case "1111": // Armistice
//                break;
        }
        $this->kegg();
    }

    /**
     * Detecte si un easteregg doit s'activer aujourd'hui
     */
    private function kegg() {
        $events = [
            ["0001", "-Aucun-", true],
            ["0101", "Jour de l'an"],
            ["0601", "Epiphanie"],
            ["1402", "Saint Valentin"],
            ["2103", "Printemps"],
            ["0104", "1e Avril"],
            ["0105", "Fête du travail"],
            ["2505", "Fête des mères (que le dimanche]"],
            ["2106", "Eté (et fête des pères le dimanche)"],
            ["1407", "Fête nationale"],
            ["2309", "Automne"],
            ["2511", "Sainte Catherine"],
            ["2212", "Hiver"]
        ];
        $form=new form();
        $form->select("Evenement à activer", "eggday", $events);
        $form->submit("btn-primary", "Activer");
        (new modal())->link_open_modal("", "modal_eggday", '', "Evenements", $form->render(), "");
        if (isset($_POST["eggday"])) {
            if (strlen($_POST["eggday"]) == 4) {
                session::set_val("eggday", $_POST["eggday"]);
                js::redir("");
            }
        }
        echo html_structures::script("../commun/src/js/eastereggs/cheet/cheet.min.js");
        ?>
        <script type="text/javascript">
            cheet("U U D D L R L R b a", function () {
                $("#modal_eggday").click();
            });
        </script>
        <?php

    }

    /**
     * easteregg du jour de l'an
     */
    private function jour_de_an() {
        compact_css::get_instance()->add_css_file('../commun/src/js/eastereggs/snowstorm/lights/christmaslights.css" media="screen');
        echo html_structures::script("../commun/src/js/eastereggs/snowstorm/lights/yahoo-animation.js") .
        html_structures::script("../commun/src/js/eastereggs/snowstorm/lights/soundmanager2-nodebug-jsmin.js") .
        html_structures::script("../commun/src/js/eastereggs/snowstorm/lights/christmaslights.js")
        ?>
        <script type="text/javascript">
            var urlBase = '../commun/src/js/eastereggs/snowstorm/lights/';
            soundManager.url = '../commun/src/js/eastereggs/snowstorm/lights/';
        </script>
        <?php

        echo tags::tag("div", ["id" => "lights", "style" => "width: 100%; position: absolute;"], "");
    }

    /**
     * easteregg de l'epiphanie
     */
    private function epiphanie() {
        compact_css::get_instance()->add_css_file('../commun/src/js/eastereggs/epiphanie/epiphanie.css');
        echo html_structures::script("../commun/src/js/eastereggs/epiphanie/epiphanie.js");
    }

    /**
     * easteregg de la saint-Valentin
     */
    private function valentin() {
        echo html_structures::script("../commun/src/js/eastereggs/snowstorm/snowstorm-min.js");
        ?>
        <script type="text/javascript">
            snowStorm.snowCharacter = "&#9829;";
            snowStorm.snowColor = "red";
            snowStorm.snowStick = false;
            snowStorm.flakeWidth = 14;
            snowStorm.flakeHeight = 14;
            snowStorm.flakesMax = 69;
        </script>
        <?php

    }

    /**
     * easteregg du printemps
     */
    private function printemp() {
        compact_css::get_instance()->add_css_file("../commun/src/js/eastereggs/sakura/jquery-sakura.min.css");
        echo html_structures::script("../commun/src/js/eastereggs/sakura/jquery-sakura.min.js");
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("body").sakura();
            });
        </script>
        <?php

    }

    /**
     * easteregg du 1er Avril
     */
    private function fish() {
        echo html_structures::script("../commun/src/js/eastereggs/fish/fish.js");
    }

    /**
     * easteregg du 1er Mai
     */
    private function muguet() {
        compact_css::get_instance()->add_css_file('../commun/src/js/eastereggs/muguet/muguet.css');
        echo html_structures::script("../commun/src/js/eastereggs/muguet/muguet.js");
    }

    /**
     * Verifie si on est un dimanche
     */
    private function is_sunday() {
        $wday = time::get_info_from_date(date("Y-m-d"));
        $wday = $wday["wday"];
        return ($wday == 0);
    }

    /**
     * easteregg de la fete de mères
     */
    private function fete_mere() {
        if ($this->is_sunday()) {
            compact_css::get_instance()->add_css_file('../commun/src/js/eastereggs/fete_mere/fete_mere.css');
            echo html_structures::script("../commun/src/js/eastereggs/fete_mere/fete_mere.js");
        }
    }

    /**
     * easteregg de la fete de pères
     */
    private function fete_pere() {
        if ($this->is_sunday()) {
            (new modal())->link_open_modal("", "fete_pere", "", "Bonne fête des péres", '<img src="../commun/src/js/eastereggs/fete_pere/fete_pere.jpg" alt="Diplome du meilleur père de l\'année" style="width:100%" />', '');
            ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    date = new Date;
                    if (localStorage.getItem("fete_pere") === undefined || localStorage.getItem("fete_pere") < date.getFullYear()) {
                        localStorage.setItem("fete_pere", date.getFullYear());
                        $("#fete_pere").click();
                    }
                });
            </script>
            <?php

        }
    }

    /**
     * easteregg de l'été
     */
    private function ete() {
        compact_css::get_instance()->add_css_file('../commun/src/js/eastereggs/ete.css');
    }

    /**
     * easteregg du 14 juillet
     */
    private function fete_national() {
        compact_css::get_instance()->add_css_file('../commun/src/js/eastereggs/fireworks/fireworks.css');
        echo html_structures::script("../commun/src/js/eastereggs/fireworks/fireworks.js");
    }

    /**
     * easteregg de l'Automne
     */
    private function automne() {
        echo html_structures::script("../commun/src/js/eastereggs/snowstorm/snowstorm-min.js")
        ?>
        <script type="text/javascript">
            snowStorm.snowCharacter = '<img src="../commun/src/js/eastereggs/automne/automne.gif" alt="" />';
            snowStorm.snowColor = "white";
            snowStorm.flakeWidth = 20;
            snowStorm.flakeHeight = 20;
            snowStorm.flakesMax = 10;
        </script>
        <?php

    }

    /**
     * easteregg de la sainte Catherine
     */
    private function catherine() {
        compact_css::get_instance()->add_css_file('../commun/src/js/eastereggs/catherine/catherine.css');
        echo html_structures::script("../commun/src/js/eastereggs/catherine/catherine.js");
    }

    /**
     * easteregg de NoÃ«l
     */
    private function noel() {
        echo html_structures::script("../commun/src/js/eastereggs/snowstorm/snowstorm-min.js");
    }

}
