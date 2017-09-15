<?php

/**
 * Cette classe gère des fonctions basiques basées sur le temps
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class time {

    /**
     * Array contenant les microtimes des chronometres utilisés
     * 
     * @var array Array contenant les microtimes des chronometres utilisés
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
     * (DEPPRECIÃ‰)
     * Convertit une date du format US en format FR (mois en toutes lettres)
     * 
     * @param string $date date US
     * @return boolean|string date FR ou false
     */
    public static function convert_date($date) {
        $us = explode("-", $date);
        if (!isset($us[0]) or ! isset($us[1]) or ! isset($us[2])) {
            return false;
        }
        return $us[2] . " " . self::convert_mois($us[1]) . " " . $us[0];
    }

    /**
     * Retourne les mois avec leurs numéros (à 2 chiffres) comme clé
     * 
     * @return array tableau des mois
     */
    public static function get_mois() {
        return array("01" => "Janvier",
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
        );
    }

    /**
     * Retourne le mois "en lettres" du numéro de mois passé en paramètre 
     * 
     * @param string $num_mois numéro du mois ( à 2 chiffres)
     * @return string mois "en lettres"
     */
    public static function convert_mois($num_mois) {
        $mois = self::get_mois();
        return $mois[$num_mois];
    }

    /**
     * Retourne le nombre de jours dans un mois 
     * (l'année doit être renseignée pour gérer les années bisextiles)
     * 
     * @param string $num_mois numéro du mois ( à 2 chiffres)
     * @param string $an année du mois à évaluer
     * @return int nombre de jours dans le mois
     */
    public static function get_nb_jour($num_mois, $an) {
        $nb = array(
            "01" => 31,
            "02" => 28,
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
        );
        if (self::anne_bisextile($an)) {
            $nb["02"] = 29;
        }
        return $nb[$num_mois];
    }

    /**
     * retourne si une année est bisextile ou non.
     * 
     * @param int $an année à évaluer
     * @return boolean l'année est bisextile ? true/false
     */
    public static function anne_bisextile($an) {
        return ($an % 4 == 0 and $an % 100 != 0 or $an % 400 == 0);
    }

    /**
     * Affiche un élément de formulaire pour renseigner une date (jour/mois/année)
     * 
     * @param string $label label
     * @param string $post préfixe des variables ($post."an",$post."mois",$post."jour")
     * @param string $value date par défaut au format US (null par defaut : date actuelle )
     */
    public static function form_date($label, $post, $value = null) {
        $value = ($value == null ? array(date("Y"), date("m"), date("d")) : explode("-", $value));
        ?>
        <div class="form-group form_date">
            <label for="<?php echo $post; ?>an"> <?php echo $label; ?> (jour/mois/année)</label>
            <select name="<?php echo $post; ?>jour" class="form-control">
                <?php
                for ($index = 1; $index <= 31; $index++) {
                    if ($index < 10) {
                        $index = "0" . $index;
                    }
                    ?>
                    <option value="<?php echo $index; ?>" <?php
                    if ($index == $value[2]) {
                        echo 'selected="selected" ';
                    }
                    ?>><?php echo $index; ?></option>
                            <?php
                        }
                        ?>
            </select>
            <select name="<?php echo $post; ?>mois" class="form-control">
                <?php
                $mois = new time();
                $mois = $mois->get_mois();
                foreach ($mois as $key => $m) {
                    ?>
                    <option value="<?php echo $key; ?>" <?php
                    if ($key == $value[1]) {
                        echo 'selected="selected" ';
                    }
                    ?>><?php echo $m; ?></option>
                            <?php
                        }
                        ?>
            </select>
            <input type="number" id="<?php echo $post; ?>an" name="<?php echo $post; ?>an" value="<?php echo $value[0]; ?>" class="form-control"/>
        </div>
        <?php
    }

    /**
     * Retourne la date saisie dans l'élément de formulaire ::form_date();
     * 
     * @param string $post le préfixe utilisé pour l'élément de formulaire
     * @return string date au format US
     */
    public static function get_form_date($post) {
        return ($_POST[$post . "an"] . "-" . $_POST[$post . "mois"] . "-" . $_POST[$post . "jour"]);
    }

    /**
     * Retourne l'Ã¢ge actuel en fonction d'une date de naissance
     * 
     * @param int $d jour de naissance
     * @param int $m mois de naissance
     * @param int $y année de naissance
     * @return int age
     */
    public static function get_yers_old($d, $m, $y) {
        $yo = date("Y") - $y;
        if (date("md") < ($m . $d)) {
            $yo--;
        }
        return $yo;
    }

    /**
     * Cette fonction permet d'additioner ou de soustraire un nombre de mois à une date initiale
     * @param string $date date initiale au format us (yyyy-mm-dd)
     * @param int $mois combien de mois faut-il ajouter ( ou soustraire ) (renseigner un nombre négatif pour soustraire )
     * @return string date calculée au format US
     */
    public static function date_plus_ou_moins_mois($date, $mois) {
        $date = explode("-", $date);
        $point = abs($mois % 12);
        if ($point == 0) {
            $point = 12;
        }
        $date[0] += ((int) ($mois / 12));
        if ($date[1] <= $point and $point != 12 and $mois < 0) {
            $date[0] --;
        } elseif ($date[1] <= $point and $point != 12 and $mois > 0) {
            $date[0] ++;
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
     * Démarre un chronomètre pour chronometrer la durée d'execution d'un bout de code,
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
            return ($ms < 1 ? ((int) ($secondes * 1000000)) . " Âµs" : $ms . " ms");
        }
        if ($secondes < 60) {
            return $secondes . " s";
        } else {
            $min = (int) ($secondes / 60);
            $secondes %= 60;
            if ($min < 60) {
                return $min . " min " . $secondes . " s";
            } else {
                $h = (int) ($min / 60);
                $min %= 60;
                if ($h < 24) {
                    return $h . " h " . $min . " min " . $secondes . " s";
                } else {
                    $j = (int) ($h / 24);
                    $h %= 24;
                    return $j . " J " . $h . " h " . $min . " min " . $secondes . " s";
                }
            }
        }
    }

    /**
     * Retourne un tableau d'information sur la date passée en paramètre
     * @param string $date_us Date au format US
     * @return array https://secure.php.net/manual/fr/function.getdate.php
     */
    public static function get_info_from_date($date_us) {
        return getdate(strtotime($date_us));
    }

}
