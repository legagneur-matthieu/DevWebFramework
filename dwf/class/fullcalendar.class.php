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
     * Structure de l'entité fullcalendar_event (cf entity_generator)
     * @var array Structure de l'entité fullcalendar_event (cf entity_generator)
     */
    private static $_entity = [
        ["id", "int", true],
        ["calendar", "string", false],
        ["title", "string", false],
        ["start", "string", false],
        ["end", "string", false],
        ["url", "string", false],
        ["description", "string", false],
        ["location", "string", false],
        ["uid", "string", false]
    ];

    /**
     * id CSS du Fullcalendar
     * @var string $id id CSS du Fullcalendar
     */
    private $_id;

    /**
     * Evènements passés au constructeur
     * @var array $events Evènements passés au constructeur
     */
    private $_events;

    /**
     * Créé un Fullcalendar
     * @param string $id id CSS du Fullcalendar
     * @param array $events Liste des evenements à afficher (ex : fullcalendar_event::get_table_array()),
     * le tableau des évènements doit avoir la forme suivante :
     * array(
     *     array("title"=>"titre",
     *           "start"=>"yyyy-mm-ddThh:mm:ss",
     *           "end"=>"yyyy-mm-ddThh:mm:ss",
     *           "url"=>"https://duckduckgo.com", //optionel
     *           "allDay"=>true, //optionel, évènement sur la journée entière ("start" au format "yyyy-mm-dd" également accepté)
     *           "description"=>"texte", //optionel, affiché au survol et utilisé par l'export ICS
     *           "location"=>"lieu", //optionel, affiché au survol et utilisé par l'export ICS
     *           "uid"=>"abc@exemple.fr", //optionel, UID exporté en ICS (sinon "id@hôte")
     *           "id"=>1 //optionel, identifiant stable utilisé comme UID par l'export ICS en l'absence de "uid"
     *     ),
     *     ...)
     * $id à null : instance sans affichage (utilisée en interne par fullcalendar::admin())
     */
    public function __construct($id = "fullcalendar", $events = []) {
        $this->_id = $id;
        $this->_events = (is_array($events) ? $events : []);
        if ($id === null) {
            return;
        }
        if (!self::$_called) {
            self::$_called = true;
            entity_generator::generate(["fullcalendar_event" => self::$_entity]);
            echo html_structures::script("../commun/src/js/fullcalendar/index.global.min.js");
            echo html_structures::script("../commun/src/js/fullcalendar/locales-all.global.js");
        }
        ?>
        <script type="text/javascript" nonce="<?= csp::get_nonce() ?>">
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
                    events: <?= json_encode($this->_events, JSON_HEX_TAG | JSON_HEX_AMP) ?>,
                    eventDidMount: function (info) {
                        var tooltip = [info.event.extendedProps.location, info.event.extendedProps.description].filter(Boolean).join("\n");
                        if (tooltip) {
                            info.el.title = tooltip;
                        }
                    },
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
     * Retourne le bouton d'export de l'agenda au format iCalendar (.ics, RFC 5545).
     * Le bouton n'est pas affiché par défaut : echo $fc->export_btn("Exporter l'agenda");
     * Les évènements exportés sont ceux passés au constructeur.
     * Le fichier est servi par ../commun/printer.php, comme les exports PDF/CSV de la classe printer.
     * @param string $text Texte du bouton
     * @param string $filename Nom du fichier exporté (caractères spéciaux remplacés par "_", extension .ics ajoutée si absente)
     * @return string Formulaire d'export
     */
    public function export_btn($text = "Exporter l'agenda", $filename = "agenda.ics") {
        $filename = (!empty($filename) ? preg_replace('/\.ics$/i', "", trim(preg_replace('/[^A-Za-z0-9._-]+/', "_", basename((string) $filename)), "._")) : "agenda") . ".ics";
        $escape = function ($value) {
            return strtr(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', "", trim((string) $value)), ["\\" => "\\\\", ";" => "\\;", "," => "\\,", "\r\n" => "\\n", "\r" => "\\n", "\n" => "\\n"]);
        };
        $utc = new DateTimeZone("UTC");
        $host = preg_replace('/[^A-Za-z0-9.-]/', "", explode(":", $_SERVER["HTTP_HOST"] ?? "")[0]);
        $dtstamp = gmdate("Ymd\THis\Z");
        $lines = [
            "BEGIN:VCALENDAR",
            "VERSION:2.0",
            "PRODID:-//DWF//fullcalendar//FR",
            "CALSCALE:GREGORIAN",
            "METHOD:PUBLISH",
            "X-WR-CALNAME:" . $escape(pathinfo($filename, PATHINFO_FILENAME))
        ];
        foreach ($this->_events as $event) {
            try {
                $start = (!empty($event["start"]) ? new DateTime($event["start"]) : null);
                $end = (!empty($event["end"]) ? new DateTime($event["end"]) : null);
            } catch (Exception $e) {
                $start = null;
            }
            if (empty($start)) {
                dwf_exception::warning_exception(651, ["__" => json_encode($event["title"] ?? "")]);
                continue;
            }
            $lines[] = "BEGIN:VEVENT";
            $lines[] = "UID:" . $escape(!empty($event["uid"]) ? $event["uid"] : (isset($event["id"]) ? $event["id"] : sha1(json_encode($event))) . "@" . (!empty($host) ? $host : "dwf"));
            $lines[] = "DTSTAMP:{$dtstamp}";
            if (!empty($event["allDay"]) or preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($event["start"]))) {
                $lines[] = "DTSTART;VALUE=DATE:" . $start->format("Ymd");
                $lines[] = "DTEND;VALUE=DATE:" . ((!empty($end) and $end > $start) ? $end : (clone $start)->modify("+1 day"))->format("Ymd");
            } else {
                $lines[] = "DTSTART:" . (clone $start)->setTimezone($utc)->format("Ymd\THis\Z");
                if (!empty($end) and $end > $start) {
                    $lines[] = "DTEND:" . (clone $end)->setTimezone($utc)->format("Ymd\THis\Z");
                }
            }
            $lines[] = "SUMMARY:" . $escape($event["title"] ?? "");
            foreach (["description" => "DESCRIPTION", "location" => "LOCATION"] as $key => $property) {
                if (!empty($event[$key])) {
                    $lines[] = "{$property}:" . $escape($event[$key]);
                }
            }
            if (!empty($event["url"]) and filter_var($event["url"], FILTER_VALIDATE_URL) and in_array(strtolower((string) parse_url($event["url"], PHP_URL_SCHEME)), ["http", "https"])) {
                $lines[] = "URL:" . $event["url"];
            }
            $lines[] = "END:VEVENT";
        }
        $lines[] = "END:VCALENDAR";
        $ics = "";
        foreach ($lines as $line) {
            $folded = mb_strcut($line, 0, 75, "UTF-8");
            for ($pos = strlen($folded); $pos < strlen($line); $pos += strlen($chunk)) {
                $chunk = mb_strcut($line, $pos, 74, "UTF-8");
                $folded .= "\r\n " . $chunk;
            }
            $ics .= $folded . "\r\n";
        }
        $key = "ICS_" . sha1($this->_id . json_encode($this->_events));
        $_SESSION[$key] = json_encode(["filename" => $filename, "content" => $ics]);
        export_dwf::add_files([
            realpath("../commun/printer.php"),
            realpath("../commun/printer"),
        ]);
        $form = new form("", "../commun/printer.php", "post");
        $form->hidden("content", $key);
        $form->submit("btn btn-secondary", htmlspecialchars($text, ENT_QUOTES));
        return $form->render();
    }

    /**
     * Affiche l'administration du Fullcalendar (ajout, modification, suppression et import ICS des évènements)
     * Point d'entrée statique conservé (fullcalendar::admin();), les pages d'administration sont des méthodes d'instance
     * appelées sur une instance sans affichage (new self(null)).
     */
    public static function admin() {
        $admin = new self(null);
        if (!isset($_GET["action"])) {
            entity_generator::generate(["fullcalendar_event" => self::$_entity]);
            $_GET["action"] = "default";
        }
        switch ($_GET["action"]) {
            case "edit":
                $admin->admin_edit($_GET["id"]);
                break;
            case "del":
                $admin->admin_del($_GET["id"]);
                break;
            case "import":
                $admin->admin_import();
                break;
            default:
                $admin->admin_default();
                break;
        }
    }

    /**
     * Affiche les événements en base de données et le formulaire d'ajout
     */
    private function admin_default() {
        $events = [];
        foreach (fullcalendar_event::get_table_array() as $event) {
            $events[] = [
                htmlspecialchars($event["calendar"], ENT_QUOTES, "UTF-8", false),
                htmlspecialchars($event["title"], ENT_QUOTES, "UTF-8", false),
                htmlspecialchars($event["start"], ENT_QUOTES, "UTF-8", false),
                htmlspecialchars($event["end"], ENT_QUOTES, "UTF-8", false),
                htmlspecialchars($event["url"], ENT_QUOTES, "UTF-8", false),
                nl2br(htmlspecialchars($event["description"], ENT_QUOTES, "UTF-8", false)),
                htmlspecialchars($event["location"], ENT_QUOTES, "UTF-8", false),
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
            fullcalendar_event::ajout($_POST["calendar"], $_POST["title"], $start, $end, $url, $_POST["description"] ?? "", $_POST["location"] ?? "", "");
            js::alertify_alert_redir("L'événement a bien été ajouté", "");
        } else {
            $form = new form();
            $form->open_fieldset("Ajouter un événement");
            $form->input("Agenda", "calendar", "text", "Default");
            $form->input("Titre", "title");
            $form->datetimepicker("Debut", "start");
            $form->datetimepicker("Fin", "end");
            $form->input("URL (falcutatif)", "url", "url", "", false);
            $form->input("Lieu (falcutatif)", "location", "text", "", false);
            $form->textarea("Description (falcutatif)", "description", "", false, "", 30, 3);
            $form->submit("btn btn-primary", "Ajouter");
            $form->close_fieldset();
            js::datatable();
            echo tags::tag("p", [], tags::tag("a", ["class" => "btn btn-secondary", "href" => application::get_url(["action"]) . "action=import"], "Importer un fichier ICS")) .
            html_structures::table(["Agenda", "Titre", "Début", "Fin", "URL", "Description", "Lieu", "Edit"], $events, "", "datatable") .
            html_structures::hr() . $form->render();
        }
    }

    /**
     * Affiche le formulaire d'import d'un fichier ICS (iCalendar, RFC 5545) et le bilan de l'import.
     * Un évènement déjà présent dans l'agenda (même UID, ou à défaut même titre et même début) est mis à jour
     * s'il a changé, ignoré sinon : un fichier peut donc être réimporté sans créer de doublons.
     * Les évènements récurrents (RRULE) ne sont importés qu'à leur première occurrence,
     * les occurrences modifiées (RECURRENCE-ID) et les évènements annulés sont ignorés.
     */
    private function admin_import() {
        $form = new form();
        $form->open_fieldset("Importer un fichier ICS");
        $form->input("Agenda", "calendar", "text", "Default");
        $form->file("Fichier ICS (.ics)", "ics");
        $form->csrf_token();
        $form->submit("btn btn-primary", "Importer");
        $form->close_fieldset();
        $back = tags::tag("a", ["class" => "btn btn-secondary", "href" => application::get_url(["action"])], "Retour");
        if (!isset($_POST["calendar"])) {
            echo $form->render() . tags::tag("p", [], $back);
            return;
        }
        $events = false;
        if (!$form->csrf_check()) {
            $error = "CSRF Invalide !";
        } else {
            $dir = rtrim(sys_get_temp_dir(), "/\\");
            $upload = form::get_upload("ics", $dir, ["text/calendar", "text/plain"], "dwf_ics_" . bin2hex(random_bytes(8)) . "_" . (is_string($_FILES["ics"]["name"] ?? null) ? $_FILES["ics"]["name"] : ""));
            if (!$upload["error"] and !empty($upload["doc"])) {
                $events = $this->ics_parse(file_get_contents("{$dir}/{$upload["doc"]}"));
                unlink("{$dir}/{$upload["doc"]}");
            }
            $error = ($upload["error"] ? "Le fichier n'est pas un fichier ICS ou l'upload a échoué !" : ($events === false ? "Aucun agenda ICS valide reçu (fichier absent, trop volumineux pour upload_max_filesize ou BEGIN:VCALENDAR absent) !" : ""));
        }
        if (!empty($error)) {
            echo tags::tag("p", ["class" => "alert alert-danger"], htmlspecialchars($error)) . tags::tag("p", [], tags::tag("a", ["class" => "btn btn-primary", "href" => application::get_url(["action"]) . "action=import"], "Réessayer") . " " . $back);
            return;
        }
        $calendar = ((is_string($_POST["calendar"]) and !empty(trim($_POST["calendar"]))) ? trim($_POST["calendar"]) : "Default");
        $existing = [];
        foreach (fullcalendar_event::get_table_array() as $row) {
            $existing[implode("\n", (!empty($row["uid"]) ? [htmlspecialchars_decode($row["calendar"]), "uid", $row["uid"]] : [htmlspecialchars_decode($row["calendar"]), htmlspecialchars_decode($row["title"]), $row["start"]]))] = $row["id"];
        }
        $result = ["imported" => 0, "updated" => 0, "ignored" => 0, "recurring" => 0];
        foreach ($events as $event) {
            if (!empty($event["ignored"])) {
                $result["ignored"]++;
                continue;
            }
            $result["recurring"] += (!empty($event["rrule"]) ? 1 : 0);
            if (isset($existing[$key = implode("\n", (!empty($event["uid"]) ? [$calendar, "uid", $event["uid"]] : [$calendar, $event["title"], $event["start"]]))])) {
                $entity = fullcalendar_event::get_from_id($existing[$key]);
                if (!empty($entity) and [$entity->get_title(), $entity->get_start(), $entity->get_end(), $entity->get_url(), $entity->get_description(), $entity->get_location()] !== [$event["title"], $event["start"], $event["end"], $event["url"], $event["description"], $event["location"]]) {
                    $entity->set_title($event["title"]);
                    $entity->set_start($event["start"]);
                    $entity->set_end($event["end"]);
                    $entity->set_url($event["url"]);
                    $entity->set_description($event["description"]);
                    $entity->set_location($event["location"]);
                    $entity->update();
                    $result["updated"]++;
                } else {
                    $result["ignored"]++;
                }
                continue;
            }
            $entity = fullcalendar_event::ajout($calendar, $event["title"], $event["start"], $event["end"], $event["url"], $event["description"], $event["location"], $event["uid"]);
            $existing[$key] = (is_object($entity) ? $entity->get_id() : 0);
            $result["imported"]++;
        }
        echo tags::tag("div", ["class" => "alert alert-success"],
                tags::tag("p", [], "Import terminé dans l'agenda « " . htmlspecialchars($calendar) . " » :") .
                html_structures::ul([
                    "{$result["imported"]} évènement(s) importé(s)",
                    "{$result["updated"]} évènement(s) mis à jour",
                    "{$result["ignored"]} évènement(s) ignoré(s) (déjà présents, annulés, occurrences modifiées ou dates invalides)",
                    "{$result["recurring"]} évènement(s) récurrent(s) : seule la première occurrence est importée"
                ])) . tags::tag("p", [], tags::tag("a", ["class" => "btn btn-primary", "href" => application::get_url(["action"]) . "action=import"], "Importer un autre fichier") . " " . $back);
    }

    /**
     * Analyse un contenu ICS (iCalendar, RFC 5545) et retourne ses évènements (VEVENT) au format de fullcalendar_event.
     * Gère le dépliage des lignes, les échappements (\\ \; \, \n), DTSTART/DTEND en UTC (Z), en heure locale flottante
     * ou avec TZID (fuseau du projet si le TZID est inconnu, par exemple un nom Windows d'Outlook), les journées entières
     * (VALUE=DATE, fin exclusive) et DURATION en l'absence de DTEND. Les VTODO, VALARM, VTIMEZONE, ... sont ignorés.
     * Les dates sont converties dans le fuseau du projet : "Y-m-d H:i:s", ou "Y-m-d" pour une journée entière.
     * @param string $ics Contenu du fichier ICS
     * @return array|false Liste d'évènements ["title", "start", "end", "url", "description", "location", "uid", "rrule", "ignored"], false si le contenu n'est pas un agenda ICS
     */
    private function ics_parse($ics) {
        $ics = preg_replace('/^\xEF\xBB\xBF/', "", (string) $ics);
        if (!mb_check_encoding($ics, "UTF-8")) {
            $ics = mb_convert_encoding($ics, "UTF-8", "Windows-1252");
        }
        $ics = preg_replace('/\n[ \t]/', "", preg_replace('/\r\n?/', "\n", $ics));
        if (!preg_match('/^BEGIN:VCALENDAR$/mi', $ics)) {
            return false;
        }
        $local = new DateTimeZone(date_default_timezone_get());
        $events = [];
        $stack = [];
        $props = [];
        foreach (explode("\n", $ics) as $line) {
            if (!preg_match('/^([A-Za-z0-9-]+)((?:;[A-Za-z0-9-]+=(?:"[^"]*"|[^";:]*)(?:,(?:"[^"]*"|[^";:,]*))*)*):(.*)$/', $line, $m)) {
                continue;
            }
            $name = strtoupper($m[1]);
            if ($name === "BEGIN") {
                $stack[] = strtoupper(trim($m[3]));
                $props = (end($stack) === "VEVENT" ? [] : $props);
                continue;
            }
            if ($name === "END") {
                if (array_pop($stack) === "VEVENT" and end($stack) === "VCALENDAR") {
                    $dates = [];
                    foreach (["DTSTART", "DTEND"] as $p) {
                        if (empty($props[$p]["value"])) {
                            continue;
                        }
                        $value = trim($props[$p]["value"]);
                        $tzid = trim($props[$p]["params"]["TZID"] ?? "", "\"/ ");
                        try {
                            $tz = (!empty($tzid) ? new DateTimeZone($tzid) : $local);
                        } catch (Exception $e) {
                            $tz = $local;
                        }
                        if (strtoupper($props[$p]["params"]["VALUE"] ?? "") === "DATE" or preg_match('/^\d{8}$/', $value)) {
                            $date = DateTime::createFromFormat("!Ymd", substr($value, 0, 8), $local);
                            $dates[$p] = (!empty($date) ? ["date" => $date, "allday" => true] : null);
                        } elseif (preg_match('/^(\d{8}T\d{6})(Z?)$/i', $value, $d)) {
                            $date = DateTime::createFromFormat("!Ymd\THis", strtoupper($d[1]), (!empty($d[2]) ? new DateTimeZone("UTC") : $tz));
                            $dates[$p] = (!empty($date) ? ["date" => $date->setTimezone($local), "allday" => false] : null);
                        } else {
                            $dates[$p] = null;
                        }
                    }
                    if (empty($dates["DTSTART"]) or isset($props["RECURRENCE-ID"]) or strtoupper(trim($props["STATUS"]["value"] ?? "")) === "CANCELLED") {
                        $events[] = ["ignored" => true];
                        continue;
                    }
                    $allday = $dates["DTSTART"]["allday"];
                    $start = $dates["DTSTART"]["date"];
                    $end = (!empty($dates["DTEND"]) ? $dates["DTEND"]["date"] : null);
                    if (empty($end) and !empty($props["DURATION"]["value"])) {
                        try {
                            $duration = new DateInterval(ltrim(strtoupper(trim($props["DURATION"]["value"])), "+"));
                            $end = (clone $start)->add($duration);
                        } catch (Exception $e) {
                            $end = null;
                        }
                    }
                    if (empty($end) or $end < $start) {
                        $end = ($allday ? (clone $start)->modify("+1 day") : clone $start);
                    }
                    $url = trim($props["URL"]["value"] ?? "");
                    $events[] = [
                        "title" => (!empty($props["SUMMARY"]["value"]) ? $props["SUMMARY"]["value"] : "(sans titre)"),
                        "start" => $start->format($allday ? "Y-m-d" : "Y-m-d H:i:s"),
                        "end" => $end->format($allday ? "Y-m-d" : "Y-m-d H:i:s"),
                        "url" => ((filter_var($url, FILTER_VALIDATE_URL) and in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ["http", "https"])) ? $url : ""),
                        "description" => trim($props["DESCRIPTION"]["value"] ?? ""),
                        "location" => trim($props["LOCATION"]["value"] ?? ""),
                        "uid" => trim($props["UID"]["value"] ?? ""),
                        "rrule" => !empty($props["RRULE"]["value"]),
                        "ignored" => false
                    ];
                }
                continue;
            }
            if (end($stack) === "VEVENT" and !isset($props[$name])) {
                preg_match_all('/;([A-Za-z0-9-]+)=("[^"]*"|[^";:]*)/', $m[2], $params, PREG_SET_ORDER);
                $props[$name] = [
                    "params" => array_column(array_map(function ($param) {
                                return [strtoupper($param[1]), trim($param[2], "\"")];
                            }, $params), 1, 0),
                    "value" => preg_replace_callback('/\\\\([\\\\;,nN])/', function ($c) {
                                return (strtolower($c[1]) === "n" ? "\n" : $c[1]);
                            }, $m[3])
                ];
            }
        }
        return $events;
    }

    /**
     * Afiche le formulaire d'edition d'un événement
     * @param int $id Id de l'événement
     */
    private function admin_edit($id) {
        $event = fullcalendar_event::get_from_id($id);
        if (isset($_POST["calendar"])) {
            $event->set_calendar($_POST["calendar"]);
            $event->set_title($_POST["title"]);
            $event->set_start(form::get_datetimepicker_us("start"));
            $event->set_end(form::get_datetimepicker_us("end"));
            $event->set_url((isset($_POST["url"]) and!empty($_POST["url"])) ? $_POST["url"] : "");
            $event->set_description($_POST["description"] ?? "");
            $event->set_location($_POST["location"] ?? "");
            js::alertify_alert_redir("L'événement a bien été modifié", application::get_url(["action", "id"]));
        } else {
            $form = new form();
            $form->open_fieldset("Modifier un événement");
            $form->input("Agenda", "calendar", "text", htmlspecialchars($event->get_calendar(), ENT_QUOTES, "UTF-8", false));
            $form->input("Titre", "title", "text", htmlspecialchars($event->get_title(), ENT_QUOTES, "UTF-8", false));
            $start = explode(" ", $event->get_start());
            $start = time::date_us_to_fr($start[0]) . " " . ($start[1] ?? "00:00:00");
            $form->datetimepicker("Debut", "start", $start);
            $end = explode(" ", $event->get_end());
            $end = time::date_us_to_fr($end[0]) . " " . ($end[1] ?? "00:00:00");
            $form->datetimepicker("Fin", "end", $end);
            $form->input("URL (falcutatif)", "url", "url", htmlspecialchars($event->get_url(), ENT_QUOTES, "UTF-8", false), false);
            $form->input("Lieu (falcutatif)", "location", "text", htmlspecialchars($event->get_location(), ENT_QUOTES, "UTF-8", false), false);
            $form->textarea("Description (falcutatif)", "description", htmlspecialchars($event->get_description(), ENT_QUOTES, "UTF-8", false), false, "", 30, 3);
            $form->submit("btn btn-primary", "Modifier");
            $form->close_fieldset();
            echo $form->render();
        }
    }

    /**
     * Affiche la demande de confirmation pour la suppresion d'un événement
     * @param int $id Id de l'événement
     */
    private function admin_del($id) {
        $event = fullcalendar_event::get_from_id($id);
        ?><p>Etes-vous sur de vouloir supprimer l'événement : <br />
            <?= htmlspecialchars("{$event->get_title()} de {$event->get_start()} à {$event->get_end()} ?", ENT_QUOTES, "UTF-8", false) ?>
        </p>
        <div class="row">
            <div class="col-6">
                <?php
                if (isset($_POST["del"])) {
                    $event->delete();
                    js::alertify_alert_redir("L'événement a bien été supprimé", application::get_url(["action", "id"]));
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
