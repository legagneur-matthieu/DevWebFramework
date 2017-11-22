<?php

/**
 * Cette classe permet de gérer une autentification via FaceBook
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class fb {

    const FB_SESSION_KEY = "fb_token";

    private $_fb;
    private $_fb_helper;

    /**
     * Permet de vérifier que le SDK FB a bien été appelé qu'une fois.
     * @var boolean Permet de vérifier que le SDK FB a bien été appelé qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette classe permet de gérer une autentification via FaceBook
     * 
     * @param string $app_id app_id de votre application FaceBook
     * @param string $app_secret secret de votre application FaceBook
     * @param string $default_graph_version Version de Graph
     */
    public function __construct($app_id, $app_secret, $default_graph_version = "v2.11") {
        if (!self::$_called) {
            include __DIR__ . '/php-graph-sdk-5.x/src/Facebook/autoload.php';
            self::$_called = true;
        }
        $this->_fb = new Facebook\Facebook(
                ['app_id' => $app_id, 'app_secret' => $app_secret, 'default_graph_version' => $default_graph_version]
        );
        $this->_fb_helper = $this->_fb->getRedirectLoginHelper();
    }

    /**
     * Retourne l'url pour le bouton de connexion
     * @param string $redirect_url URL de redirection
     * @param array $permissions Tableau des permitions demandées pour l'authentification ( le mail est demandé par defaut)
     * @return string URL de connexion
     */
    public function getLoginUrl($redirect_url, $permissions = ["email"]) {
        return $this->_fb_helper->getLoginUrl($redirect_url, $permissions);
    }

    /**
     * Retourne l'url pour le bouton de deconnexion
     * @param string $redirect_url URL de redirection
     * @return string URL de deconnexion
     */
    public function getLogoutUrl($redirect_url) {
        return $this->_fb_helper->getLogoutUrl(session::get_val(fb::FB_SESSION_KEY), $redirect_url);
    }

    /**
     * Retourne le token d'accès ( stocké dans session::get_val(fb::FB_SESSION_KEY) ) ou false
     * @return boolean|string token d'accès ou false
     */
    public function getAccessToken_session() {
        try {
            if (!session::get_val(fb::FB_SESSION_KEY) and $token = $this->_fb_helper->getAccessToken()) {
                session::set_val(fb::FB_SESSION_KEY, $token->getValue());
            }
            return session::get_val(fb::FB_SESSION_KEY);
        } catch (Exception $e) {
            dwf_exception::print_exception($e);
            return false;
        }
    }

    /**
     * Permet de lancer une requete a l'API de FaceBook et retourne le resultat
     * @param string $method methode a utiliser (get, post ou del)
     * @param string $req requete à envoyer
     * @return mixed reponse de l'API
     */
    public function request($method = "get", $req = "/me") {
        return (session::get_val(fb::FB_SESSION_KEY) ? $this->_fb->$method($req, session::get_val(fb::FB_SESSION_KEY)) : false);
    }

    /**
     * Retourne les données de l'utilisateur connecté (GraphUser)
     * @return \Facebook\GraphNodes\GraphUser Données de l'utilisateur
     */
    public function getGraphUser() {
        return $this->request()->getGraphUser();
    }

}
