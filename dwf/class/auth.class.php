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
     * Cette classe gère l'authentification d'un utilisateur
     * 
     * @param string $table table des utilisateurs
     * @param string $tuple_login tuble du login
     * @param string $tuple_psw tuple du mot de passe
     * @param boolean $call_session_start l'application doit lancer un session_start() (true/false)
     */
    public function __construct($table, $tuple_login, $tuple_psw, $call_session_start = false) {
        if ($call_session_start) {
            session_start();
        }
        $this->_table = $table;
        $this->_tuple_login = $tuple_login;
        $this->_tuple_psw = $tuple_psw;
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
        $form = new form();
        if (isset($_POST['auth_login'])) {
            if ($form->csrf_check()) {
                $this->exec_auth();
            }
        } else {
            ?>
            <div class="class_auth">
                <?php
                $form->input("Login", "auth_login", "text");
                $form->input("Mot de passe", "auth_psw", "password");
                $form->csrf_token();
                $form->submit("btn-block btn-primary", "Connexion");
                echo $form->render();
                ?>
            </div>
            <?php
        }
    }

    /**
     * Exécution du formulaire d'authentification
     */
    private function exec_auth() {
        $table = $this->_table;
        $req = $table::get_table_array("{$this->_tuple_login}=:{$this->_tuple_login} and ({$this->_tuple_psw}=:{$this->_tuple_psw}", [
            ":{$this->_tuple_login}" => $_POST['auth_login'],
            ":{$this->_tuple_psw}" => application::hash($_POST['auth_psw'], true)
        ]);
        if (isset($req[0]["id"])) {
            session::set_auth(true);
            session::set_user($req[0]["id"]);
            js::redir("");
        } else {
            $_POST["auth_psw"] = "********";
            $_REQUEST["auth_psw"] = "********";
            js::alertify_alert_redir("Votre login ou mot de passe est invalide !", "");
        }
    }

    /**
     * Méthode de déconnexion de l'utilisateur
     */
    public static function unauth() {
        session_destroy();
    }
}
