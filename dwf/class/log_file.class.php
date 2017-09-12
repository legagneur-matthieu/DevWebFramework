<?php

/**
 * Cette classe permet de créer un log sous forme de fichier.
 * Elle vous permet d'enregistrer les comportements anormaux de votre application
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class log_file {

    /**
     * Fichier de log
     * 
     * @var string Fichier de log
     */
    private $_log;

    /**
     * IP du client
     * 
     * @var string IP du client
     */
    private $_ip_client;

    /**
     * Cette classe permet de créer un log sous forme de fichier.
     * Elle vous permet d'enregistrer les comportements anormaux de votre application
     * 
     * @param boolean $a_log_a_day la classe doit-elle créer un fichier par jour ? true/false (false par defaut : créé un fichier unique pour le log)
     */
    public function __construct($a_log_a_day = FALSE) {
        $this->_log = __DIR__ . "/../../dwf/log/log_" . config::$_prefix . ($a_log_a_day ? "_" . date("Y.m.d") : "") . ".txt";
        $this->_ip_client = $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Fonction d'écriture du log
     * 
     * @param string $type type de message / niveaux de gravité du message
     * @param string $message message à écrire dans le log
     */
    private function write_log($type, $message) {
        file_put_contents($this->_log, date("Y/m/d H:i:s") . " " . $type . " ( " . $this->_ip_client . " ) " . $message . "\n", FILE_APPEND);
    }

    /**
     * Ã‰crit un message de type "info" dans le log
     * 
     * @param string $message message à écrire dans le log
     */
    public function info($message) {
        $this->write_log('[Info]', $message);
    }

    /**
     * Ã‰crit un message de type "warning" dans le log
     * 
     * @param string $message message à écrire dans le log
     */
    public function warning($message) {
        $this->write_log('[Warning]', $message);
    }

    /**
     * Ã‰crit un message de type "sévère" dans le log
     * 
     * @param string $message message à écrire dans le log
     */
    public function severe($message) {
        $this->write_log('[Severe]', $message);
    }

}
