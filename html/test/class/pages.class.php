<?php

/** * Cette classe sert de "Vue" à votre application, * vous pouvez y développer votre application comme bon vous semble : * HTML, créér et appeler une fonction "private" dans une fonction "public", faire appel à des classes exterieures ... * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> */
class pages { /** * Cette classe sert de "Vue" à votre application, * vous pouvez y développer votre application comme bon vous semble : * HTML, créé et appelle une fonction "private" dans une fonction "public", faire appel à des classes exterieures ... */

    public function __construct() {
        new robotstxt();
    }

    /**     * Entete des pages */
    public function header() {
        ?> <header class="page-header label-info">
            <h1>test <br /><small>Description de test</small></h1> 
        </header> <?php
    }

    /**     * Pied des pages */
    public function footer() {
        ?> <footer> <hr /> <p> 2017-<?php echo date("Y"); ?> D&eacute;velopp&eacute; par [VOUS]</p> <!--[if (IE 6)|(IE 7)]> <p><big>Ce site n'est pas compatible avec votre version d'internet explorer !</big></p> <![endif]--> </footer> <?php
    }

    /**     * Fonction par défaut / page d'accueil */
    public function index() {
        ?> <p>[Votre contenu]</p> <?php
    }

    /**     * Exemple de login */
    public function login() {
        $auth = new auth("user", "login", "psw");
        if (session::get_auth()) {
            js::redir("index.php");
        }
    }

    public function deco() {
        auth::unauth();
        js::redir("index.php");
    }

}
