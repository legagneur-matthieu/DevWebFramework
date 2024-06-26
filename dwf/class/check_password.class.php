<?php

/**
 * Cette classe permet d'appliquer une politique de mots de passe
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class check_password {

    /**
     * Tableau d'erreurs
     * @var array Tableau d'erreurs
     */
    private $_error = [];

    /**
     * Taille minimale des mots de passe
     * @var int Taille minimale des mots de passe
     */
    private $_minlen = 8;

    /**
     * Tableau des messages liés aux erreurs
     * @var type 
     */
    private $_errormsg = [
        "minlen" => "",
        "special" => "",
        "number" => "",
        "upper" => "",
        "lower" => "",
    ];

    /**
     * Cette classe permet d'appliquer une politique de mots de passe
     * @param string $password Mot de passe à verifier
     * @param int $minlen Longueur minimale du mot de passe (8 par defaut)
     * @param boolean $special Le mot de passe doit-il contenir au moins un caractère spécial ? (false par defaut)
     * ATTENTION : un caractère accentué est considéré comme un caractère special !
     * @param boolean $number Le mot de passe doit-il contenir au moins un chiffre ? (true par defaut)
     * @param boolean $upper Le mot de passe doit-il contenir au moins une majuscule ? (true par defaut)
     * @param boolean $lower Le mot de passe doit-il contenir au moins une minuscule ? (true par defaut)
     */
    public function __construct($password, $minlen = 8, $special = false, $number = true, $upper = true, $lower = true) {
        $this->_minlen = $minlen;
        $this->set_errormsg_minlen("Requiert " . $minlen . " caractères au minimum");
        $len = (function_exists("mb_strlen") ? mb_strlen($password) : strlen($password));
        if ($len < $minlen) {
            $this->_error[] = "minlen";
        }
        $this->set_errormsg_special("Requiert au moins un caractère spécial");
        if ($special and !preg_match("/[^a-zA-Z0-9]/", $password)) {
            $this->_error[] = "special";
        }
        $this->set_errormsg_number("Requiert au moins un chiffre");
        if ($number and !preg_match("/[0-9]/", $password)) {
            $this->_error[] = "number";
        }
        $this->set_errormsg_upper("Requiert au moins une majuscule");
        if ($upper and !preg_match("/[A-Z]/", $password)) {
            $this->_error[] = "upper";
        }
        $this->set_errormsg_lower("Requiert au moins une minuscule");
        if ($lower and !preg_match("/[a-z]/", $password)) {
            $this->_error[] = "lower";
        }
    }

    /**
     * Retourne true si le mot de passe est conforme à la politique de mot de passe, false si non
     * @return boolean Retourne true si le mot de passe est conforme à la politique de mot de passe, false si non
     */
    public function is_valid() {
        return (count($this->_error) == 0);
    }

    /**
     * Retoune le tableau d'erreurs
     * @return array Retoune le tableau d'erreurs
     */
    public function get_error() {
        return $this->_error;
    }

    /**
     * Retourne la longueur minimale du mot de passe renseigné dans le constructeur (8 par defaut)
     * @return int Retourne la longueur minimale du mot de passe renseigné dans le constructeur (8 par defaut)
     */
    public function get_minlen() {
        return $this->_minlen;
    }

    /**
     * Modifie le message d'erreur lié à la longueur du mot de passe
     * @param string $msg message
     */
    public function set_errormsg_minlen($msg) {
        $this->_errormsg["minlen"] = $msg;
    }

    /**
     * Modifie le message d'erreur lié au manque de caractère spécial dans le mot de passe
     * @param string $msg message
     */
    public function set_errormsg_special($msg) {
        $this->_errormsg["special"] = $msg;
    }

    /**
     * Modifie le message d'erreur lié au manque de chiffre dans le mot de passe
     * @param string $msg message
     */
    public function set_errormsg_number($msg) {
        $this->_errormsg["number"] = $msg;
    }

    /**
     * Modifie le message d'erreur lié au manque de majuscule dans le mot de passe
     * @param string $msg message
     */
    public function set_errormsg_upper($msg) {
        $this->_errormsg["upper"] = $msg;
    }

    /**
     * Modifie le message d'erreur lié au manque de minuscule dans le mot de passe
     * @param string $msg message
     */
    public function set_errormsg_lower($msg) {
        $this->_errormsg["lower"] = $msg;
    }

    /**
     * Affiche le message d'erreur à l'utilisateur afin qu'il corrige son mot de passe
     * @param string $msg message
     */
    public function print_errormsg($msg = "Erreur ! votre mot de passe :") {
        if (!$this->is_valid()) {
            $li = "";
            foreach ($this->_error as $error) {
                $li .= tags::tag("li", [], $this->_errormsg[$error]);
            }
            echo tags::tag("div", ["class" => "alert alert-danger"], tags::tag("p", [], $msg) . tags::tag("ul", [], $li));
        }
    }

}
