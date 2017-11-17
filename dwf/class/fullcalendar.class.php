<?php

/**
 * Cette classe permet d'afficher un Fullcalendar 
 * (https://fullcalendar.io/)
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class fullcalendar {

    /**
     * Permet de vérifier que la librairie fullcalendar a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie fullcalendar a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Permet de vérifier que la librairie fullcalendar-gcal a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie fullcalendar-gcal a bien été appelée qu'une fois.
     */
    private static $_gcal_called = false;

    /**
     * id CSS du Fullcalendar
     * @var string $id id CSS du Fullcalendar
     */
    private $_id;

    /**
     * Paramètres par défaut du Fullcalendar
     * @var array Paramètres par défaut du Fullcalendar 
     */
    private $_params = array(
        "header" => "{left: 'prev,next today', center: 'title', right: 'month,agendaWeek,agendaDay,timelineDay'}",
        "lang" => '"fr"',
    );

    /**
     * Créé un Fullcalendar
     * 
     * @param string $id id CSS du Fullcalendar
     * @param array $params Surcharge les paramètres à appliquer au Fullcalendar ( laissez par defaut ou voir la doc ...)
     */
    public function __construct($id = "fullcalendar", $params = array()) {
        $datas = array(
            "fullcalendar_resource" => array(
                array("id", "int", true),
                array("title", "string", false),
                array("eventColor", "string", false)
            ),
            "fullcalendar_event" => array(
                array("id", "int", true),
                array("title", "string", false),
                array("start", "string", false),
                array("end", "string", false),
                array("url", "string", false),
                array("resourceId", "string", false),
            ),
        );
        foreach ($datas as $table => $data) {
            new entity_generator($data, $table, false, true);
        }
        if (!self::$_called) {
            echo html_structures::link_in_body('../commun/src/js/fullcalendar/fullcalendar.min.css');
            echo html_structures::link_in_body('../commun/src/js/fullcalendar/scheduler/scheduler.min.css');
            echo html_structures::link_in_body('../commun/src/js/fullcalendar/style.css');
            ?>
            <script src='../commun/src/js/fullcalendar/lib/moment.min.js'></script>
            <script src='../commun/src/js/fullcalendar/fullcalendar.min.js'></script>
            <script src='../commun/src/js/fullcalendar/scheduler/scheduler.min.js'></script> 
            <script src='../commun/src/js/fullcalendar/locale-all.js'></script>
            <?php
            self::$_called = true;
        }
        foreach ($params as $key => $value) {
            $this->_params[$key] = $value;
        }
        ?>
        <script src='../commun/src/js/fullcalendar/locale/<?php echo strtr($this->_params["lang"], array('"' => '')); ?>.js'></script>
        <?php
        $this->_id = $id;
    }

    /**
     * Affiche la div conteneur et le rendu du Fullcalendar
     */
    private function render() {
        ?>
        <div id="<?php echo $this->_id; ?>"></div>
        <?php
    }

    /**
     * Interconnecte le Fullcalendar à un ou plusieurs compte/groupe google
     * @param string $api API Google
     * @param array $sourses array("abcd1234@group.calendar.google.com",...)
     */
    public function gcal($api, $sourses) {
        if (self::$_gcal_called) {
            ?>
            <script src='../commun/src/js/fullcalendar/gcal.js'></script>
            <?php
            self::$_gcal_called = true;
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?php echo $this->_id; ?>").fullCalendar({
                defaultDate: '<?php echo date("Y-m-d"); ?>',
                        weekNumbers: true,
        <?php
        foreach ($this->_params as $key => $value) {
            echo $key . ': ' . $value . ', ';
        }
        echo "googleCalendarApiKey: '" . $api . "',";
        ?>
                eventSources: [
        <?php
        foreach ($sourses as $s) {
            echo "{googleCalendarId: '" . $s . "'},";
        }
        ?>
                ]
                });
                }
                );</script>
        <?php
        $this->render();
    }

    /**
     * Ajoute des évènements et des ressources au Fullcalendar
     * @param array $data array(
     *                        array("title"=>"titre",
     *                              "start"=>"yyyy-mm-ddThh:mm:ss",
     *                              ["end"=>"yyyy-mm-ddThh:mm:ss",]
     *                              ["url"=>"https://guckduckgo.com",]
     *                              ["resourceId"=>"",]
     *                        ),
     *                        ...)
     * @param array $resource array(
     *                             array(
     *                                 "id"=>"",
     *                                 "title"=>"",
     *                                 "eventColor"=>"",
     *                             )
     *                         )
     */
    public function events($data, $resource = array()) {
        ?>
        <script type="text/javascript">
                $(document).ready(function () {
                    $("#<?php echo $this->_id; ?>").fullCalendar({
                    defaultDate: '<?php echo date("Y-m-d"); ?>',
                            weekNumbers: true,
        <?php
        foreach ($this->_params as $key => $value) {
            echo $key . ': ' . $value . ', ';
        }
        ?>
                    events: [
        <?php
        foreach ($data as $d) {
            echo "{";
            foreach ($d as $key => $value) {
                echo $key . " : '" . strtr($value, array("'" => "\'", "\\" => "\\\\")) . "',";
            }
            echo "},";
        }
        ?>
                    ],
        <?php
        if (count($resource) > 0) {
            ?>
                        resources:[
            <?php
            foreach ($resource as $r) {
                echo "{";
                foreach ($r as $key => $value) {
                    echo $key . " : '" . strtr($value, array("'" => "\'", "\\" => "\\\\")) . "',";
                }
                echo "},";
            }
            ?>
                        ],
            <?php
        }
        ?>
                });
            });

        </script>
        <?php
        $this->render();
    }

    /**
     * Gère les sous-routes et affiche l'administration du Fullcalendar
     * @param array $keys_route_sup cf \sub_menu
     */
    public function admin($keys_route_sup = array("page")) {
        new sub_menu($this, array(
            array("fc" => "res", "title" => "Agendas", "text" => "Agendas"),
            array("fc" => "event", "title" => "Evenement", "text" => "Evenement")
                ), "fc", "res", $keys_route_sup);
    }

    /**
     * Fonction appelée par admin()
     * Administre les ressources du Fullcalendar
     */
    public function res() {
        $url = application::get_url(array("action", "id"));
        if (isset($_GET["action"])) {
            if ($_GET["action"] == "supp") {
                $id = application::$_bdd->protect_var((int) $_GET["id"]);
                $resource = fullcalendar_resource::get_table_array("id='" . $id . "'");
                ?>
                <p class="text-center">ÃŠTES VOUS SUR DE VOULOIR SUPPRIMER CETTE Ã‰LÃ‰MENT :</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Couleur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $resource[0]["title"]; ?></td>
                            <td style="background-color: <?php echo $resource[0]["eventColor"]; ?>"> <?php echo $resource[0]["eventColor"]; ?> </td>
                        </tr>
                    </tbody>
                </table>
                <?php
                if (isset($_POST["admin_form_supp"])) {
                    application::$_bdd->fetch("delete from fullcalendar_resource where id=" . $id . ";");
                    application::$_bdd->fetch("delete from fullcalendar_event where resourceId=" . $id . ";");
                    js::alert("L'agenda a bien été supprimé");
                    js::redir(strtr($url, $from = array("&amp;" => "&")));
                } else {
                    form::new_form("form-inline");
                    form::hidden("admin_form_supp", "1");
                    form::submit("btn-danger", "Oui");
                    echo html_structures::a_link($url, "Non", "btn btn-default");
                    form::close_form();
                }
            }
            if ($_GET["action"] == "modif") {
                $id = application::$_bdd->protect_var((int) $_GET["id"]);
                $resource = fullcalendar_resource::get_from_id($id);
                if (isset($_POST["title"])) {
                    $resource->set_title($_POST["title"]);
                    $resource->set_eventColor($_POST["eventColor"]);
                    unset($_GET["action"]);
                    unset($_GET["id"]);
                    js::alert("L'agenda a bien été modifié");
                    js::redir($url);
                } else {
                    form::new_form();
                    form::input("Titre", "title", "text", $resource->get_title());
                    form::input("Couleur", "eventColor", "color", $resource->get_eventColor());
                    form::submit("btn-default", "Modifier");
                    form::close_form();
                }
            }
        } else {
            if (isset($_POST["title"])) {
                fullcalendar_resource::ajout($_POST["title"], $_POST["eventColor"]);
                js::alert("L'agenda a bien été ajouté");
                js::redir("");
            } else {
                $resource = fullcalendar_resource::get_table_array();
                ?>
                <table class="table" id="datatable">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Couleur</th>
                            <th>Modifier / Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($resource as $r) {
                            ?>
                            <tr>
                                <td><?php echo $r["title"]; ?></td>
                                <td style="background-color: <?php echo $r["eventColor"]; ?>"> <?php echo $r["eventColor"]; ?> </td>
                                <td><?php
                                    echo html_structures::a_link($url . "action=modif&amp;id=" . $r["id"], html_structures::glyphicon("edit", "Modifier") . " Modifier", "btn btn-xs btn-default") .
                                    html_structures::a_link($url . "action=supp&amp;id=" . $r["id"], html_structures::glyphicon("remove", "Supprimer") . " Supprimer", "btn btn-xs btn-danger navbar-right");
                                    ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                form::new_form();
                form::input("Titre", "title");
                form::input("Couleur", "eventColor", "color", "#0000ff");
                form::submit("btn-default", "Ajouter");
                form::close_form();
            }
        }
    }

    /**
     * Fonction appelée par admin()
     * Administre les évènements du Fullcalendar
     */
    public function event() {
        $url = application::get_url(array("action", "id"));
        if (isset($_GET["action"])) {
            if ($_GET["action"] == "supp") {
                $id = application::$_bdd->protect_var((int) $_GET["id"]);
                $event = fullcalendar_event::get_table_array("id='" . $id . "'");
                ?>
                <p class="text-center">ÃŠTES VOUS SUR DE VOULOIR SUPPRIMER CETTE Ã‰LÃ‰MENT :</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Début</th>
                            <th>Fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $event[0]["title"]; ?></td>
                            <td> <?php echo $this->convert_date($event[0]["start"]); ?> </td>
                            <td> <?php echo $this->convert_date($event[0]["end"]); ?> </td>
                        </tr>
                    </tbody>
                </table>
                <?php
                if (isset($_POST["admin_form_supp"])) {
                    application::$_bdd->fetch("delete from fullcalendar_event where id=" . $id . ";");
                    js::alert("L'evenement a bien été supprimé");
                    js::redir(strtr($url, $from = array("&amp;" => "&")));
                } else {
                    form::new_form("form-inline");
                    form::hidden("admin_form_supp", "1");
                    form::submit("btn-danger", "Oui");
                    echo html_structures::a_link($url, "Non", "btn btn-default");
                    form::close_form();
                }
            }
            if ($_GET["action"] == "modif") {
                $id = application::$_bdd->protect_var((int) $_GET["id"]);
                $event = fullcalendar_event::get_from_id($id);
                if (isset($_POST["title"])) {
                    $event->set_title($_POST["title"]);
                    $event->set_start(form::get_datetimepicker_us("start"));
                    $event->set_end(form::get_datetimepicker_us("end"));
                    $event->set_url($_POST["url"]);
                    $event->set_resourceId($_POST["resourceId"]);
                    js::alert("L'evenement a bien été modifié");
                    js::redir($url);
                } else {
                    $option = array(
                        array("default", "- défaut -",)
                    );
                    foreach (fullcalendar_resource::get_table_array() as $res) {
                        $option[] = array($res["id"], $res["title"], ($res["id"] == $event->get_resourceId()));
                    }
                    form::new_form();
                    form::input("Titre", "title", "text", $event->get_title());
                    form::datetimepicker("Debut", "start", $this->convert_date($event->get_start()));
                    form::datetimepicker("Fin", "end", $this->convert_date($event->get_end()));
                    form::input("URL (facultative)", "url", "url", $event->get_url(), false);
                    form::select("Agenda", "resourceId", $option);
                    form::submit("btn-default", "Modifier");
                    form::close_form();
                    echo html_structures::a_link($url . "action=supp&id=" . $event->get_id(), "Supprimer", "btn btn-danger");
                }
            }
        } else {
            $data = array();
            $option = array(
                array("default", "- défaut -",)
            );
            $resources = array(
                array("id" => "default", "title" => "- defaut -")
            );
            foreach (fullcalendar_resource::get_table_array() as $res) {
                $option[] = array($res["id"], $res["title"]);
                $resources[] = array("id" => $res["id"], "title" => $res["title"], "eventColor" => $res["eventColor"]);
            }
            foreach (fullcalendar_event::get_table_array() as $event) {
                if (!empty($event["url"])) {
                    $event["title"] .= " [" . $event["url"] . "]";
                }
                $event["url"] = strtr($url . "action=modif&amp;id=" . $event["id"], array("&amp;" => "&"));
                $data[] = $event;
            }
            $this->events($data, $resources);
            echo html_structures::hr();
            form::new_form();
            form::input("Titre", "title");
            form::datetimepicker("Debut", "start");
            form::datetimepicker("Fin", "end");
            form::input("URL (facultative)", "url", "url", null, false);
            form::select("Agenda", "resourceId", $option);
            form::submit("btn-default", "Ajouter");
            form::close_form();
            if (isset($_POST["title"])) {
                fullcalendar_event::ajout($_POST["title"], form::get_datetimepicker_us("start"), form::get_datetimepicker_us("end"), $_POST["url"], $_POST["resourceId"]);
                js::alert("L'evenement a bien été ajouté");
                js::redir("");
            }
        }
    }

    /**
     * Convertit une date au format US (yyyy-mm-dd) au format FR (dd/mm/yyyy)
     * @param string $date date au format US
     * @return string date au format fr
     */
    private function convert_date($date) {
        $time = explode(" ", $date);
        $us = explode("-", $time[0]);
        if (!isset($us[0]) or ! isset($us[1]) or ! isset($us[2])) {
            return false;
        }
        return $us[2] . "/" . $us[1] . "/" . $us[0] . " " . $time[1];
    }

}
