<?php

/**
 * Cette classe parmet de lancer des requettes au websocket depuis le projet
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class websocket_request {

    /**
     * Client Websocket
     * @var websocket_client Client Websocket
     */
    private $_client;

    /**
     * Cette classe parmet de lancer des requettes au websocket depuis le projet
     * 
     * @param string $host IP du serveur
     * @param int $port Port du serveur
     */
    public function __construct($host = "127.0.0.1", $port = 9000) {
        socket_connect($socket = socket_create(AF_INET, SOCK_STREAM, 0), $host, $port);
        $this->_client = new websocket_client($socket);
    }

    /**
     * Envoi un message au serveur websocket
     * @param string $message Message
     */
    public function send($message) {
        $this->_client->write($message);
    }

    /**
     * Envoi un message au serveur websocket et attend une réponse
     * @param string $message Message
     * @param int $kb Nombre max de KB attendu depuis le serveur
     * @return string Réponse du serveur websocket
     */
    public function request($message, $kb = 1) {
        $this->send($message);
        return socket_read($this->_client->get_socket(), $kb * (1024 ** 2));
    }

    /**
     * Ferme la connexion
     */
    public function close() {
        $this->_client->close();
        $this->_client = false;
    }

    /**
     * Ferme la connexion si ne n'est pas fait
     */
    public function __destruct() {
        if ($this->_client) {
            $this->close();
        }
    }

}
