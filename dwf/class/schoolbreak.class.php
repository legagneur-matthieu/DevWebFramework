<?php

/**
 * Cette classe permet d'afficher un tableau des vacances scolaires 
 * (france uniquement pour le moment)
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class schoolbreak {

    /**
     * Affiche un tableau des vacances scolaires
     * @param string $academie academie pour détermier la zone A, B ou C et les "pont" (Amiens par defaut)
     */
    public static function fr($academie = "Amiens") {
        $y = (int) date('Y');
        if (!file_exists($filename = "./src/schoolbreak/{$y}.json")) {
            $url = "https://data.education.gouv.fr/api/records/1.0/search/?dataset=fr-en-calendrier-scolaire&q=&rows=20&&exclude.population=Enseignants&refine.location={$academie}&refine.annee_scolaire=";
            $scoolbreak = [];
            foreach ([
        ($y - 1) . "-" . $y,
        $y . "-" . ($y + 1)
            ] as $y) {
                $a = json_decode(service::HTTP_GET($url . $y), true);
                if (isset($a["nhits"]) and $a["nhits"] and ( $a["nhits"] <= $a["parameters"]["rows"])) {
                    foreach ($a['records'] as $sb) {
                        $scoolbreak[$sb["fields"]["start_date"]] = [
                            "name" => $sb["fields"]["description"],
                            "start" => strtotime($sb["fields"]["start_date"]),
                            "end" => strtotime($sb["fields"]["end_date"])
                        ];
                    }
                }
            }
            if (count($scoolbreak)) {
                ksort($scoolbreak);
                file_put_contents($filename, json_encode($scoolbreak));
            }
        }
        if (file_exists($filename)) {
            $scoolbreak = json_decode(file_get_contents($filename), true);
            $data = [];
            foreach ($scoolbreak as $sb) {
                if ($sb["end"] >= time()) {
                    $data[] = [
                        $sb["name"] . ($sb["start"] < time() ? " <small><i>(en cours)</i></small>" : ""),
                        tags::tag("span", ["title" => ($sb["start"] > time() ? "dans " . ((int) (($sb["start"] - time()) / 84600)) . " jours" : "")], date("d/m/Y", $sb["start"])),
                        tags::tag("span", ["title" => "dans " . ((int) (($sb["end"] - time()) / 84600)) . " jours"], date("d/m/Y", $sb["end"])),
                    ];
                }
            }
            echo html_structures::table(["Vacances", "Debut", "Fin"], $data);
        } else {
            echo tags::tag("p", ["class" => "text-center"], "Les vaccances scolaires ne sont pas encore disponible.");
        }
    }
}
