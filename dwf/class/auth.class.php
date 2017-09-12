<?php

/**
 * Cette classe gère l'authentification d'un utilisateur
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class auth {

    /**
     * Table des utilisateurs
     * 
     * @var string table des utilisateurs
     */
    private $_table;

    /**
     * Tuple du login
     * 
     * @var string tuple du login
     */
    private $_tuple_login;

    /**
     * Tuple du mot de passe
     * 
     * @var string tuple du mot de passe
     */
    private $_tuple_psw;

    /**
     * Le formulaire doit-il posseder un token
     * @var boolean Le formulaire doit-il posseder un token
     */
    private $_use_token;

    /**
     * Cette classe gère l'authentification d'un utilisateur
     * 
     * @param string $table table des utilisateurs
     * @param string $tuple_login tuble du login
     * @param string $tuple_psw tuple du mot de passe
     * @param boolean $use_token Le formulaire doit-il posseder un token (sécurité, true/false, false par defaut)
     * @param boolean $call_session_start l'application doit lancer un session_start() (true/false)
     */
    public function __construct($table, $tuple_login, $tuple_psw, $use_token = false, $call_session_start = FALSE) {
        if ($call_session_start) {
            session_start();
        }
        $this->_table = $table;
        $this->_tuple_login = $tuple_login;
        $this->_tuple_psw = $tuple_psw;
        $this->_use_token = $use_token;
        $this->denied_or_granted();
    }

    /**
     * Affiche le formulaire d'authentification si non authentifié
     */
    private function denied_or_granted() {
        if (!session::get_auth()) {
            $this->form_auth();
        }
    }

    /**
     * Affiche le formulaire d'authentification
     */
    private function form_auth() {
        if (isset($_POST['auth_login'])) {
            if ($this->_use_token) {
                if ($form->validate_token()) {
                    $this->exec_auth();
                } else {
                    js::alert("Token invalide!");
                    js::redir("");
                }
            } else {
                $this->exec_auth();
            }
        } else {
            ?>
            <div class="class_auth">
                <?php
                $form = new form();
                $form->new_form();
                $form->input("Login", "auth_login", "text");
                $form->input("Mot de passe", "auth_psw", "password");
                $form->submit("btn-block btn-default", "Connexion");
                $form->close_form($this->_use_token);
                ?>
            </div>
            <?php
        }
    }

    /**
     * Exécution du formulaire d'authentification
     */
    private function exec_auth() {
        $req = application::$_bdd->fetch("select count(*) as count from " . $this->_table . " where " . $this->_tuple_login . "='" . application::$_bdd->protect_var($_POST['auth_login']) . "';");
        $report = false;
        if ($req[0]["count"] != 0) {
            $req = application::$_bdd->fetch("select * from " . $this->_table . " where " . $this->_tuple_login . "='" . application::$_bdd->protect_var($_POST['auth_login']) . "';");
            if ($this->checkPassword($_POST['auth_psw'], $req[0][$this->_tuple_psw])) {
                session::set_auth(true);
                session::set_user($req[0]['id']);
                js::redir("");
            } else {
                js::alert("Votre mot de passe est invalide !");
                $report = true;
            }
        } else {
            js::alert("Votre login est invalide !");
            $report = true;
        }
        if ($report) {
            $_POST["auth_psw"] = "********";
        }
    }

    /**
     * Vérifie le mot de passe (la fonction hash est à modifier à votre convenance )
     * 
     * @param string $checkPass mot de passe reÃ§u
     * @param string $realPass mot de passe stocké
     * @return boolean
     */
    private function checkPassword($checkPass, $realPass) {
        return $realPass == hash(config::$_hash_algo, $checkPass);
    }

    /**
     * Méthode de déconnexion de l'utilisateur
     */
    public static function unauth() {
        session_destroy();
    }

}
