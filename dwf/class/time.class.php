<?php

/**
 * Cette classe gère des fonctions basiques basées sur le temps
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class time {

    /**
     * Array contenant les microtimes des chronomètres utilisés
     * 
     * @var array Array contenant les microtimes des chronomètres utilisés
     */
    private static $_chronometer;

    /**
     * Convertit une date au format US (yyyy-mm-dd) au format FR (dd/mm/yyyy)
     * @param string $dateUS date au format US
     * @return string date au format fr
     */
    public static function date_us_to_fr($dateUS) {
        return date("d/m/Y", strtotime(date($dateUS)));
    }

    /**
     * Convertit une date au format FR (dd/mm/yyyy) au format US (yyyy-mm-dd)
     * @param string $dateUS date au format US
     * @return string date au format fr
     */
    public static function date_fr_to_us($dateFR) {
        return date_format(date_create_from_format("d/m/Y", $dateFR), "Y-m-d");
    }

    /**
     * Convertit une date au format US (yyyy-mm-dd hh:mm:ss) au format FR (dd/mm/yyyy hh:mm:ss)
     * @param string $dateUS date au format US
     * @return string date au format fr
     */
    public static function datetime_us_to_fr($dateUS) {
        return date("d/m/Y H:i:s", strtotime(date($dateUS)));
    }

    /**
     * Convertit une date au format FR (dd/mm/yyyy hh:mm:ss) au format US (yyyy-mm-dd hh:mm:ss)
     * @param string $dateUS date au format US
     * @return string date au format fr
     */
    public static function datetime_fr_to_us($dateFR) {
        return date_format(date_create_from_format("d/m/Y H:i:s", $dateFR), "Y-m-d H:i:s");
    }

    /**
     * (DEPPRECIÉ)
     * Convertit une date du format US en format FR (mois en toutes lettres)
     * 
     * @param string $date date US
     * @return boolean|string date FR ou false
     */
    public static function convert_date($date) {
        $us = explode("-", $date);
        return (!isset($us[0]) or!isset($us[1]) or!isset($us[2]) ? false : "{$us[2]} " . self::convert_mois($us[1]) . " {$us[0]}");
    }

    /**
     * Retourne les mois avec leurs numéros (à 2 chiffres) comme clé
     * 
     * @return array tableau des mois
     */
    public static function get_mois() {
        return ["01" => "Janvier",
            "02" => "Fevrier",
            "03" => "Mars",
            "04" => "Avril",
            "05" => "Mai",
            "06" => "Juin",
            "07" => "Juillet",
            "08" => "Aout",
            "09" => "Septembre",
            "10" => "Octobre",
            "11" => "Novembre",
            "12" => "Decembre"
        ];
    }

    /**
     * Retourne le mois "en lettres" du numéro de mois passé en paramètre 
     * 
     * @param string $num_mois numéro du mois ( à 2 chiffres)
     * @return string mois "en lettres"
     */
    public static function convert_mois($num_mois) {
        return self::get_mois()[$num_mois];
    }

    /**
     * Retourne le nombre de jours dans un mois 
     * (l'année doit être renseignée pour gérer les années bissextiles)
     * 
     * @param string $num_mois numéro du mois ( à 2 chiffres)
     * @param string $an année du mois à évaluer
     * @return int nombre de jours dans le mois
     */
    public static function get_nb_jour($num_mois, $an) {
        $nb = [
            "01" => 31,
            "02" => (self::anne_bisextile($an) ? 29 : 28),
            "03" => 31,
            "04" => 30,
            "05" => 31,
            "06" => 30,
            "07" => 31,
            "08" => 31,
            "09" => 30,
            "10" => 31,
            "11" => 30,
            "12" => 31
        ];
        return $nb[$num_mois];
    }

    /**
     * retourne si une année est bissextile ou non.
     * 
     * @param int $an année à évaluer
     * @return boolean l'année est bissextile ? true/false
     */
    public static function anne_bisextile($an) {
        return ($an % 4 == 0 and $an % 100 != 0 or $an % 400 == 0);
    }

    /**
     * Affiche un élément de formulaire pour renseigner une date (jour/mois/année)
     * 
     * @param form $form objet form
     * @param string $label label
     * @param string $post préfixe des variables ($post."an",$post."mois",$post."jour")
     * @param string $value date par défaut au format US (null par defaut : date actuelle )
     */
    public static function form_date($form, $label, $post, $value = null) {
        $value = ($value == null ? [date("Y"), date("m"), date("d")] : explode("-", $value));
        $option_j = [];
        foreach (range(0, 31) as $i) {
            $i = ($i < 10 ? "0{$i}" : $i);
            $option_j[] = [$i, $i, ($i == $value[2])];
        }
        $option_m = [];
        foreach (self::get_mois() as $key => $m) {
            $option_m[] = [$key, $m, ($key == $value[1])];
        }
        return $form->select("Jour", $post . "jour", $option_j) .
                $form->select("Mois", $post . "mois", $option_m) .
                $form->input("Année", $post . "an", "number", $value[0]);
    }

    /**
     * Retourne la date saisie dans l'élément de formulaire ::form_date();
     * 
     * @param string $post le préfixe utilisé pour l'élément de formulaire
     * @return string date au format US
     */
    public static function get_form_date($post) {
        return"{$_POST[$post . "an"]}-{$_POST[$post . "mois"]}-{$_POST[$post . "jour"]}";
    }

    /**
     * Retourne l'âge actuel en fonction d'une date de naissance
     * 
     * @param int $d jour de naissance
     * @param int $m mois de naissance
     * @param int $y année de naissance
     * @return int age
     */
    public static function get_yers_old($d, $m, $y) {
        $yo = date("Y") - $y;
        return (date("md") < "{$m}{$d}" ? $yo - 1 : $yo);
    }

    /**
     * Cette fonction permet d'additioner ou de soustraire un nombre de mois à une date initiale
     * @param string $date date initiale au format us (yyyy-mm-dd)
     * @param int $mois combien de mois faut-il ajouter ( ou soustraire ) (renseigner un nombre négatif pour soustraire )
     * @return string date calculée au format US
     */
    public static function date_plus_ou_moins_mois($date, $mois) {
        $date = explode("-", $date);
        $point = ($point = abs($mois % 12) == 0 ? 12 : $point);
        $date[0] += ((int) ($mois / 12));
        if ($date[1] <= $point and $point != 12 and $mois < 0) {
            $date[0]--;
        } elseif ($date[1] <= $point and $point != 12 and $mois > 0) {
            $date[0]++;
        }
        $k = 12 * 80000000;
        $date[1] = abs((($date[1] + $k + ($mois)) % 12));
        if ($date[1] == 0) {
            $date[1] = 12;
        }
        if ($date[1] < 10) {
            $date[1] = "0" . $date[1];
        }
        return $date[0] . "-" . $date[1] . "-" . $date[2];
    }

    /**
     * Démarre un chronomètre pour chronometrer la durée d'exécution d'un bout de code,
     * il est possible d'utiliser plusieurs chronomètres en leurs spécifiant un identifiant
     * l'identifiant peut être un nombre ou une chaine de caractères
     * 
     * @param int|string $id Id du chronomètre
     */
    public static function chronometer_start($id = 0) {
        time::$_chronometer[$id] = microtime(true);
    }

    /**
     * Retourne le temps mesuré par un chronomètre depuis son lancement
     * 
     * @param int|string $id Id du chronomètre
     * @return float Temps mesuré par le chronomètre
     */
    public static function chronometer_get($id = 0) {
        return (microtime(true) - time::$_chronometer[$id]);
    }

    /**
     * Parse un temps en secondes en jours/heures/minutes/secondes <br />
     * pour les temps inférieurs à 1 seconde, le parse peut se faire en millisecondes ou microsecondes
     * 
     * @param int|float $secondes Secondes
     * @return string Temps
     */
    public static function parse_time($secondes) {
        if ($secondes < 1) {
            $ms = (int) ($secondes * 1000);
            return ($ms < 1 ? ((int) ($secondes * 1000000)) . " µs" : $ms . " ms");
        }
        if ($secondes < 60) {
            return "$secondes s";
        } else {
            $min = (int) ($secondes / 60);
            $secondes %= 60;
            if ($min < 60) {
                return "$min min $secondes s";
            } else {
                $h = (int) ($min / 60);
                $min %= 60;
                if ($h < 24) {
                    return "$h h $min min $secondes s";
                } else {
                    $j = (int) ($h / 24);
                    $h %= 24;
                    if ($j < 31) {
                        return "$j jours $h h $min min $secondes s";
                    } else {
                        $m = (int) ($j / 31);
                        $j %= 31;
                        if ($m < 12) {
                            return "$m mois $j jours $h h $min min $secondes s";
                        } else {
                            $a = (int) ($m / 12);
                            $m %= 12;
                            if ($a < 100) {
                                return "$a ans $m mois $j jours $h h $min min $secondes s";
                            } else {
                                $s = (int) ($a / 100);
                                $a %= 100;
                                return "$s siècles $a ans $m mois $j jours $h h $min min $secondes s";
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Retourne un tableau d'informations sur la date passée en paramètre
     * @param string $date_us Date au format US
     * @return array https://secure.php.net/manual/fr/function.getdate.php
     */
    public static function get_info_from_date($date_us) {
        return getdate(strtotime($date_us));
    }

}
