/**
 * Cette classe gère des fonctions basiques basé sur le temps
 * 
 * @returns {dwf_time}
 */
function dwf_time() {

    var chronometer = {};

    /**
     * Convertit une date du format US en format FR
     * 
     * @param {string} date date US
     * @return {boolean|string} date FR ou false
     */
    this.convert_date = function (date) {
        var us = explode("-", date);
        if (!isset(us[0]) || !isset(us[1]) || !isset(us[2])) {
            return false;
        }
        return us[2] + " " + this.convert_mois(us[1]) + " " + us[0];
    };

    /**
     * Retourne les mois avec leurs numéros (à 2 chiffres) comme clé
     * 
     * @return {array} tableau des mois
     */
    this.get_mois = function () {
        return {"01": "Janvier",
            "02": "Fevrier",
            "03": "Mars",
            "04": "Avril",
            "05": "Mai",
            "06": "Juin",
            "07": "Juillet",
            "08": "Aout",
            "09": "Septembre",
            "10": "Octobre",
            "11": "Novembre",
            "12": "Decembre"
        };
    };

    /**
     * Retourne le mois "en lettres" du numéro de mois passé en paramètre 
     * 
     * @param {string} num_mois numéro du mois ( à 2 chiffres)
     * @return {string} mois "en lettre"
     */
    this.convert_mois = function (num_mois) {
        var mois = this.get_mois();
        return mois[num_mois];
    };

    /**
     * Retourne le nombre de jours dans un mois 
     * (l'année doit être renseignée pour gérer les années bisextiles)
     * 
     * @param {string} num_mois numéro du mois ( à 2 chiffres)
     * @param {string} an année du mois à évaluer
     * @return {int} nombre de jours dans le mois
     */
    this.get_nb_jour = function (num_mois, an) {
        var nb = {
            "01": 31,
            "02": 28,
            "03": 31,
            "04": 30,
            "05": 31,
            "06": 30,
            "07": 31,
            "08": 31,
            "09": 30,
            "10": 31,
            "11": 30,
            "12": 31
        };
        if (this.anne_bisextile(an)) {
            nb["02"] = 29;
        }
        return nb[num_mois];
    };

    /**
     * Retourne si une année est bisextile ou non.
     * 
     * @param {int} an année a évaluer
     * @return {boolean} l'année est bisextile ? true/false
     */
    this.anne_bisextile = function (an) {
        return (an % 4 == 0 && an % 100 != 0 || an % 400 == 0);
    };

    /**
     * Retourne l'âge actuel en fonction d'une date de naissance
     * 
     * @param {int} d jour de naissance
     * @param {int} m mois de naissance
     * @param {int} y année de naissance
     * @return {int} age
     */
    this.get_yers_old = function (d, m, y) {
        var yo = date("Y") - y;
        if (date("md") < (m + "" + d)) {
            yo--;
        }
        return yo;
    };

    /**
     * Cette fonction permet d'additioner ou de soustraire un nombre de mois a une date initiale
     * 
     * @param {string} date date initiale au format us (yyyy-mm-dd)
     * @param {int mois} combient de mois faut-il ajouter ( ou soustraire ) (renseigner un nombre négatif pour soustaire )
     * @return {string} date calculé au format US
     */
    this.date_plus_ou_moins_mois = function (date, mois) {
        date = explode("-", date);
        var point = abs(mois % 12);
        if (point == 0) {
            point = 12;
        }
        date[0] += (parseInt((mois / 12)));
        if (date[1] <= point && point != 12 && mois < 0) {
            date[0]--;
        } else {
            if (date[1] <= point && point != 12 && mois > 0) {
                date[0]++;
            }
        }
        k = 12 * 80000000;
        date[1] = abs(((date[1] + k + (mois)) % 12));
        if (date[1] == 0) {
            date[1] = 12;
        }
        if (date[1] < 10) {
            date[1] = "0".date[1];
        }
        return date[0] + "-" + date[1] + "-" + date[2];
    };

    /**
     * Démarre un chronomètre pour chronometrer la durée d'execution d'un bout de code,
     * il est possible d'utiliser plusieurs chronomètres en leurs spécifiant un identifaiant
     * l'identifaiant peut etre un nombre ou une chaine de caractère
     * 
     * @param {int|string} id Id du chronomètre
     */
    this.chronometer_start = function (id) {
        chronometer[(!isset(id) ? 0 : id)] = microtime(true);
    };

    /**
     * Retourne le temps mesuré par un chronomètre depuis son lancement
     * 
     * @param {int|string} id Id du chronomètre
     * @return {float} Temps mesuré par le chronomètre
     */
    this.chronometer_get = function (id) {
        return (microtime(true) - chronometer[(!isset(id) ? 0 : id)]);
    };

    /**
     * Parse un temps en secondes en jours/heur/minutes/secondes <br />
     * pour les temps inferieurs a 1 seconde, le parse peut se faire en milliseconde ou microsecondes
     * 
     * @param {int|double} secondes Secondes
     * @return {string} Temps 
     */
    this.parse_time = function (secondes) {
        if (secondes < 1) {
            var ms = parseInt((secondes * 1000));
            return(ms < 1 ? parseInt(secondes * 1000000) + " µs" : ms + " ms");
        }
        if (secondes < 60) {
            return secondes + " s";
        } else {
            var min = parseInt(secondes / 60);
            secondes %= 60;
            if (min < 60) {
                return min + " min " + secondes + " s";
            } else {
                var h = parseInt(min / 60);
                min %= 60;
                if (h < 24) {
                    return h + " h " + min + " min " + secondes + " s";
                } else {
                    var j = parseInt(h / 24);
                    h %= 24;
                    return j + " J " + h + " h " + min + " min " + secondes + " s";
                }
            }
        }
    };

    /**
     * Retourne un tableau d'information sur la date passé en paramètre
     * @param {string} date_us Date au format US
     * @return {array} https://secure.php.net/manual/fr/function.getdate.php
     */
    this.get_info_from_date = function (date_us) {
        return getdate(strtotime(date_us));
    };
}