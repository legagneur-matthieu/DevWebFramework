<?php

/**
 * Cette classe permet de recueillir et d'afficher des statistiques liès à l'activitè des utilisateurs
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class statistiques {

    /**
     * Cette classe permet de recueillir et d'afficher des statistiques liès à l'activitè des utilisateurs
     */
    public function __construct() {
        if (config::$_statistiques) {
            $datas = [
                "stat_visitor" => [
                    ["id", "int", true],
                    ["imat", "string", false],
                    ["user_agent", "string", false],
                ],
                "stat_pages" => [
                    ["id", "int", true],
                    ["visitor", "visitor", false],
                    ["page", "string", false],
                    ["date", "string", false],
                ]
            ];
            foreach ($datas as $table => $data) {
                new entity_generator($data, $table, true, true);
            }
        }
    }

    /**
     * Cette fonction permet d'enregistrer l'activitè des utilisateurs sur la page actuelle
     */
    public function add_stat() {
        if (!in_array($_SERVER["REMOTE_ADDR"], ["localhost", "127.0.0.1", "::1"]) and config::$_statistiques) {
            if (session::get_val("stat_uid") == false) {
                session::set_val("stat_uid", uniqid());
            }
            $imat = hash("gost", session::get_val("stat_uid") . "@" . $_SERVER["REMOTE_ADDR"]);
            if (stat_visitor::get_count("imat=:imat", [":imat" => $imat]) == 0) {
                stat_visitor::ajout($imat, (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "Inconnu"));
                (new log_file())->info(json_encode($_SERVER));
            }
            $visitor = stat_visitor::get_table_array("imat=:imat", [":imat" => $imat]);
            stat_pages::ajout($visitor[0]["id"], $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], date("Y-m-d H:i:s"));
        }
    }

    /**
     * Cette fonction permet d'afficher les statistiques ( il est conseillè de ne pas appeler cette fonction sur une page "publique" )
     */
    public function get_stat() {
        //compact_css::get_instance()->add_css_file("../commun/src/css/statistiques.css");
        compact_css::get_instance()->add_style((new css())
                        ->add_rule(".plot", ["width" => "800px", "min-height" => "400px", "margin" => "0 auto"])
                        ->add_rule(".stat_ring", ["margin" => "0 auto", "width" => "600px", "height" => "400px", "margin-bottom" => "-100px"])
                        ->add_rule(".graph-legend", ["font-size" => "10px"])
        );
        $key = "stat";
        $route = [
            [$key => "get_stat_an", "title" => "Statistiques annuelles", "text" => "Statistiques annuelles"],
            [$key => "consult", "title" => ""]
        ];
        (new sub_menu($this, $route, $key, "get_stat_an"));
    }

    /**
     * Fonction appelèe par $this->get_stat(), à ne pas utiliser !
     */
    public function get_stat_an() {
        $default = $this->check_form();
        $this->form($default);
        echo html_structures::hr() .
        tags::tag("h2", ["class" => "text-center"], tags::tag("small", [], "Nombre de visiteurs unique " . tags::tag("sup", ["title" => "Un visiteur unique est défini par son cookie de session et son ip"], "*")));
        $this->get_uniques_visitors($default);
        echo html_structures::hr() . tags::tag("h2", ["class" => "text-center"], tags::tag("small", [], "Indicateur d'activité par heure"));
        $this->get_activity_per_hours($default);
        echo html_structures::hr() . tags::tag("h2", ["class" => "text-center"], tags::tag("small", [], "Navigateurs"));
        $this->get_browser($default);
    }

    /**
     * Affiche les statistiques sur les visiteurs uniques
     * @param array $default $this->check_form()
     */
    private function get_uniques_visitors($default) {
        $data = [
            ["label" => "Visiteurs",
                "data" => $this->get_plot_uniques_visitors($default)
            ]
        ];
        $tricks = [
            [0, "Janvier"],
            [1, "Fevrier"],
            [2, "Mars"],
            [3, "Avril"],
            [4, "Mai"],
            [5, "Juin"],
            [6, "Juillet"],
            [7, "Aout"],
            [8, "Septembre"],
            [9, "Octobre"],
            [10, "Novembre"],
            [11, "Dècembre"]
        ];
        (new graphique("stat_graph_visiteur_unique", ["width" => "100%", "height" => "300px"]))->line($data, $tricks, "stat_plot");
        $li = [];
        foreach ($data[0]["data"] as $d) {
            $mois = "";
            if ($d[0] + 1 < 10) {
                $mois .= "0";
            }
            $mois .= ($d[0] + 1);
            $li[] = time::convert_mois($mois) . " : " . ($d[1] != 0 ? html_structures::a_link("index.php?page=" . $_GET["page"] . "&stat=consult&date=" . $default["an"] . "_" . $mois, $d[1] . " Visiteurs") : "Vous n'avez eu aucun visiteur.");
        }
        echo html_structures::ul($li);
    }

    /**
     * Retourne les statistiques sur un visiteur unique
     * @param array $default $this->check_form()
     * @return array Data du graphique de $this->get_uniques_visitors();
     */
    private function get_plot_uniques_visitors($default) {
        $data = [];
        for ($i = 0; $i < 12; $i++) {
            $date_debut = $default["an"] . "-";
            if ($i + 1 < 10) {
                $date_debut .= "0";
            }
            $date_debut .= $i + 1;
            $date_fin = $date_debut . "-31";
            $date_debut .= "-01";

            $req = application::$_bdd->fetch("select distinct visitor from stat_pages where date between :date_debut and :date_fin;", [":date_debut" => $date_debut, ":date_fin" => $date_fin]);
            $data[$i] = [$i, count($req)];
        }
        return $data;
    }

    /**
     * Affiche les statistiques d'activitès par heure
     * @param array $default $this->check_form()
     */
    private function get_activity_per_hours($default) {
        $data = [
            [
                "label" => "Pages visitées",
                "data" => $this->get_plot_activity_per_hours($default)
            ]
        ];
        $tricks = [
            [0, "00h - 01h"],
            [1, "01h - 02h"],
            [2, "02h - 03h"],
            [3, "03h - 04h"],
            [4, "04h - 05h"],
            [5, "05h - 06h"],
            [6, "06h - 07h"],
            [7, "07h - 08h"],
            [8, "08h - 09h"],
            [9, "09h - 10h"],
            [10, "10h - 11h"],
            [11, "11h - 12h"],
            [12, "12h - 13h"],
            [13, "13h - 14h"],
            [14, "14h - 15h"],
            [15, "15h - 16h"],
            [16, "16h - 17h"],
            [17, "17h - 18h"],
            [18, "18h - 19h"],
            [19, "19h - 20h"],
            [20, "20h - 21h"],
            [21, "21h - 22h"],
            [22, "22h - 23h"],
            [23, "23h - 24h"]
        ];

        (new graphique("stat_graph_activity_per_hours", ["width" => "100%", "height" => "300px"]))->line($data, $tricks, "stat_plot_activity_per_hours");

        $li = [];
        for ($i = 0; $i < 24; $i++) {
            $li[] = $tricks[$i][1] . " : " . $data[0]["data"][$i][1] . " pages visitèes";
        }
        echo html_structures::ul($li);
    }

    /**
     * Retourne les donnèes du graphique de $this->get_activity_per_hours();
     * @param array $default $this->check_form()
     * @return array Donnèes du graphique de $this->get_activity_per_hours();
     */
    private function get_plot_activity_per_hours($default) {
        $pages = stat_pages::get_table_array("date between :date_debut and :date_fin", [":date_debut" => $default["an"] . "-01-01", ":date_fin" => $default["an"] . "-12-31"]);
        $heures = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($pages as $page) {
            $heure = explode(" ", $page["date"]);
            $heure = ((int) strtr($heure[1], [":" => ""]));
            for ($i = 0; $i < 24; $i++) {
                if ($heure < (($i + 1) * 10000)) {
                    $heures[$i] = $heures[$i] + 1;
                    break;
                }
            }
        }
        $data = [];
        foreach ($heures as $key => $value) {
            $data[] = [$key, $value];
        }
        return $data;
    }

    /**
     * Affiche les statistiques sur les navigateurs des visiteurs
     * @param array $default $this->check_form()
     */
    private function get_browser($default) {
        $req = application::$_bdd->fetch("select distinct visitor from stat_pages where date between :date_debut and :date_fin", [":date_debut" => $default["an"] . "-01-01", ":date_fin" => $default["an"] . "-12-31"]);
        if (($total = count($req)) != 0) {
            $in = "id in(";
            foreach ($req as $v) {
                $in .= ((int)$v["visitor"]) . ",";
            }
            $in .= "__)";
            $in = strtr($in, [",__" => ""]);
            $data = [];
            foreach (application::$_bdd->fetch("select user_agent, count(*) as count from `stat_visitor`where " . $in . " group by user_agent;") as $key => $value) {
                $data[] = [$value["user_agent"], $value["count"], $value["count"] / $total * 100];
            }
            js::datatable();
            echo html_structures::table(["Navigateur", "Nombre d'utilisateur", "Pourcentage (%)"], $data, '', "datatable");
        }
    }

    /**
     * Fonction appelèe par $this->get_stat(), à ne pas utiliser ! <br />
     * Affiche les statistiques détaillées d'un mois
     */
    public function consult() {
        if (isset($_GET["date"])) {
            $date = explode("_", $_GET["date"]);
            sub_menu::add_active_tab("stat", $stat = time::convert_mois($date[1]) . " " . $date[0]);
            html5::before_title("Statistiques " . $stat . " -");
            $date_debut = $date[0] . "-" . $date[1];
            $date_fin = $date_debut . "-31";
            $date_debut .= "-01";
            $req = application::$_bdd->fetch("select distinct visitor from stat_pages where date between :date_debut and :date_fin", [":date_debut" => $date_debut, ":date_fin" => $date_fin]);
            if (count($req) != 0) {
                $in = "id in(";
                foreach ($req as $v) {
                    $in .= ((int)$v["visitor"]) . ",";
                }
                $in .= "__);";
                $in = strtr($in, [",__" => ""]);
                $visitors = stat_visitor::get_table_ordored_array($in); //application::$_bdd->fetch("select imat from stat_visitor " . $in);
                $nav = stat_pages::get_table_array("date between :date_debut and :date_fin", [":date_debut" => $date_debut, ":date_fin" => $date_fin]);
                $req = application::$_bdd->fetch("select page, count(*) as count from stat_pages where date between :date_debut and :date_fin group by page", [":date_debut" => $date_debut, ":date_fin" => $date_fin]);
                $data = [];
                $titles = [];
                foreach ($req as $page) {
                    $titles[$page["page"]] = ["title" => $this->get_real_title_from_url($page["page"]), "count" => $page["count"]];
                    $data[] = [$titles[$page["page"]]["title"], $page["page"], $page["count"]];
                }
                js::datatable();
                echo tags::tag("h2", ["class" => "text-center"], tags::tag("small", [], "Pages visitèes")) .
                html_structures::table(["Page", "URL", "Visites"], $data, '', "datatable") . html_structures::hr() .
                tags::tag("h2", ["class" => "text-center"], tags::tag("small", [], "Historique des visiteurs"));
                foreach ($nav as $link) {
                    $visitors[$link["visitor"]]["nav"][] = ["date" => $link["date"], "page" => strtr($link["page"], [$_SERVER["HTTP_HOST"] => "localhost"]), "title" => $titles[$link["page"]]["title"]];
                }
                js::accordion();
                $accordion = "";
                foreach ($visitors as $visitor) {
                    $a = "";
                    foreach ($visitor["nav"] as $nav) {
                        $date = explode(" ", $nav["date"]);
                        $date = time::convert_date($date[0]) . " " . $date[1];
                        $a .= html_structures::a_link($nav["page"], $date . " - " . $nav["title"], "", $nav["page"]) . tags::tag("br");
                    }
                    $accordion .= tags::tag("h3", [], $visitor["imat"]) . tags::tag("div", [], tags::tag("p", [], $a));
                }
                echo tags::tag("div", ["id" => "accordion"], $accordion);
            }
        }
    }

    /**
     * Affiche le formulaire de sélection d'années
     * @param array $default $this->check_form()
     */
    private function form($default) {
        $form = new form();
        $y = date("Y");
        $option = [];
        for ($i = 5; $i >= 0; $i--) {
            $k = $y - $i;
            $option[] = [$k, $k, ($k == $default["an"])];
        }
        $form->select("Annèe", "stat_an", $option);
        $form->submit("btn-primary", "Voir");
        echo $form->render();
    }

    /**
     * Retourne la valeur par défault à utiliser dans les statistiques
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
     * Récupére le title de l'url passè en paramètre 
     * @param string $url URL
     * @return string Title de l'url
     */
    private function get_real_title_from_url($url) {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTMLFile($url);
        $title = $dom->getElementsByTagName("title")->item(0)->textContent;
        libxml_clear_errors();
        return $title;
    }
}
