<?php

/**
 * Cette classe permet de gerer les clients connecté au serveur websocket
 * (cf webserver_server.class.php)
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class websocket_client {

    /**
     * Liste des clients connecté
     * @var websocket_client[] Liste des clients connecté
     */
    public static $clients = [];

    /**
     * ID du socket
     * @var int ID du socket
     */
    private $_id;

    /**
     * Socket
     * @var Socket Socket
     */
    private $_socket;

    /**
     * Identifiant utilisateur
     * @var int Identifiant utilisateur
     */
    private $_user = 0;

    /**
     * IP
     * @var string IP
     */
    private $_ip;

    /**
     * le client est un navigateur ou non
     * @var boolean le client est un navigateur ou non
     */
    private $_isBrowser = false;

    /**
     * Cette classe permet de gerer les clients connecté au serveur websocket
     * (cf webserver_server.class.php)
     * 
     * @param Socket $socket
     */
    public function __construct($socket) {
        @socket_getpeername($socket, $ip);
        $this->_id = (is_resource($socket) ? (int) $socket : spl_object_id($socket));
        $this->_socket = $socket;
        $this->_ip = $ip;
        self::$clients[$this->_id] = $this;
    }

    /**
     * Retourne l'ID du socket
     * @return int ID du socket
     */
    public function get_id() {
        return $this->_id;
    }

    /**
     * Retourne le socket
     * @return Socket Socket
     */
    public function get_socket() {
        return $this->_socket;
    }

    /**
     * Retourne l'identifiant utilisateur
     * @return int identifiant utilisateur
     */
    public function get_user() {
        return $this->_user;
    }

    /**
     * Retourne l'IP du client
     * @return string IP
     */
    public function get_ip() {
        return $this->_ip;
    }

    /**
     * Retourne si le client est un navigateur ou non
     * 
     * @return boolean True su le client est un navigateur, sinon false
     */
    public function get_isBrowser() {
        return $this->_isBrowser;
    }

    /**
     * Definit le socket du client
     * @param Socket $_socket
     */
    public function set_socket(Socket $_socket) {
        unset(self::$clients[$this->_id]);
        $this->_id = (is_resource($socket) ? (int) $socket : spl_object_id($socket));
        $this->_socket = $_socket;
        self::$clients[$this->_id] = $this;
    }

    /**
     * Definit l'identifiant utilisateur
     * @param int $user identifiant utilisateur
     */
    public function set_user($user) {
        $this->_user = $user;
    }

    /**
     * Definit l'IP du client
     * @param type $_ip
     */
    public function set_ip($_ip) {
        $this->_ip = $_ip;
    }

    /**
     * Defini si le client est un navigateur ou non (true/false)
     * @param boolean $isWeb
     */
    public function set_isBrowser($isBrowser) {
        $this->_isBrowser = $isBrowser;
    }

    /**
     * Recherche un client
     * 
     * @param string $attr Atribut
     * @param mixed $value Valeur
     * @return boolean|websocket_client le client trouvé (false si non trouvé)
     */
    private static function getClientFrom($attr, $value) {
        foreach (self::$clients as $client) {
            $getter = "get_{$attr}";
            if ($client->$getter() == $value) {
                return $client;
            }
        }
        return false;
    }

    /**
     * Recherche une liste de clients 
     * 
     * @param string $attr Atribut
     * @param mixed $value Valeur
     * @return websocket_client[] Liste de clients trouvé
     */
    private static function getClientsFrom($attr, $value) {
        $clients = [];
        foreach (self::$clients as $client) {
            $getter = "get_{$attr}";
            if ($client->$getter() == $value) {
                $clients[] = $client;
            }
        }
        return $clients;
    }

    /**
     * Retourne le client avec l'ID specifié (false si non trouvé)
     * 
     * @param int $id ID socket
     * @return boolean|websocket_client le client
     */
    public static function getClientFromID($id) {
        return self::getClientFrom("id", $id);
    }

    /**
     * Retourne le client avec le socket specifié (false si non trouvé)
     * 
     * @param Socket $socket
     * @return boolean|websocket_client le client
     */
    public static function getClientFromSocket($socket) {
        return self::getClientFrom("socket", $socket);
    }

    /**
     * Retourne les clients lié a l'identifiant utilisateur specifié 
     * note : un utilisateur peux avoir plusieurs sockets en cours
     * 
     * @param int $user Identifiant utilisateur
     * @return websocket_client[] Les Clients
     */
    public static function getClientsFromUser($user) {
        return self::getClientsFrom("user", $user);
    }

    /**
     * Retourne les clients avec l'ip specifié 
     * note : un utilisateur peux avoir plusieurs sockets en cours avec la meme IP
     * 
     * @param string $ip IP
     * @return websocket_client[] Les Clients
     */
    public static function getClientsFromIP($ip) {
        return self::getClientsFrom("ip", $ip);
    }

    /**
     * Retourne la liste complete des sockets connecté
     * @return type
     */
    public static function get_sockets_list() {
        $array = [];
        foreach (self::$clients as $client) {
            $array[] = $client->get_socket();
        }
        return $array;
    }

    /**
     * Envoie un message au client
     *
     * @param string $message Message
     */
    public function write($message) {
        $message = ($this->get_isBrowser() ? websocket_server::seal($message) : $message);
        @socket_write($this->get_socket(), $message, strlen($message));
    }

    /**
     * Envoie un message a un client
     *
     * @param websocket_client $client Le client destinataire
     * @param string $message Message
     */
    public static function writeTo($client, $message) {
        $message = ($client->get_isBrowser() ? websocket_server::seal($message) : $message);
        @socket_write($client->get_socket(), $message, strlen($message));
    }

    /**
     * Décoonecte le client 
     */
    public function close() {
        @socket_shutdown($this->_socket);
        @socket_close($this->_socket);
        unset(self::$clients[$this->_id]);
    }

    /**
     * Décoonecte un client
     *  
     * @param websocket_client $client Client a déconnecter
     */
    public static function disconnect($client) {
        @socket_shutdown($client->_socket);
        @socket_close($client->_socket);
        unset(self::$clients[$client->_id]);
    }

}
