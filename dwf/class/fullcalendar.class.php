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
     * id CSS du Fullcalendar
     * @var string $id id CSS du Fullcalendar
     */
    private $_id;

    /**
     * Créé un Fullcalendar
     * @param string $id id CSS du Fullcalendar
     * @param array|null $events Liste des evenements a afficher, null pour utiliser un google agenda.
     * le tableau des efevement doit avoir la forme suivante :
     * array(
     *     array("title"=>"titre",
     *           "start"=>"yyyy-mm-ddThh:mm:ss",
     *           "end"=>"yyyy-mm-ddThh:mm:ss",
     *           "url"=>"https://duckduckgo.com" //optionel
     *     ),
     *     ...)
     * @param string $gapi Clé API pour le google agenda ($events doit être a null ou false)
     * @param string $gid Identifiant du google agenda ("abcd1234@group.calendar.google.com", $events doit être a null ou false)
     */
    public function __construct($id = "fullcalendar", $events = [], $gapi = "", $gid = "") {
        $this->_id = $id;
        if (!self::$_called) {
            entity_generator::generate([
                "fullcalendar_event" => [
                    ["id", "int", true],
                    ["calendar", "string", false],
                    ["title", "string", false],
                    ["start", "string", false],
                    ["end", "string", false],
                    ["url", "string", false]
                ]
            ]);
            echo html_structures::script("../commun/src/js/fullcalendar/lib/main.js");
            echo html_structures::script("../commun/src/js/fullcalendar/lib/locales-all.js");
            echo html_structures::link_in_body("../commun/src/js/fullcalendar/lib/main.css");
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                fc = new FullCalendar.Calendar(
                        document.getElementById("<?= $this->_id ?>"), {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    locale: 'fr',
        <?php
        if (is_array($events)) {
            ?>events:<?= json_encode($events) ?>, <?php
        } else {
            ?>googleCalendarApiKey: "<?= $gapi ?>",
                        events: {
                            googleCalendarId: "<?= $gid ?>"
                        },<?php
        }
        ?>
                    eventClick: function (info) {
                        info.jsEvent.preventDefault();
                        if (info.event.url) {
                            window.open(info.event.url, "_blank");
                        }
                    }
                }).render();
                $(".fc-license-message").remove();
            });
        </script>
        <?php
        echo tags::tag("div", ["id" => $this->_id], " ");
    }

    /**
     * Affiche l'administration du Fullcalendar
     */
    public static function admin() {
        if (!isset($_GET["action"])) {
            entity_generator::generate([
                "fullcalendar_event" => [
                    ["id", "int", true],
                    ["calendar", "string", false],
                    ["title", "string", false],
                    ["start", "string", false],
                    ["end", "string", false],
                    ["url", "string", false]
                ]
            ]);
            $_GET["action"] = "default";
        }
        switch ($_GET["action"]) {
            case "edit":
                self::admin_edit($_GET["id"]);
                break;
            case "del":
                self::admin_del($_GET["id"]);
                break;
            default:
                self::admin_default();
                break;
        }
    }

    /**
     * Affiche les événements en base de donnée et le formulaire d'ajout
     */
    private static function admin_default() {
        $events = [];
        foreach (fullcalendar_event::get_table_array() as $event) {
            $events[] = [
                $event["calendar"], $event["title"], $event["start"], $event["end"], $event["url"],
                tags::tag("a",
                        ["class" => "btn btn-secondary",
                            "href" => application::get_url(["action"]) .
                            "action=edit&id={$event["id"]}"],
                        "Modifier") .
                tags::tag("a",
                        ["class" => "btn btn-danger",
                            "href" => application::get_url(["action"]) .
                            "action=del&id={$event["id"]}"],
                        "Supprimer")
            ];
        }
        if (isset($_POST["calendar"])) {
            $start = form::get_datetimepicker_us("start");
            $end = form::get_datetimepicker_us("end");
            $url = ((isset($_POST["url"]) and!empty($_POST["url"])) ? $_POST["url"] : "");
            fullcalendar_event::ajout($_POST["calendar"], $_POST["title"], $start, $end, $url);
            js::alertify_alert_redir("L'evenement a bien été ajouté", "");
        } else {
            $form = new form();
            $form->open_fieldset("Ajouter un événement");
            $form->input("Agenda", "calendar", "text", "Default");
            $form->input("Titre", "title");
            $form->datetimepicker("Debut", "start");
            $form->datetimepicker("Fin", "end");
            $form->input("URL (falcutatif)", "url", "url", "", false);
            $form->submit("btn btn-primary", "Ajouter");
            $form->close_fieldset();
            js::datatable();
            echo html_structures::table(["Agenda", "Titre", "Début", "Fin", "URL", "Edit"], $events, "", "datatable") .
            html_structures::hr() . $form->render();
        }
    }

    /**
     * Afiche le formulaire d'edition d'un événement
     */
    private static function admin_edit($id) {
        $event = fullcalendar_event::get_from_id($id);
        if (isset($_POST["calendar"])) {
            $event->set_calendar($_POST["calendar"]);
            $event->set_title($_POST["title"]);
            $event->set_start(form::get_datetimepicker_us("start"));
            $event->set_end(form::get_datetimepicker_us("end"));
            $event->set_url((isset($_POST["url"]) and!empty($_POST["url"])) ? $_POST["url"] : "");
            js::alertify_alert_redir("L'evenement a bien été modifié", application::get_url(["action", "id"]));
        } else {
            $form = new form();
            $form->open_fieldset("Modifier un événement");
            $form->input("Agenda", "calendar", "text", $event->get_calendar());
            $form->input("Titre", "title", "text", $event->get_title());
            $start = explode(" ", $event->get_start());
            $start = time::date_us_to_fr($start[0]) . " {$start[1]}";
            $form->datetimepicker("Debut", "start", $start);
            $end = explode(" ", $event->get_end());
            $end = time::date_us_to_fr($end[0]) . " {$end[1]}";
            $form->datetimepicker("Fin", "end", $end);
            $form->input("URL (falcutatif)", "url", "url", $event->get_url(), false);
            $form->submit("btn btn-primary", "Modifier");
            $form->close_fieldset();
            echo $form->render();
        }
    }

    /**
     * Affiche la demande de confirmation pour la suppresion d'un événement
     */
    private static function admin_del($id) {
        $event = fullcalendar_event::get_from_id($id);
        ?><p>Estes vous sur de vouloir suprimer l'evenement : <br />
            <?= "{$event->get_title()} de {$event->get_start()} à {$event->get_end()} ?" ?>
        </p>
        <div class="row">
            <div class="col-6">
                <?php
                if (isset($_POST["del"])) {
                    $event->delete();
                    js::alertify_alert_redir("L'evenement a bien été supprimé", application::get_url(["action", "id"]));
                } else {
                    $form = new form();
                    $form->hidden("del", $event->get_id());
                    $form->submit("btn btn-danger", "Oui");
                    echo $form->render();
                }
                ?>
            </div>
            <div class="col-6">
                <p class="text-center">
                    <a class="btn btn-secondary" href="<?= application::get_url(["action", "id"]) ?>">Non</a>
                </p>
            </div>
        </div>
        <?php
    }

}
