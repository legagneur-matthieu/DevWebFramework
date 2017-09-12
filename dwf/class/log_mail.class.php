<?php

/**
 * Cette classe permet de vous envoyer automatiquement un mail en cas de comportement anormal de votre application
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class log_mail {

    /**
     * Instance de mail
     * 
     * @var mail Instance de mail 
     */
    private $_mail;

    /**
     * IP du client
     * 
     * @var string IP du client
     */
    private $_ip_client;

    /**
     * Sujet de l'Email
     * 
     * @var string Sujet de l'Email
     */
    private $_subject;

    /**
     * Email d'envoi
     * 
     * @var string Email d'envoi
     */
    private $_from;

    /**
     * Email de r�ception
     * 
     * @var string Email de r�ception
     */
    private $_to;

    /**
     * Contenu de l'email
     * 
     * @var string Comptenu de l'email
     */
    private $_msg;

    /**
     * Cette classe permet de vous envoyer automatiquement un mail en cas de comportement anormal de votre application
     * 
     * @param string $smtp adresse du serveur smtp
     * @param string $from email d'envoi
     * @param string $to email de r�ception
     */
    public function __construct($from, $to) {
        $this->_mail = new mail();
        $this->_from = $from;
        $this->_to = $to;
        $this->_msg = "";
        $this->_ip_client = $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Fonction d'�criture du log
     * 
     * @param string $type type de message / niveau de gravit� du message
     * @param string $message message � �crire dans le log
     */
    private function write_log($type, $message) {
        $msg = date("Y/m/d H:i:s") . " " . $type . " ( " . $this->_ip_client . " ) " . $message . "\n";
        $this->_msg .= $msg . " <br />";
    }

    /**
     * Écrit un message de type "info" dans le log
     * 
     * @param string $message message � �crire dans le log
     */
    public function info($message) {
        $this->write_log('[Info]', $message);
    }

    /**
     * Écrit un message de type "warning" dans le log
     * 
     * @param string $message message � �crire dans le log
     */
    public function warning($message) {
        $this->write_log('[Warning]', $message);
    }

    /**
     * Écrit un message de type "s�v�re" dans le log
     * 
     * @param string $message message � �crire dans le log
     */
    public function severe($message) {
        $this->write_log('[Severe]', $message);
    }

    /**
     * Destructeur : envoie le mail s'il n'est pas vide
     */
    public function __destruct() {
        if ($this->_msg != "") {
            $this->_mail->send($this->_from, $this->_to, $this->_subject, $this->_msg);
        }
    }

}
