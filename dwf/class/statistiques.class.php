<?php

/**
 * Cette classe permet de recueillir et d'afficher des statistiques liées à l'activité des utilisateurs
 * (limité a 5 ans d'enregistrement)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class statistiques extends singleton {

    /**
     * Indique si la page courante doit être enregistrée ou non
     * @var boolean Indique si la page courante doit être enregistrée ou non
     */
    static private $_enable = false;

    /**
     * Cette classe permet de recueillir et d'afficher des statistiques liées à l'activité des utilisateurs
     * (limité a 5 ans d'enregistrement) 
     */
    public function __construct() {
        if (class_exists("config") and isset(config::$_statistiques) and config::$_statistiques) {
            self::$_enable = true;
        }
        if (self::$_enable) {
            entity_generator::generate([
                "stat_country" => [
                    ["id", "int", true],
                    ["code", "string", false],
                    ["name", "string", false],
                ],
                "stat_browser" => [
                    ["id", "int", true],
                    ["agent", "string", false],
                ],
                "stat_isp" => [
                    ["id", "int", true],
                    ["name", "string", false],
                ],
                "stat_user" => [
                    ["id", "int", true],
                    ["ip", "string", false],
                    ["isp", "stat_isp", false],
                    ["country", "stat_country", false],
                    ["browser", "stat_browser", false],
                ],
                "stat_page" => [
                    ["id", "int", true],
                    ["url", "string", false],
                    ["title", "string", false],
                ],
                "stat_visite" => [
                    ["id", "int", true],
                    ["timestamp", "int", false],
                    ["user", "stat_user", false],
                    ["page", "stat_page", false],
                ]
            ]);
            if (stat_country::get_count() == 0) {
                stat_country::ajout("5N", "Local");
                stat_country::ajout("UN", "Unknow");
                stat_isp::ajout("Local");
                stat_isp::ajout("Unknow");
            }
            bdd::get_instance()->query("delete from stat_visite where timestamp<:t", [":t" => (time() - 157680000)]);
        }
    }

    /**
     * Enregistre la visite de l'utilisateur si config::_statistiques=true;
     * Cette fonction est automatiquement appelé dans html5::before_render_task() 
     * Inutile de l'appeler davantage.
     * Si vous désirez desactiver l'enregistrement de statistiques sur une page 
     * (comme l'interface d'administration par exemple)
     * utilisez :
     * statistiques::get_instance()->disable();
     * dans les pages concerné
     */
    public function add_visit() {
        if (self::$_enable) {
            $browser = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "Unknow");
            if (stat_browser::get_count("agent=:agent", [":agent" => $browser]) == 0) {
                stat_browser::ajout($browser);
            }
            $browser = stat_browser::get_collection("agent=:agent", [":agent" => $browser])[0];
            if (stat_page::get_count("url=:url", [":url" => $url = application::get_loc()]) == 0) {
                stat_page::ajout($url, html5::$_real_title);
            }
            $page = stat_page::get_collection("url=:url", [":url" => application::get_loc()])[0];
            $ip = $_SERVER["REMOTE_ADDR"];
            if (stat_user::get_count("ip=:ip", [":ip" => $ip]) == 0) {
                if ($this->is_private_IP($ip)) {
                    $isp = stat_country::get_collection("name='Local'")[0];
                    $country = stat_country::get_collection("code='5N'")[0];
                    stat_user::ajout($ip, $isp->get_id(), $country->get_id(), $browser->get_id());
                } else {
                    $ip_api = ip_api::get_instance()->json($ip);
                    if ($ip_api and $ip_api["status"] == "success") {
                        if (stat_isp::get_count("name=:name", [":name" => $ip_api["isp"]]) == 0) {
                            stat_isp::ajout($ip_api["isp"]);
                        }
                        if (stat_country::get_count("code=:code", [":code" => $ip_api["countryCode"]]) == 0) {
                            stat_country::ajout($ip_api["countryCode"], $ip_api["country"]);
                        }
                        $isp = stat_isp::get_collection("name=:name", [":name" => $ip_api["isp"]])[0];
                        $country = stat_country::get_collection("code=:code", [":code" => $ip_api["countryCode"]])[0];
                        stat_user::ajout($ip, $isp->get_id(), $country->get_id(), $browser->get_id());
                    } else {
                        $isp = stat_isp::get_collection("name='Unknow'")[0];
                        $country = stat_country::get_collection("code='UN'")[0];
                        stat_user::ajout($ip, $isp->get_id(), $country->get_id(), $browser->get_id());
                        if (stat_user::get_count("country=:id", [":id" => $country->get_id()]) > 90) {
                            $ips_assoc = [];
                            foreach ($users = stat_user::get_collection("country=:id order by id desc limit 99", [":id" => $country->get_id()]) as $user) {
                                $ips_assoc[$user->get_ip()] = false;
                            }
                            foreach (ip_api::get_instance()->batch(array_keys($ips_assoc)) as $ip_api) {
                                if ($ip_api["status"] = "success") {
                                    $ips_assoc[$ip_api["query"]] = ["isp" => $ip_api["isp"], "country" => $ip_api["countryCode"], "country" => $ip_api["countryCode"]];
                                }
                            }
                            foreach ($users as $user) {
                                if ($ips_assoc[$user->get_ip()]) {
                                    $ip_api = $ips_assoc[$user->get_ip()];
                                    if (stat_isp::get_count("name=:name", [":name" => $ip_api["isp"]]) == 0) {
                                        stat_isp::ajout($ip_api["isp"]);
                                    }
                                    if (stat_country::get_count("code=:code", [":code" => $ip_api["countryCode"]]) == 0) {
                                        stat_country::ajout($ip_api["countryCode"], $ip_api["country"]);
                                    }
                                    $isp = stat_isp::get_collection("name=:name", [":name" => $ip_api["isp"]])[0];
                                    $country = stat_country::get_collection("code=:code", [":code" => $ip_api["countryCode"]])[0];
                                    $user->set_isp($isp->get_id());
                                    $user->set_country($country->get_id());
                                }
                            }
                        }
                    }
                }
            }
            $users = stat_user::get_collection("ip=:ip", [":ip" => $ip]);
            $find = false;
            foreach ($users as $user) {
                if ($user->get_browser()->get_id() == $browser->get_id()) {
                    $find = true;
                    break;
                }
            }
            if (!$find) {
                stat_user::ajout($ip, $user->get_country()->get_id(), $browser->get_id());
                $user = stat_user::get_collection("ip=:ip and browser=:browser", [":ip" => $ip, ":browser" => $browser->get_id()])[0];
            }
            $last_visite = stat_visite::get_collection("user=:user order by timestamp desc limit 0, 1", [":user" => $user->get_id()]);
            if ($last_visite) {
                $last_visite = $last_visite[0];
                if ($last_visite->get_page()->get_id() != $page->get_id()) {
                    stat_visite::ajout(time(), $user->get_id(), $page->get_id());
                }
            } else {
                stat_visite::ajout(time(), $user->get_id(), $page->get_id());
            }
        }
    }

    /**
     * Désactive l'enregistrement de statistiques pour la page courante 
     */
    public function disable() {
        if (self::$_enable) {
            self::$_enable = false;
        }
    }

    /**
     * Affiche l'interface pour consulter les statistiques
     * Requiert que config::$_statistiques soit défini à true
     * La page affichant les statistiques n'est pas enregistrée dans les statistiques !
     */
    public function html() {
        if (self::$_enable) {
            $this->disable();
            ?>
            <div class="row">
                <div class="col-2">
                    <?php
                    $ul = [];
                    $year = (int) date("Y");
                    if (!isset($_GET["y"])) {
                        $_GET["y"] = $year;
                    }
                    for ($i = 0;
                            $i < 5;
                            $i++) {
                        $y = $year - $i;
                        $ul[] = html_structures::a_link(application::get_url(["y", "m", "d"]) . "y={$y}", $y);
                        if ($_GET["y"] == $y) {
                            $ul_months = [];
                            foreach (time::get_mois() as $km => $m) {
                                $ul_months[] = html_structures::a_link(application::get_url(["y", "m", "d"]) . "y={$y}&m={$km}", $m);
                                if (isset($_GET["m"]) and $_GET["m"] == $km) {
                                    $ul_days = [];
                                    foreach (range(1, time::get_nb_jour($km, $y)) as $d) {
                                        $ul_days[] = html_structures::a_link(application::get_url(["y", "m", "d"]) . "y={$y}&m={$km}&d={$d}", $d);
                                    }
                                    $ul_months[] = $ul_days;
                                }
                            }
                            $ul[] = $ul_months;
                        }
                    }
                    echo html_structures::ul($ul, "list-group stat_date");
                    ?>
                    <script>
                        $(document).ready(function () {
                            $(".list-group ul").addClass("list-group");
                            $(".list-group li").addClass("list-group-item");
                            let urlParams = new URLSearchParams(window.location.search);
                            let y = urlParams.get('y');
                            let m = urlParams.get('m');
                            let d = urlParams.get('d');
                            let mois = [
                                "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                                "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
                            ];
                            y = y ? y : new Date().getFullYear();
                            $(".stat_date>li.list-group-item:contains('" + y + "')").addClass("active");
                            if (m) {
                                $(".stat_date>li>ul.list-group>li.list-group-item:contains('" + mois[parseInt(m) - 1] + "')").addClass("active");
                            }
                            if (d) {
                                $(".stat_date>li>ul.list-group>li>ul.list-group>li.list-group-item:contains('" + d + "')").addClass("active");
                            }
                            $(".stat_date .active a").addClass("link-light");
                        })
                    </script>
                </div>
                <div class="col-10">
                    <?php
                    list($start, $end) = $this->get_period();
                    if (isset($_GET["view"])) {
                        switch ($_GET["view"]) {
                            case "user":
                                $this->view_user($start, $end, $_GET["user"]);
                                break;
                            case "general":
                            default:
                                $this->view_general($start, $end);
                                break;
                        }
                    } else {
                        $this->view_general($start, $end);
                    }
                    ?>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Retourne si une IP est privé
     * @param string $ip Ip v4 ou v6 a évaluer
     * @return boolean true si l'ip est privé, false si publique
     */
    private function is_private_IP($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $this->is_private_IPv4($ip);
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $this->is_private_IPv6($ip);
        }
    }

    /**
     * Retourne si une IPv4 est privé
     * @param string $ip Ip v4
     * @return boolean true si l'ip est privé, false si publique
     */
    private function is_private_IPv4($ip) {
        $ipBinary = inet_pton($ip);
        $privateRanges = [
            ['start' => inet_pton('10.0.0.0'), 'end' => inet_pton('10.255.255.255')],
            ['start' => inet_pton('127.0.0.0'), 'end' => inet_pton('127.255.255.255')],
            ['start' => inet_pton('172.16.0.0'), 'end' => inet_pton('172.31.255.255')],
            ['start' => inet_pton('192.168.0.0'), 'end' => inet_pton('192.168.255.255')]
        ];
        foreach ($privateRanges as $range) {
            if ($ipBinary >= $range['start'] && $ipBinary <= $range['end']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retourne si une IPv6 est privé
     * @param string $ip Ip v6
     * @return boolean true si l'ip est privé, false si publique
     */
    private function is_private_IPv6($ip) {
        if ($ip == "::1") {
            return true;
        }
        $ipBinary = inet_pton($ip);
        $privateRange = ['start' => inet_pton('fc00::'), 'end' => inet_pton('fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff')];
        if (memcmp($ipBinary, $privateRange['start'], strlen($privateRange['start'])) >= 0 &&
                memcmp($ipBinary, $privateRange['end'], strlen($privateRange['end'])) <= 0) {
            return true;
        }
        return false;
    }

    /**
     * Retourne le debut et la fin de la periode définis par les variables GET y m et d
     * @return array Debut et la fin de la periode
     */
    private function get_period() {
        if (isset($_GET['y'])) {
            $year = intval($_GET['y']);
        } else {
            $year = date("Y");
        }
        if (isset($_GET['m']) && isset($_GET['d'])) {
            $month = intval($_GET['m']);
            $day = intval($_GET['d']);
            $start = mktime(0, 0, 0, $month, $day, $year);
            $end = mktime(0, 0, 0, $month, $day + 1, $year);
        } elseif (isset($_GET['m'])) {
            $month = intval($_GET['m']);
            $start = mktime(0, 0, 0, $month, 1, $year);
            $end = mktime(0, 0, 0, $month + 1, 1, $year);
        } else {
            if ($year == date("Y")) {
                $end = time();
            } else {
                $end = mktime(0, 0, 0, 1, 1, $year + 1);
            }
            $start = mktime(0, 0, 0, 1, 1, $year);
        }
        return[$start, $end];
    }

    /**
     * Retourne les paramètres GET de l'URL
     * @param string $url URL complete
     * @return string Paramètres GET de l'URL
     */
    private function get_params($url) {
        return ($url = parse_url($url, PHP_URL_QUERY) ? explode("?", $url)[1] : "");
    }

    /**
     * Vue général des statistiques pour une periode donné
     * @param int $start Timestamp de début de periode
     * @param int $end Timestamp de fin de periode
     */
    private function view_general($start, $end) {
        $visites = stat_visite::get_collection("timestamp between :start and :end", [":start" => $start, ":end" => $end]);
        $stat = [
            "pv" => count($visites), //pages vue
            "vu" => bdd::get_instance()->fetch("select COUNT(DISTINCT user) as count FROM stat_visite where timestamp between :start and :end", [":start" => $start, ":end" => $end])[0]["count"], //visiteur unique
            "isp" => [], //visites par isp (operateur)
            "pays" => [], //visites par pays
            "browser" => [], //visites par browsers
            "page" => [], //visites par pages
            "user" => [], //visites des utilisateurs
        ];
        foreach ($visites as $visite) {
            if (!isset($stat["isp"][$visite->get_user()->get_isp()->get_id()])) {
                $stat["isp"][$visite->get_user()->get_isp()->get_id()] = 0;
            }
            $stat["isp"][$visite->get_user()->get_country()->get_id()]++;
            if (!isset($stat["pays"][$visite->get_user()->get_country()->get_id()])) {
                $stat["pays"][$visite->get_user()->get_country()->get_id()] = 0;
            }
            $stat["pays"][$visite->get_user()->get_country()->get_id()]++;
            if (!isset($stat["browser"][$visite->get_user()->get_browser()->get_id()])) {
                $stat["browser"][$visite->get_user()->get_browser()->get_id()] = 0;
            }
            $stat["browser"][$visite->get_user()->get_browser()->get_id()]++;

            if (!isset($stat["page"][$visite->get_page()->get_id()])) {
                $stat["page"][$visite->get_page()->get_id()] = 0;
            }
            $stat["page"][$visite->get_page()->get_id()]++;
            $stat["user"][] = [
                date("Y-m-d H:i:s", $visite->get_timestamp()),
                html_structures::a_link(application::get_url() . "view=user&user={$visite->get_user()->get_id()}",
                        twemojiFlags::get($visite->get_user()->get_country()->get_code()) .
                        tags::tag("span", ["title" => "ISP : {$visite->get_user()->get_isp()->get_name()}"], $visite->get_user()->get_ip()) .
                        " ({$visite->get_user()->get_browser()->get_agent()})"
                ),
                html_structures::a_link($visite->get_page()->get_url(), $visite->get_page()->get_title(), "", "", true)
            ];
        }
        foreach (["isp", "pays", "browser", "page"] as $key) {
            arsort($stat[$key]);
        }
        ?>
        <div>
            <h2 class="text-center">
                Statistiques Général <br> 
                <small>Du <?= date("d/m/Y H:i", $start) ?> au <?= date("d/m/Y H:i", $end - 1) ?></small>
            </h2>
        </div>
        <hr>
        <div class="w-75 mx-auto my-3">
            <p class="text-center">
                <strong>Vititeurs uniques : </strong><?= $stat["vu"] ?>
                <br>
                <strong>Pages vues : </strong><?= $stat["pv"] ?>
            </p>
        </div>
        <hr>
        <div class="w-75 mx-auto my-3">
            <h3 class="text-center">Pages</h3>
            <?php
            $data = [];
            $sum = array_sum($stat["page"]);
            $i = 1;
            foreach ($stat["page"] as $id => $nb) {
                $page = stat_page::get_from_id($id);
                $data[] = [
                    $i++,
                    html_structures::a_link($page->get_url(), $page->get_title(), "", $this->get_params($page->get_url()), true),
                    $nb,
                    number_format($nb / $sum * 100, 2)
                ];
            }
            echo html_structures::table(["#", "Page", "Vues", "%"], $data);
            ?>
        </div>
        <hr>
        <div class="w-75 mx-auto my-3">
            <h3 class="text-center">Pays</h3>
            <?php
            $data = [];
            $sum = array_sum($stat["pays"]);
            $i = 1;
            foreach ($stat["pays"] as $id => $nb) {
                $pays = stat_country::get_from_id($id);
                $data[] = [
                    $i++,
                    twemojiFlags::get($pays->get_code()) . " {$pays->get_name()}",
                    $nb,
                    number_format($nb / $sum * 100, 2)
                ];
            }
            echo html_structures::table(["#", "Pays", "Vues", "%"], $data);
            ?>
        </div>
        <hr>
        <div class="w-75 mx-auto my-3">
            <h3 class="text-center">Agents <br>
                <small>(Navigateurs)</small></h3>
            <?php
            $data = [];
            $sum = array_sum($stat["browser"]);
            $i = 1;
            foreach ($stat["browser"] as $id => $nb) {
                $browser = stat_browser::get_from_id($id);
                $data[] = [
                    $i++,
                    $browser->get_agent(),
                    $nb,
                    number_format($nb / $sum * 100, 2)
                ];
            }
            echo html_structures::table(["#", "Agent", "Vues", "%"], $data);
            ?>
        </div>
        <hr>
        <div class="w-75 mx-auto my-3">
            <h3 class="text-center">ISP <br>
                <small>(Operateur)</small></h3>
            <?php
            $data = [];
            $sum = array_sum($stat["isp"]);
            $i = 1;
            foreach ($stat["isp"] as $id => $nb) {
                $isp = stat_isp::get_from_id($id);
                $data[] = [
                    $i++,
                    $isp->get_name(),
                    $nb,
                    number_format($nb / $sum * 100, 2)
                ];
            }
            echo html_structures::table(["#", "ISP", "Vues", "%"], $data);
            ?>
        </div>
        <hr>
        <div class="w-75 mx-auto my-3">
            <h3 class="text-center">Utilisateurs</h3>
            <?= html_structures::table(["date", "Utilisateur", "Page"], $stat["user"]) ?>
        </div>
        <?php
    }

    /**
     * Vue utilisateur des statistiques pour une periode et un utilisateur donné 
     * @param int $start Timestamp de début de periode
     * @param int $end Timestamp de fin de periode
     * @param int $user Id de l'utilisateur (stat_user)
     */
    private function view_user($start, $end, $user) {
        $user = stat_user::get_from_id($user);
        if ($user) {
            echo tags::tag("div", [], html_structures::a_link(application::get_url(["view", "user"]), html_structures::glyphicon("arrow-left") . "Retour aux statistiques général", "btn btn-outline-primary"));
            $visites = stat_visite::get_collection("user=:user and timestamp between :start and :end order by timestamp desc", [":user" => $user->get_id(), ":start" => $start, ":end" => $end]);
            ?>
            <hr>
            <div>
                <h2 class="text-center">
                    Statistiques Utilisateur <br>
                    <small>Du <?= date("d/m/Y H:i", $start) ?> au <?= date("d/m/Y H:i", $end - 1) ?></small>
                </h2>
                <hr>
                <div class="w-50 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            Utilisateur
                        </div>
                        <div class="card-body">
                            <ul>
                                <li><strong>IP : </strong><?= $user->get_ip() ?></li>
                                <li><strong>Pays : </strong><?= twemojiFlags::get($user->get_country()->get_code()) . " " . $user->get_country()->get_name() ?></li>
                                <li><strong>ISP : </strong><?= $user->get_isp()->get_name() ?></li>
                                <li><strong>Agent : </strong><?= $user->get_browser()->get_agent() ?></li>
                                <li><strong>Pages vues <small>(pour la periode indiquée)</small> : </strong><?= count($visites) ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            <?php
            $data = [];
            foreach ($visites as $visite) {
                $data[] = [
                    date("Y-m-d H:i:s", $visite->get_timestamp()),
                    html_structures::a_link($visite->get_page()->get_url(), $visite->get_page()->get_title(), "", "", true),
                    $this->get_params($visite->get_page()->get_url()),
                ];
            }
            echo html_structures::table(["Date", "Page", "GET"], $data);
        } else {
            js::redir(application::get_url(["view", "user"]));
        }
    }
}
