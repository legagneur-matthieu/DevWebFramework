<?php

/**
 * Cette classe permet de recueillir et d'afficher des statistiques liès a l'activitè des utilisateurs
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class statistiques {

    /**
     * Cette classe permet de recueillir et d'afficher des statistiques liès a l'activitè des utilisateurs
     */
    public function __construct() {
        if (config::$_statistiques) {
            $datas = array(
                "stat_visitor" => array(
                    array("id", "int", true),
                    array("imat", "string", false),
                    array("user_agent", "string", false),
                ),
                "stat_pages" => array(
                    array("id", "int", true),
                    array("visitor", "visitor", false),
                    array("page", "string", false),
                    array("date", "string", false),
                )
            );
            foreach ($datas as $table => $data) {
                new entity_generator($data, $table, true, true);
            }
        }
    }

    /**
     * Cette fonction permet d'enregistrer l'activitè des utilisateurs sur la page actuelle
     */
    public function add_stat() {
        if (!in_array($_SERVER["REMOTE_ADDR"], array("localhost", "127.0.0.1", "::1")) and config::$_statistiques) {
            if (session::get_val("stat_uid") == false) {
                session::set_val("stat_uid", uniqid());
            }
            $imat = hash("gost", session::get_val("stat_uid") . "@" . $_SERVER["REMOTE_ADDR"]);
            if (stat_visitor::get_count("imat='" . $imat . "'") == 0) {
                stat_visitor::ajout($imat, (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "Inconnu"));
                (new log_file())->info(json_encode($_SERVER));
            }
            $visitor = stat_visitor::get_table_array("imat='" . $imat . "'");
            stat_pages::ajout($visitor[0]["id"], $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], date("Y-m-d H:i:s"));
        }
    }

    /**
     * Cette fonction permet d'afficher les statistiques ( il est conseillè ne ne pas appeler cette fonction sur une page "publique" )
     */
    public function get_stat() {
        //echo html_structures::link_in_body("../commun/src/css/statistiques.css");
        ?>
        <script type="text/javascript">
            $("head").append('<style type="text/css">' +
                    '.plot{ width: 800px; min-height: 400px; margin: 0 auto; }' +
                    '.stat_ring{ margin: 0 auto; width: 600px; height: 400px; margin-bottom: -100px; }' +
                    '.graph-legend{ font-size: 10px; }' +
                    '</style>');
        </script>
        <?php
        $key = "stat";
        $route = array(
            array($key => "get_stat_an", "title" => "Statistiques annuelles", "text" => "Statistiques annuelles"),
            //array($key => "get_stat_other", "title" => "Statistiques diverses", "text" => "Statistiques diverses"),
            array($key => "consult", "title" => "")
        );
        (new sub_menu($this, $route, $key, "get_stat_an"));
    }

    /**
     * fonction appelèe par $this->get_stat(), à ne pas utiliser !
     */
    public function get_stat_an() {
        $default = $this->check_form();
        $this->form($default);
        echo html_structures::hr();
        ?>
        <h2 class="text-center"><small>Nombre de visiteurs unique <sup title="Un visiteur unique est dèfini par son cookie de session et son ip">*</sup></small></h2>
        <?php
        $this->get_uniques_visitors($default);
        echo html_structures::hr();
        ?>
        <h2 class="text-center"><small>Indicateur d'activitè par heures</small></h2>
        <?php
        $this->get_activity_per_hours($default);
        echo html_structures::hr();
        ?>
        <h2 class="text-center"><small>Navigateurs</small></h2>
        <?php
        $this->get_browser($default);
    }

    /**
     * Affiche les statistiques sur les visiteurs uniques
     * @param array $default $this->check_form()
     */
    private function get_uniques_visitors($default) {
        $data = array(
            array("label" => "Visiteurs",
                "data" => $this->get_plot_uniques_visitors($default)
            )
        );
        $tricks = array(
            array(0, "Janvier"),
            array(1, "Fevrier"),
            array(2, "Mars"),
            array(3, "Avril"),
            array(4, "Mai"),
            array(5, "Juin"),
            array(6, "Juillet"),
            array(7, "AoÃ»t"),
            array(8, "Septembre"),
            array(9, "Octobre"),
            array(10, "Novembre"),
            array(11, "Dècembre")
        );
        (new graphique())->line($data, $tricks, "stat_plot");
        ?>
        <ul>
            <?php
            foreach ($data[0]["data"] as $d) {
                ?>
                <li>
                    <?php
                    $mois = "";
                    if ($d[0] + 1 < 10) {
                        $mois .= "0";
                    }
                    $mois .= ($d[0] + 1);
                    echo time::convert_mois($mois) . " : ";
                    if ($d[1] != 0) {
                        echo html_structures::a_link("index.php?page=" . $_GET["page"] . "&amp;stat=consult&amp;date=" . $default["an"] . "_" . $mois, $d[1] . " Visiteurs");
                    } else {
                        echo "Vous n'avez eu aucun visiteur.";
                    }
                    ?>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    }

    /**
     * Retourne les statistiques sur un visiteur unique
     * @param array $default $this->check_form()
     * @return array Data du graphique de $this->get_uniques_visitors();
     */
    private function get_plot_uniques_visitors($default) {
        $data = array();
        for ($i = 0; $i < 12; $i++) {
            $date_debut = $default["an"] . "-";
            if ($i + 1 < 10) {
                $date_debut .= "0";
            }
            $date_debut .= $i + 1;
            $date_fin = $date_debut . "-31";
            $date_debut .= "-01";

            $req = application::$_bdd->fetch("select distinct visitor from stat_pages where date between '" . $date_debut . "' and '" . $date_fin . "';");
            $data[$i] = array($i, count($req));
        }
        return $data;
    }

    /**
     * Affiche les statistiques d'activitès par heures
     * @param array $default $this->check_form()
     */
    private function get_activity_per_hours($default) {
        $data = array(
            array(
                "label" => "Pages visitèes",
                "data" => $this->get_plot_activity_per_hours($default)
            )
        );
        $tricks = array(
            array(0, "00h - 01h"),
            array(1, "01h - 02h"),
            array(2, "02h - 03h"),
            array(3, "03h - 04h"),
            array(4, "04h - 05h"),
            array(5, "05h - 06h"),
            array(6, "06h - 07h"),
            array(7, "07h - 08h"),
            array(8, "08h - 09h"),
            array(9, "09h - 10h"),
            array(10, "10h - 11h"),
            array(11, "11h - 12h"),
            array(12, "12h - 13h"),
            array(13, "13h - 14h"),
            array(14, "14h - 15h"),
            array(15, "15h - 16h"),
            array(16, "16h - 17h"),
            array(17, "17h - 18h"),
            array(18, "18h - 19h"),
            array(19, "19h - 20h"),
            array(20, "20h - 21h"),
            array(21, "21h - 22h"),
            array(22, "22h - 23h"),
            array(23, "23h - 24h")
        );

        (new graphique())->line($data, $tricks, "stat_plot_activity_per_hours");
        ?>
        <ul>
            <?php
            for ($i = 0; $i < 24; $i++) {
                ?>
                <li>
                    <?php echo $tricks[$i][1] . " : " . $data[0]["data"][$i][1] . " pages visitèes"; ?>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    }

    /**
     * Retourne les donnèes du graphique de $this->get_activity_per_hours();
     * @param array $default $this->check_form()
     * @return array Donnèes du graphique de $this->get_activity_per_hours();
     */
    private function get_plot_activity_per_hours($default) {
        $pages = stat_pages::get_table_array("date between '" . $default["an"] . "-01-01' and '" . $default["an"] . "-12-31'");
        $heures = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        foreach ($pages as $page) {
            $heure = explode(" ", $page["date"]);
            $heure = ((int) strtr($heure[1], array(":" => "")));
            for ($i = 0; $i < 24; $i++) {
                if ($heure < (($i + 1) * 10000)) {
                    $heures[$i] = $heures[$i] + 1;
                    break;
                }
            }
        }
        $data = array();
        foreach ($heures as $key => $value) {
            $data[] = array($key, $value);
        }
        return $data;
    }

    /**
     * Affiche les statistiques sur les navigateurs des visiteurs
     * @param array $default $this->check_form()
     */
    private function get_browser($default) {
        $req = application::$_bdd->fetch("select distinct visitor from stat_pages where date between '" . $default["an"] . "-01-01' and '" . $default["an"] . "-12-31'");
        if (($total = count($req)) != 0) {
            $in = "id in(";
            foreach ($req as $v) {
                $in .= $v["visitor"] . ",";
            }
            $in .= "__)";
            $in = strtr($in, array(",__" => ""));
            $data = array();
            foreach (application::$_bdd->fetch("select user_agent, count(*) as count from `stat_visitor`where " . $in . " group by user_agent;") as $key => $value) {
                $data[] = [$value["user_agent"], $value["count"], $value["count"] / $total * 100];
            }
            js::datatable();
            echo html_structures::table(["Navigateur", "Nombre d'utilisateur", "Pourcentage (%)"], $data, '', "datatable");
        }
    }

    /**
     * Fonction appelèe par $this->get_stat(), à ne pas utiliser ! <br />
     * Affiche les statistiques dètaillèes d'un mois
     */
    public function consult() {
        if (isset($_GET["date"])) {
            $date = explode("_", $_GET["date"]);
            sub_menu::add_active_tab("stat", $stat = time::convert_mois($date[1]) . " " . $date[0]);
            js::before_title("Statistiques " . $stat . " -");
            $date_debut = $date[0] . "-" . $date[1];
            $date_fin = $date_debut . "-31";
            $date_debut .= "-01";
            $where = "date between '" . application::$_bdd->protect_var($date_debut) . "' and '" . application::$_bdd->protect_var($date_fin) . "'";
            $req = application::$_bdd->fetch("select distinct visitor from stat_pages where " . $where);
            if (count($req) != 0) {
                $in = "id in(";
                foreach ($req as $v) {
                    $in .= $v["visitor"] . ",";
                }
                $in .= "__);";
                $in = strtr($in, array(",__" => ""));
                $visitors = stat_visitor::get_table_ordored_array($in); //application::$_bdd->fetch("select imat from stat_visitor " . $in);
                $nav = stat_pages::get_table_array("date between '" . $date_debut . "' and '" . $date_fin . "';");
                $req = application::$_bdd->fetch("select page, count(*) as count from stat_pages where " . $where . " group by page");
                $data = array();
                $titles = array();
                foreach ($req as $page) {
                    $titles[$page["page"]] = ["title" => $this->get_real_title_from_url($page["page"]), "count" => $page["count"]];
                    $data[] = [$titles[$page["page"]]["title"], $page["page"], $page["count"]];
                }
                ?><h2 class="text-center"><small>Pages visitèes</small></h2><?php
                js::datatable();
                echo html_structures::table(["Page", "URL", "Visites"], $data, '', "datatable") . html_structures::hr();
                ?><h2 class="text-center"><small>Historique des visiteurs</small></h2><?php
                foreach ($nav as $link) {
                    $visitors[$link["visitor"]]["nav"][] = array("date" => $link["date"], "page" => strtr($link["page"], array($_SERVER["HTTP_HOST"] => "localhost")), "title" => $titles[$link["page"]]["title"]);
                }
                js::accordion();
                ?>
                <div id="accordion">
                    <?php foreach ($visitors as $visitor) {
                        ?>
                        <h3>
                            <?php echo $visitor["imat"] ?>
                        </h3>
                        <p>
                            <?php
                            foreach ($visitor["nav"] as $nav) {
                                $date = explode(" ", $nav["date"]);
                                $date = time::convert_date($date[0]) . " " . $date[1];
                                echo html_structures::a_link($nav["page"], $date . " - " . $nav["title"], "", $nav["page"]) . "<br />";
                            }
                            ?>
                        </p>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        }
    }

    /**
     * Affiche le formulaire de selection d'annèes
     * @param array $default $this->check_form()
     */
    private function form($default) {
        form::new_form();
        $y = date("Y");
        $option = array();
        for ($i = 5; $i >= 0; $i--) {
            $k = $y - $i;
            $option[] = array($k, $k, ($k == $default["an"]));
        }
        form::select("Annèe", "stat_an", $option);
        form::submit("btn-default", "Voir");
        form::close_form();
    }

    /**
     * Retourne la valeur par default à utiliser dans les statistiques
     * @return array retourne $default["an"]
     */
    private function check_form() {
        $y = date("Y");
        if (isset($_POST["stat_an"]) and math::is_int($_POST["stat_an"]) and ( $_POST["stat_an"] >= $y - 5 and $_POST["stat_an"] <= $y)) {
            $default["an"] = $_POST["stat_an"];
        } else {
            $default["an"] = $y;
        }
        return $default;
    }

    /**
     * Récupére le contenu de p#real_title sur l'url passè en paramétre 
     * @param string $url URL
     * @return string contenu de p#real_title
     */
    private function get_real_title_from_url($url) {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTMLFile($url);
        $title = $dom->getElementById("real_title")->textContent;
        libxml_clear_errors();
        return $title;
    }

}
