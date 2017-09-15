<?php

/**
 * Cette classe permet de faciliter l'utilisation d'un gateway SMS afin de pouvoir envoyer et recevoir des SMS depuis une application Web.
 * Cette classe a été conÃ§u pour fonctionner par défaut avec le logiciel SMS Gateway installé sur un appareil Android.
 * https://play.google.com/store/apps/details?id=eu.apksoft.android.smsgateway
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
     * Cette classe permet de faciliter l'utilisation d'un gateway SMS afin de pouvoir envoyer et recevoir des SMS depuis une application Web.
     * Cette classe a été conÃ§u pour fonctionner par défaut avec le logiciel SMS Gateway installé sur un appareil Android.
     * https://play.google.com/store/apps/details?id=eu.apksoft.android.smsgateway
     * Si vous utilisez un autre progranme, veillez à adapter les paramètres en conséquence.
     * 
     * @param string $gateway_host Adresse IP ou DNS du Gateway
     * @param string $password Mot de passe du Gateway
     * @param int $gateway_port Port du Gateway
     * @param string $gateway_page_send Page du service d'envoi de SMS (correspond à "sendmsg" dans http://host:port/sendmsg)
     * @param string $gateway_page_index Page d'accueil du gateway servant à verifier si le service est joignable (la page doit renvoyer un statut code 1xx, 2xx ou 3xx)
     */
    public function __construct($gateway_host, $password = "", $gateway_port = 9090, $gateway_page_send = "sendsms", $gateway_page_index = "") {
        $this->gateway = array(
            "host" => $gateway_host,
            "port" => $gateway_port,
            "page_send" => $gateway_page_send,
            "page_index" => $gateway_page_index,
            "password" => $password
        );
        new entity_generator(array(
            array("id", "int", true),
            array("sender", "int", false),
            array("sended", "int", false),
            array("user", "int", false),
            array("date", "text", false),
            array("phone", "string", false),
            array("smscenter", "string", false),
            array("msg", "string", false)
                ), "sms");
        application::$_bdd->query("delete from sms where date<'" . time::date_plus_ou_moins_mois(date("Y-m-d H:i:s"), -24) . "'");
        if (!strstr($_SERVER["SCRIPT_NAME"], "services/index.php")) {
            $this->make_service();
            service::HTTP_POST("http://" . $_SERVER["HTTP_HOST"] . strtr($_SERVER["SCRIPT_NAME"], array("index.php" => "services/index.php")), array("service" => "sms_service", "action" => "send_unsended"));
        }
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
     * Affiche les messages reÃ§us
     */
    public function incoming() {
        $this->make_service();
        $sms_income = sms::get_table_array("sender=0 order by date desc");
        $data = array();
        foreach ($sms_income as $sms) {
            $date = explode(" ", $sms["date"]);
            $data[] = array(
                html_structures::time($sms["date"], time::date_us_to_fr($date[0]) . " " . $date[1]),
                $sms["phone"] . " (" . $sms["smscenter"] . ")",
                $sms["msg"]
            );
        }
        echo html_structures::table(array("Date", "Phone (smscenter)", "Message"), $data, '', 'sms_incoming');
        js::datatable('sms_incoming', array("order" => '[[ 0, "desc" ]]'));
    }

    /**
     * Affiche les messages envoyés
     */
    public function outcoming() {
        $this->make_service();
        $sms_income = sms::get_table_array("sender=1 order by date desc");
        $data = array();
        foreach ($sms_income as $sms) {
            $date = explode(" ", $sms["date"]);
            $data[] = array(
                html_structures::time($sms["date"], time::date_us_to_fr($date[0]) . " " . $date[1]),
                $sms["phone"],
                ($sms["sended"] == 1 ? "oui" : "non"),
                $sms["msg"]
            );
        }
        echo html_structures::table(array("Date", "Phone", "Envoyé", "Message"), $data, '', 'sms_outcoming');
        js::datatable('sms_outcoming', array("order" => '[[ 0, "desc" ]]'));
    }

    /**
     * Envoi de SMS par URL
     * @param array $params tableau contenant les informations d'envoi ( array("phone"=>"0654321987","text"=>"le sms") )
     * @param string $methode get ou post, get par defaut
     * @param boolean $ssl utiliser le protocole HTTPS ? (true ou false, false par défaut)
     * @return boolean succès ou echec ( si echec, verifiez la configuration du gateway, le sms sera envoyé à la prochaine verification !)
     */
    public function send_by_url($params, $methode = "get", $ssl = false) {
        $return = false;
        if ($r = $this->is_runing()) {
            if ($methode == "post") {
                $return = (service::HTTP_POST(($ssl ? "https://" : "http://") . $this->gateway["host"] . ":" . $this->gateway["port"] . "/" . $this->gateway["page_send"], $params) == TRUE);
            } else {
                $get = "?";
                foreach ($params as $key => $value) {
                    $get .= "$key=" . rawurlencode($value) . "&";
                }
                $get .= "password=" . $this->gateway["password"];
                $return = (service::HTTP_GET(($ssl ? "https://" : "http://") . $this->gateway["host"] . ":" . $this->gateway["port"] . "/" . $this->gateway["page_send"] . $get) == TRUE);
            }
        }
        sms::ajout(1, ($r ? 1 : 0), (session::get_user() ? session::get_user() : 0), (date("Y-m-d H:i:s")), $params["phone"], 0, $params["text"]);
        return $return;
    }

    /**
     * comming soon !
     * @param string $from From
     * @param string $from_name From name
     * @param string $to To
     * @param string $subject Subject
     * @param string $msg Msg
     */
    public function send_by_mail($from, $from_name, $to, $subject, $msg) {
        //(new mail())->send($from, $from_name, $to, $subject, $msg);
    }

    /**
     * créé le service du gateway (requiert que le projet soit créé avec la prise en charge de services )
     */
    private function make_service() {
        $file_service = "./services/sms_service.service.php";
        if (!file_exists($file_service)) {
            file_put_contents($file_service, '<?php /** * Service de reception et de renvois des SMS */ class sms_service { /** * Parametres du gateway * @var array Parametres du gateway */ private $_gateway = array( "host" => "' . $this->gateway["host"] . '", "port" => "' . $this->gateway["port"] . '", "page_send" => "' . $this->gateway["page_send"] . '", "page_index" => "' . $this->gateway["page_index"] . '", "password" => "' . $this->gateway["password"] . '" ); /** * SMS reÃ§us par sms_service->receive() * @var array SMS reÃ§us par sms_service->receive() */ private $_sms = array( "id" => 0, "phone" => "", "smscenter" => "", "text" => "" ); /** * Service de reception et de renvois des SMS */ public function __construct() { if (isset($_REQUEST["action"])) { switch ($_REQUEST["action"]) { case "receive": $this->receive(); break; case "send_unsended": $this->send_unsended(); break; default: dwf_exception::throw_exception(624, array("_s_" => __CLASS__, "_a_" => $_REQUEST["action"])); break; } } else { dwf_exception::throw_exception(623, array("_s_" => __CLASS__)); } } /** * Methode de réception des SMS */ private function receive() { $this->_sms["phone"] = empty($_GET["phone"]) ? (isset($headers["phone"]) ? $headers["phone"] : false) : $_GET["phone"]; $this->_sms["smscenter"] = empty($_GET["smscenter"]) ? (isset($headers["smscenter"]) ? $headers["smscenter"] : false) : $_GET["smscenter"]; $this->_sms["text"] = empty($_GET["text"]) ? (isset($headers["text"]) ? rawurldecode($headers["text"]) : false) : rawurldecode($_GET["text"]); sms::ajout(0, 0, 0, $date = date("Y-m-d H:i:s"), $this->_sms["phone"], $this->_sms["smscenter"], $this->_sms["text"]); $sms = sms::get_table_array("date=\'" . application::$_bdd->protect_var($date) . "\' and phone=\'" . application::$_bdd->protect_var($this->_sms["phone"]) . "\' and smscenter=\'" . application::$_bdd->protect_var($this->_sms["smscenter"]) . "\' and msg=\'" . application::$_bdd->protect_var($this->_sms["text"]) . "\' order by id desc"); $this->_sms["id"] = $sms[0]["id"]; } /** * Methode d\'envois des sms en attente / non envoyé ( suite a une défaillance du gateway) */ private function send_unsended() { $sms_gateway = new sms_gateway($this->_gateway["host"], $this->_gateway["port"], $this->_gateway["page_send"], $this->_gateway["page_index"]); $smss = sms::get_collection("sender=\'1\' and sended=\'0\'"); if (is_array($smss) and count($smss) > 0) { foreach ($smss as $sms) { if ($sms_gateway->send_by_url(array( "phone" => $sms->get_phone(), "text" => $sms->get_msg(), "password" => $sms_gateway["password"] ))) { $sms->set_sended(1); $sms->set_date(date("Y-m-d H:i:s")); } else { break;}}}}}');
        }
    }

}
