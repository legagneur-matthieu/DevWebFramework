<?php

/**
 * Cette classe permet de chiffrer et déchiffrer des messages<br />
 * la taille de la clé limite la taille maximale du message à chiffrer : <br />
 * 1024 bits = 117 caractères <br />
 * 2048 bits = 245 caractères <br />
 * 4096 bits = 501 caractères
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.fr>
 */
class ssl {

    /**
     * Instance de Crypt_RSA
     * @var Crypt_RSA Instance de Crypt_RSA
     */
    private $_RSA;

    /**
     * Permet de vérifier que la librairie jsencrypt a bien été appelée qu'une fois.
     * @var array Permet de vérifier que la librairie flexslider a bien été appelée qu'une fois.
     */
    private static $_called = array(false, false);

    /**
     * Cette classe permet de chiffrer et déchiffrer des messages <br />
     * la taille de la clé limite la taille maximale du message à chiffrer : <br />
     * 1024 bits = 117 caractères <br />
     * 2048 bits = 245 caractères <br />
     * 4096 bits = 501 caractères
     * @param int $bits Longueur de la clé ( en bits, 1024 par defaut)
     */
    public function __construct($bits = 1024) {
        $this->init();
        $this->_RSA = new Crypt_RSA();
        $this->_RSA->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        if (!session::get_val("ssl_private_key")) {
            extract($this->_RSA->createKey($bits));
            session::set_val("ssl_private_key", $privatekey);
            session::set_val("ssl_public_key", $publickey);
        }
    }

    /**
     * Charge les librairies PHP nécéssaires 
     */
    private function init() {
        //include_once __DIR__.'/phpseclib/phpsec_loader.class.php';
        include_once __DIR__ . '/phpseclib/Math/BigInteger.php';
        include_once __DIR__ . '/phpseclib/Crypt/Random.php';
        include_once __DIR__ . '/phpseclib/Crypt/Hash.php';
        include_once __DIR__ . '/phpseclib/Crypt/AES.php';
        include_once __DIR__ . '/phpseclib/Crypt/DES.php';
        include_once __DIR__ . '/phpseclib/Crypt/TripleDES.php';
        include_once __DIR__ . '/phpseclib/Crypt/RSA.php';
    }

    /**
     * Retourne la clé publique
     * @return string Clé publique
     */
    public function get_public_key() {
        return session::get_val("ssl_public_key");
    }

    /**
     * Chiffre un message avec la clé publique
     * @param string $data message à chiffrer
     * @return string message chiffré (en binaire!)
     */
    public function encrypt($data) {
        $this->_RSA->loadKey($this->get_public_key());
        return $this->_RSA->encrypt($data);
    }

    /**
     * Déchiffre un message avec la clé privée
     * @param string $data message à déchiffrer
     * @return string Message en clair
     */
    public function decrypt($data) {
        $this->_RSA->loadKey(session::get_val("ssl_private_key"));
        return $this->_RSA->decrypt($data);
    }

    /**
     * Charge les librairie JS et chiffre la saisie des utilisateurs dans les formulaires de la page
     * ATTENTION : une saisie d'utilisateur ne doit pas excéder 117 caractères !
     * @param boolean $enable_client_ssl l'utilisateur a t-il besoin d'une paire de clés pour lui aussi chiffrer/déchiffrer des messages du serveur ? (true/false, false par defaut)
     */
    public function ssl_js($enable_client_ssl = false) {
        if (!self::$_called[0]) {
            echo html_structures::script("../commun/src/js/jsencrypt/jsencrypt.js").
                    html_structures::script("../commun/src/js/jsencrypt/ssl.jsencrypt.js");
            ?>
            <script type="text/javascript">
                $prefix = '<?= config::$_prefix; ?>';
            </script>
            <?php
            self::$_called[0] = true;
        }
        if (!self::$_called[1] and $enable_client_ssl) {
            echo html_structures::script("../commun/src/js/jsencrypt/client_ssl.jsencrypt.js");
            self::$_called[1] = true;
        }
    }

    /**
     * Dechiffre les variables $_POST ( cf $this->ssl_js() )
     */
    public function decrypt_post() {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $this->decrypt(base64_decode($value));
        }
    }

    /**
     * Affiche un message chiffré avec la clé publique de l'utilisateur ( cf $this->ssl_js(TRUE) )
     * @param string $html chaine HTML de longueur n'excédant pas 117 caractères
     */
    public function encrypt_html($html) {
        if (session::get_val("client_ssl_public_key") and self::$_called[1]) {
            $this->_RSA->loadKey(session::get_val("client_ssl_public_key"));
            echo tags::tag("div",["class"=>"jsencrypt"],base64_encode($this->_RSA->encrypt($html)));
        } else {
            echo tags::tag("div",["class"=>"alert alert-danger"],tags::tag("p",[],"Une erreur est survenu, ce comptenu ne peux être affiché"));
        }
    }

}
