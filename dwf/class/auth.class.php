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
     * Le formulaire doit-il posséder un token
     * @var boolean Le formulaire doit-il posséder un token
     */
    private $_use_token;

    /**
     * Cette classe gère l'authentification d'un utilisateur
     * 
     * @param string $table table des utilisateurs
     * @param string $tuple_login tuble du login
     * @param string $tuple_psw tuple du mot de passe
     * @param boolean $use_token Le formulaire doit-il posséder un token (sécurité, true/false, false par defaut)
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
                if ((new form())->validate_token()) {
                    $this->exec_auth();
                } else {
                    js::alertify_alert_redir("Token invalide!", "");
                }
            } else {
                $this->exec_auth();
            }
        } else {
            ?>
            <div class="class_auth">
                <?php
                $form = new form();
                $form->input("Login", "auth_login", "text");
                $form->input("Mot de passe", "auth_psw", "password");
                $form->submit("btn-block btn-primary", "Connexion");
                if ($this->_use_token) {
                    $form->token();
                }
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
        $req = $table::get_table_array("{$this->_tuple_login}=:{$this->_tuple_login} and {$this->_tuple_psw}=:{$this->_tuple_psw}", $params = [
                    ":{$this->_tuple_login}" => $_POST['auth_login'],
                    ":{$this->_tuple_psw}" => application::hash($_POST['auth_psw'])
        ]);
        if (isset($req[0]["id"])) {
            session::set_auth(true);
            session::set_user($req[0]["id"]);
            js::redir("");
        } else {
            $_POST["auth_psw"] = "********";
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
