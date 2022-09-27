<?php

/**
 * Cette classe permet de gérer une autentification via Google
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class google_oauth {

    /**
     * Client Google
     * @var Google\Client
     */
    private $_gclient;

    /**
     * Permet de vérifier que le SDK Google a bien été appelé qu'une fois.
     * @var boolean Permet de vérifier que le SDK Google a bien été appelé qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette classe permet de gérer une autentification via Google
     * 
     * @param string $clientId Client ID
     * @param string $clientSecret Client Secret
     * @param string $redirectUri Url de redirection après authentification
     * @param boolean $accesTypeOnline Access Online (true) ou Offline (false) (false par defaut)
     */
    public function __construct(
            $clientId,
            $clientSecret,
            $redirectUri,
            $accesTypeOnline = false
    ) {
        if (!self::$_called) {
            include __DIR__ . '/Oauth_Google/vendor/autoload.php';
        }
        $this->_gclient = new Google\Client();
        $this->_gclient->setClientId($clientId);
        $this->_gclient->setClientSecret($clientSecret);
        $this->_gclient->setRedirectUri($redirectUri);
        if ($accesTypeOnline) {
            $this->_gclient->setAccessType('online');
        } else {
            $this->_gclient->setAccessType('offline');
            //$this->_gclient->setApprovalPrompt('consent');
        }
        $this->_gclient->setIncludeGrantedScopes(true);
        $this->_gclient->addScope(Google\Service\Oauth2::OPENID);
        if (session::get_val("Google_AccesToken")) {
            $this->_gclient->setAccessToken(session::get_val("Google_AccesToken"));
        }
    }

    /**
     * Renseigne les autorisations a demander (un tableau des scopes)
     * @param array $scopes Autorisations a demander (un tableau des scopes)
     */
    public function addscopes($scopes = []) {
        foreach ($scopes as $scope) {
            $this->_gclient->addScope($scope);
        }
    }

    /**
     * Definis les Scopes et retourne l'url pour le bouton de connexion
     * @return string L'url pour le bouton de connexion
     */
    public function getLoginUrl() {
        return $this->_gclient->createAuthUrl();
    }

    /**
     * Retourne le token d'accès ( stocké dans session::get_val("Google_AccesToken") ) ou false
     * @return boolean|string Token d'accès ou false
     */
    public function getAccessToken_session() {
        if (isset($_GET["code"])) {
            $this->_gclient->authenticate($_GET["code"]);
        }
        try {
            if (!session::get_val("Google_AccessToken") and $token = $this->_gclient->getAccessToken()) {
                session::set_val("Google_AccessToken", $token);
            }
            return session::get_val("Google_AccessToken");
        } catch (Exception $e) {
            dwf_exception::print_exception($e);
            return false;
        }
    }

    /**
     * Retourne l'access token, plus d'info dans session::get_val("Google_AccessToken");
     * @return string Access Token
     */
    public function get_access_token() {
        return (session::get_val("Google_AccessToken") ? session::get_val("Google_AccessToken")["access_token"] : "");
    }

    /**
     * Retourne la fiche OpenId (userInfo) de l'utilisateur
     * @return array UserInfo
     */
    public function get_OpenId() {
        return json_decode(service::HTTP_GET("https://openidconnect.googleapis.com/v1/userinfo?access_token={$this->get_access_token()}"), true);
    }

    /**
     * Retourne le client Google our exploiter les scopes
     * @return Google\Client Client Google
     */
    public function get_google_client() {
        return $this->_gclient;
    }

}
