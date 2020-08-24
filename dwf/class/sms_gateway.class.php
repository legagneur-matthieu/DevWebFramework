<?php

/**
 * Cette classe permet de faciliter l'utilisation d'un gateway SMS afin de pouvoir envoyer des SMS depuis une application Web.
 * Cette classe a été conçu pour fonctionner par défaut avec le logiciel SMS Gateway installé sur un appareil Android.
 * https://dwf.sytes.net/smsgateway
 * Si vous utilisez un autre progranme, veillez à adapter les paramètres en conséquence.
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class sms_gateway {

    /**
     * Paramètres du Gateway 
     * @var array Paramètres du Gateway 
     */
    private $gateway;

    /**
     * Cette classe permet de faciliter l'utilisation d'un gateway SMS afin de pouvoir envoyer des SMS depuis une application Web.
     * Cette classe a été conçu pour fonctionner par défaut avec le logiciel SMS Gateway installé sur un appareil Android.
     * https://dwf.sytes.net/smsgateway
     * Si vous utilisez un autre progranme, veillez à adapter les paramètres en conséquence.
     * 
     * @param string $gateway_host Adresse IP ou DNS du Gateway
     * @param int $gateway_port Port du Gateway
     * @param string $gateway_page_send Page du service d'envoi de SMS (correspond à "sendmsg" dans http://host:port/sendmsg)
     * @param string $gateway_page_index Page d'accueil du gateway servant à verifier si le service est joignable (la page doit renvoyer un statut code 1xx, 2xx ou 3xx)
     */
    public function __construct($gateway_host, $gateway_port = 8080, $gateway_page_send = "sendsms", $gateway_page_index = "run") {
        $this->gateway = array(
            "host" => $gateway_host,
            "port" => $gateway_port,
            "page_send" => $gateway_page_send,
            "page_index" => $gateway_page_index
        );
    }

    /**
     * Retourne si le service répond ou non
     * @return boolean Retourne si le service répond ou non
     */
    public function is_runing() {
        $statu = (int) strtr(service::HTTP_get_STATUS($this->gateway["host"], $this->gateway["port"], $this->gateway["page_index"]), array("HTTP/1.1 " => ""));
        return ($statu < 400);
    }

    /**
     * Envoi un SMS par URL
     * @param array $params tableau contenant les informations d'envoi ( array("phone"=>"0654321987","text"=>"le sms",["psw"=>"motdepasse"]) )
     * @param string $methode get ou post, post par defaut
     * @param boolean $ssl utiliser le protocole HTTPS ? (true ou false, false par défaut)
     * @return boolean succès ou echec ( si echec, verifiez la configuration du gateway)
     */
    public function send_by_url($params, $methode = "post", $ssl = false) {
        $return = false;
        if ($r = $this->is_runing()) {
            if ($methode == "post") {
                $return = service::HTTP_POST_REQUEST(($ssl ? "https://" : "http://") . $this->gateway["host"] . ":" . $this->gateway["port"] . "/" . $this->gateway["page_send"], $params);
            } else {
                $get = "?";
                foreach ($params as $key => $value) {
                    $get .= "$key=" . rawurlencode($value) . "&";
                }
                $return = service::HTTP_GET(($ssl ? "https://" : "http://") . $this->gateway["host"] . ":" . $this->gateway["port"] . "/" . $this->gateway["page_send"] . $get);
            }
        }
        return $return;
    }

}
