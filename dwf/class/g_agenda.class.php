<?php

/**
 * Cette classe permet de gérer un agenda d'évènements minimaliste,
 * souvent utilisé pour avertir des visiteurs des prochains évènements
 * (salons, conventions, spectacles, ...)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class g_agenda {

    /**
     * Cette classe permet de gérer un agenda d'évènements minimaliste,
     * souvent utilisé pour avertir des visiteurs des prochains évènements
     * (salons, conventions, spectacles, ...)
     */
    public function __construct() {
        new entity_generator([
            ["id", "int", true],
            ["date_debut", "string", false],
            ["date_fin", "string", false],
            ["titre", "string", false],
            ["texte", "string", false]
                ], "agenda", true, true);
        compact_css::get_instance()->add_css_file("../commun/src/css/agenda.css");
    }

    /**
     * Affiche l'interface d'administration
     */
    public function admin() {
        (isset($_GET["agenda_supp"]) ? $this->agenda_admin_supp() : (isset($_GET["agenda"]) ? $this->agenda_admin_modif() : $this->agenda_admin_list()));
    }

    /**
     * Affiche la liste d'évènements prévus
     * @param int $lim nombre limite d'évènements à afficher (10 par défaut)
     */
    public function agenda_page($lim = 10) {
        (isset($_GET["agenda"]) ? $this->agenda_envent($_GET["agenda"]) : $this->agenda_liste($lim));
    }

    /**
     * Affiche la liste d'évènements prévus
     * @param int $lim nombre limite d'évènements à afficher (10 par défaut)
     */
    private function agenda_liste($lim) {
        $events = agenda::get_table_array("date_fin>=:date_fin order by date_debut limit 0,:lim", [":date_fin" => date("Y-m-d") . " 23:59", ":lim" => (int) $lim]);
        foreach ($events as $event) {
            $date_debut = explode(" ", $event["date_debut"]);
            $date_fin = explode(" ", $event["date_fin"]);
            echo tags::tag("div", ["class" => "row agenda"], tags::tag(
                            "div", ["class" => "col-sm-3"], tags::tag(
                                    "p", [], "Du : <strong>" . html_structures::time($event["date_debut"], time::date_us_to_fr($date_debut[0]) . " " . $date_debut[1]) . "</strong><br />" .
                                    "Au : <strong>" . html_structures::time($event["date_fin"], time::date_us_to_fr($date_fin[0]) . " " . $date_fin[1]) . "</strong>")
                    ) .
                    tags::tag("div", ["class" => "col-sm-9"], tags::tag(
                                    "p", [], tags::tag("a", ["href" => "index.php?page=" . $_GET["page"] . "&agenda=" . $event["id"]], $event["titre"])
                            ))
            ) . tags::tag("hr");
        }
    }

    /**
     * Affiche un évènement précis
     * @param int $id Id de l'évènement
     */
    private function agenda_envent($id) {
        $event = agenda::get_from_id($id);
        $date_debut = explode(" ", $event->get_date_debut());
        $date_fin = explode(" ", $event->get_date_fin());
        echo tags::tag("a", ["href" => "index.php?page=" . $_GET["page"]], html_structures::glyphicon("arrow-left", "Retour a la liste des evennements") . " Retour") .
        tags::tag("div", ["class" => "row agenda"], tags::tag(
                        "div", ["class" => "col-sm-3"], tags::tag(
                                "p", [], "Du : <strong>" . html_structures::time($event->get_date_debut(), time::date_us_to_fr($date_debut[0]) . " " . $date_debut[1]) . "</strong><br />" .
                                "Au : <strong>" . html_structures::time($event->get_date_fin(), time::date_us_to_fr($date_fin[0]) . " " . $date_fin[1]) . "</strong>")
                ) .
                tags::tag("div", ["class" => "col-sm-9"], tags::tag("p", [], tags::tag("strong", [], $event->get_titre())) .
                        tags::tag("hr") . tags::tag("article", [], htmlspecialchars_decode($event->get_texte()))
                )
        );
    }

    /**
     * Affiche la liste des évènements dans l'administration
     */
    private function agenda_admin_list() {
        $option = [];
        for ($i = 0; $i < 12; $i++) {
            $m = date("m") + $i;
            $y = date("Y");
            if ($m > 12) {
                $m %= 12;
                $y++;
            }
            if ($m < 10) {
                $m = "0" . $m;
            }
            $option[] = [$m . "-" . $y, time::convert_mois($m) . " " . $y];
        }
        $form = new form();
        $form->select("Mois", "agenda_my", $option);
        $form->submit("btn-primary");
        echo $form->render();
        if (isset($_POST["agenda_my"])) {
            $events = agenda::get_table_array("date_debut>=:date_debut1 and date_debut<=:date_debut2;", ["date_debut1" => "01-{$_POST["agenda_my"]} 00:00", "date_debut2" => "32-{$_POST["agenda_my"]} 00:00"]);
            debug::print_r($events);
            echo html_structures::hr();
            foreach ($events as $event) {
                $date_debut = explode(" ", $event["date_debut"]);
                $date_fin = explode(" ", $event["date_fin"]);
                echo tags::tag("div", ["class" => "row agenda"], tags::tag(
                                "div", ["class" => "col-sm-3"], tags::tag(
                                        "p", [], "Du : " .
                                        tags::tag("strong", [], html_structures::time($event["date_debut"], time::date_us_to_fr($date_debut[0]) . " " . $date_debut[1])) .
                                        tags::tag("br") . " Au : " .
                                        tags::tag("strong", [], html_structures::time($event["date_fin"], time::date_us_to_fr($date_fin[0]) . " " . $date_fin[1]))
                                )
                        ) .
                        tags::tag("div", ["class" => "col-sm-9"], html_structures::a_link("index.php?page=" . $_GET["page"] . "&agenda=" . $event["id"], $event["titre"]) .
                                html_structures::a_link("index.php?page=" . $_GET["page"] . "&amp;agenda_supp=" . $event["id"], html_structures::glyphicon("remove", "Supprimer l'évenement"), "btn btn-danger btn-xs")
                        ) . html_structures::hr()
                );
            }
            echo html_structures::hr();
            $cke = js::ckeditor("texte");
            $form = new form();
            $form->hidden("agenda_my", $_POST["agenda_my"]);
            $form->datetimepicker("Date de début", "date_debut", date("d/m/Y H:i"));
            $form->datetimepicker("Date de fin", "date_fin", date("d/m/Y H:i"));
            $form->input("Titre", "titre");
            $form->textarea("Texte", "texte");
            $form->submit("btn-primary");
            echo $form->render();
            if (isset($_POST["titre"])) {
                agenda::ajout($form->get_datetimepicker_us("date_debut"), $form->get_datetimepicker_us("date_fin"), $_POST["titre"], $cke->parse($_POST["texte"]));
                js::alert("L'événement a bien été ajouté");
                js::redir("");
            }
        }
    }

    /**
     * Modification d'un évènement dans l'administration
     */
    private function agenda_admin_modif() {
        $event = agenda::get_from_id($_GET["agenda"]);
        $debut = explode(" ", $event->get_date_debut());
        $debut[0] = explode("-", $debut[0]);
        $debut = $debut[0][2] . "/" . $debut[0][1] . "/" . $debut[0][0] . " " . $debut[1];
        $fin = explode(" ", $event->get_date_fin());
        $fin[0] = explode("-", $fin[0]);
        $fin = $fin[0][2] . "/" . $fin[0][1] . "/" . $fin[0][0] . " " . $fin[1];
        $cke = js::ckeditor("texte");
        $form = new form();
        $form->datetimepicker("Date de début", "date_debut", $debut);
        $form->datetimepicker("Date de fin", "date_fin", $fin);
        $form->input("Titre", "titre", "text", $event->get_titre());
        $form->textarea("Texte", "texte", htmlspecialchars($event->get_texte()));
        $form->submit("btn-primary");
        echo $form->render();
        if (isset($_POST["titre"])) {
            $event->set_date_debut($form->get_datetimepicker_us("date_debut"));
            $event->set_date_fin($form->get_datetimepicker_us("date_fin"));
            $event->set_titre($_POST["titre"]);
            $event->set_texte($cke->parse($_POST["texte"]));
            js::alert("L'événement a bien été ajouté");
            js::redir("index.php?page=" . $_GET["page"]);
        }
    }

    /**
     * Suppresion d'un évènement dans l'administration
     */
    private function agenda_admin_supp() {
        agenda::delete_by_id($_GET["agenda_supp"]);
        js::alert("L'événement a bien été supprimé");
        js::redir("index.php?page=" . $_GET["page"]);
    }
}
