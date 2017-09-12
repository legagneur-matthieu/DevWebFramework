<?php

/**
 * Cette classe permet d'envoyer des mails, la configuration du SMTP se fait dans la classe config.class.php
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class mail {

    /**
     * Permet de vérifier que la librairie flexslider a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie flexslider a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Instance de PHPMailer
     * @var PHPMailer Instance de PHPMailer
     */
    private $_phpmailer;

    /**
     * Cette classe permet d'envoyer des mails, la configuration du SMTP ce fait dans la class config.class.php     * 
     */
    public function __construct() {
        if (!self::$_called) {
            foreach (glob(__DIR__ . "/phpmailer/class.*.php") as $phpmailer_class) {
                include_once $phpmailer_class;
            }
            self::$_called = true;
        }
        $this->_phpmailer = new PHPMailer(true);
        $this->_phpmailer->Host = config::$_SMTP_host;
        if ($this->_phpmailer->SMTPAuth = config::$_SMTP_auth) {
            $this->_phpmailer->Username = config::$_SMTP_login;
            $this->_phpmailer->Password = config::$_SMTP_psw;
        }
        $this->_phpmailer->SMTPSecure = 'tls';
        $this->_phpmailer->Port = 587;
        $this->_phpmailer->IsSMTP();
        $this->_phpmailer->IsHTML();
        $this->_phpmailer->CharSet = "utf-8";
    }

    /**
     * Envoi un mail
     * @param string $from Email de l'émetteur 
     * @param string $from_name Nom / pseudo de l'émetteur
     * @param string $to Email du destinataire
     * @param string $subject Sujet du massage
     * @param string $msg Contenu du message ( peut-être en HTML )
     * @return boolean Succes ou erreur
     */
    public function send($from, $from_name, $to, $subject, $msg) {
        $this->_phpmailer->setFrom($from, $from_name);
        $this->_phpmailer->AddAddress($to);
        $this->_phpmailer->Subject = $subject;
        $message = '<!DOCTYPE HTML><html lang="fr-FR"><head><meta charset="UTF-8"></head><body>' . $msg . '</body></html>';
        $this->_phpmailer->Body = $message;
        $send = $this->_phpmailer->Send();
        $this->_phpmailer->ClearAddresses();
        return $send;
    }

    /**
     * Ajoute une piece jointe au mail
     * @param string $path chemin d'acces du fichier à joindre
     * @param string $name (facultatif) renomme le fichier avant envoi
     */
    public function join_file($path, $name = "") {
        $this->_phpmailer->addAttachment($path, $name);
    }

}
