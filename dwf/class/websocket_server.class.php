<?php

/**
 * Cette classe permet de lancer un websocket (serveur)
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class websocket_server {

    /**
     * Socket du serveur
     * @var Socket Socket du serveur
     */
    public static $server_socket = false;

    /**
     * Cette classe permet de lancer un websocket (serveur)
     * 
     * @param int $port Port du serveur (9000 par defaut)
     * @param int $host Host du serveur (0.0.0.0 par defaut)
     */
    public function __construct($port = 9000, $host = "0.0.0.0") {
        if (!@socket_connect($tmpsocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP), $host, $port)) {
            $this->start($port, $host);
        } else {
            cli::write("WS Server alredy online !\n");
        }
    }

    /**
     * Lance le serveur
     * 
     * @param int $port Port du serveur (9000 par defaut)
     * @param int $host Host du serveur (0.0.0.0 par defaut)
     */
    private function start($port = 9000, $host = "0.0.0.0") {
        self::$server_socket = new websocket_client(socket_create(AF_INET, SOCK_STREAM, SOL_TCP));
        self::$server_socket->set_user("server");
        socket_set_option(self::$server_socket->get_socket(), SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind(self::$server_socket->get_socket(), $host, $port);
        socket_listen(self::$server_socket->get_socket());
        cli::write(date("Y-m-d H:i:s") . " -> WS Server online !");
        while (true) {
            $socketlist = websocket_client::get_sockets_list();
            socket_select($socketlist, $null, $null, 0, 10);
            if (in_array(self::$server_socket->get_socket(), $socketlist)) {
                $client = new websocket_client(socket_accept(self::$server_socket->get_socket()));
                $message = socket_read($client->get_socket(), 1 * (1024 ** 2));
                if (strpos($message, "Sec-WebSocket-Key:")) {
                    $res = "HTTP/1.1 101 Switching Protocols\r\n" .
                            "Upgrade: websocket\r\n" .
                            "Connection: Upgrade\r\n" .
                            "Sec-WebSocket-Accept: " .
                            base64_encode(sha1(trim(explode("\n", explode("Sec-WebSocket-Key: ", $message)[1])[0]) . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true)) .
                            "\r\n\r\n";
                    $client->write($res);
                    $client->set_isBrowser(true);
                    self::log($client, "Connected.");
                } else {
                    self::log($client, "Connected.");
                    $this->exec($client, $message);
                }
                unset($socketlist[array_search(self::$server_socket->get_socket(), $socketlist)]);
            }
            foreach ($socketlist as $socket) {
                $client = websocket_client::getClientFromSocket($socket);
                if ($client->get_isBrowser()) {
                    if (strlen(socket_recv($client->get_socket(), $message, 1 * (1024 ** 2), 0))) {
                        $message = self::unseal($message);
                        $this->exec($client, $message);
                        continue;
                    }
                    if (@socket_read($client->get_socket(), 1024, PHP_NORMAL_READ) === false) {
                        self::log($client, "Disconnected.");
                        $client->close();
                    }
                } else {
                    if (strlen($message = @socket_read($client->get_socket(), 1 * (1024 ** 2)))) {
                        $this->exec($client, $message);
                    } else {
                        self::log($client, "Disconnected.");
                        $client->close();
                    }
                }
            }
        }
        self::$server_socket->close();
    }

    /**
     * Execute la requete d'un client
     * 
     * @param websocket_client $client Le client
     * @param string $message La requete.
     */
    private function exec($client, $message) {
        if (strlen($message)) {
            $message = json_decode($message, true);
            if (isset($message["action"])) {
                $action = strtr($message["action"], ["." => "", "/" => "", "\\" => ""]);
                if ($action == "auth") {
                    if (isset($message["token"]) and!empty(trim($message["token"]))) {
                        $this->authentificate($client, $message["token"]);
                    } else {
                        self::log($client, "Auth fail : Empty token");
                        $client->write('{"auth":false,"message":"Empty token"}');
                    }
                } else {
                    if (file_exists("./{$action}.ws.php")) {
                        self::log($client, $message["action"]);
                        new $action($client, $message);
                    } else {
                        $error = "Action not found : {$action}";
                        self::log($client, $error);
                        $client->write("{\"error\":\"{$error}\"}");
                    }
                }
            } else {
                $res = json_encode(["error" => "No action sended !"]);
                $client->write($res);
            }
        } else {
            self::log($client, "Disconnected.");
            $client->close();
        }
    }

    /**
     * Affiche un log concernant un client dans le terminal (pour debug)
     *
     * @param websocket_client $client Client concerné
     * @param string $logtxt le texte du log
     */
    public static function log($client, $logtxt) {
        cli::write(date("Y-m-d H:i:s") . " -> " . ($client->get_isBrowser() ? "Browser" : "CLI") . " #{$client->get_id()} [{$client->get_ip()}] (User {$client->get_user()}) : {$logtxt}");
    }

    /**
     * Déscelle le message provenant d'un client web
     *
     * @param string $message Message scellé
     * @return string Message déscellé
     */
    public static function unseal($message) {
        if (isset($message[1])) {
            $length = ord($message[1]) & 127;
            if ($length == 126) {
                $masks = substr($message, 4, 4);
                $data = substr($message, 8);
            } elseif ($length == 127) {
                $masks = substr($message, 10, 4);
                $data = substr($message, 14);
            } else {
                $masks = substr($message, 2, 4);
                $data = substr($message, 6);
            }
            $message = "";
            for ($i = 0; $i < strlen($data); ++$i) {
                $message .= $data[$i] ^ $masks[$i % 4];
            }
            return $message;
        }
        return false;
    }

    /**
     * Scelle un message a destination d'un client web
     * 
     * @param string $message Message a sceller
     * @return string Message scellé
     */
    public static function seal($message) {
        $b1 = 0x80 | (0x1 & 0x0f);
        $length = strlen($message);
        if ($length <= 125)
            $header = pack('CC', $b1, $length);
        elseif ($length > 125 && $length < 65536)
            $header = pack('CCn', $b1, 126, $length);
        elseif ($length >= 65536)
            $header = pack('CCNN', $b1, 127, $length);
        return $header . $message;
    }

    /**
     * Retourne un token pour authentifier l'utilisateur sur les websocket
     * 
     * @return string Token
     */
    public static function auth() {
        if (session::get_auth()) {
            entity_generator::generate([
                "ws_token" => [
                    ["id", "int", true],
                    ["user", "user", false],
                    ["token", "string", false]
                ]
            ]);
            $col = ws_token::get_collection("user=" . session::get_user_id());
            if (count($col) == 0) {
                ws_token::ajout(session::get_user_id(), "");
                $col = ws_token::get_collection("user=" . session::get_user_id());
            }
            $user = $col[0];
            $rand = rand();
            $token = application::hash(uniqid("{$user->get_id()}{$rand}{$user->get_user()->get_id()}_"));
            $user->set_token($token);
            return $token;
        }
        return "";
    }

    /**
     * 
     * @param websocket_client $client
     * @param string $token
     */
    private function authentificate($client, $token) {
        $ws_token = ws_token::get_collection("token='" . bdd::p($token) . "'");
        $count = count($ws_token);
        if ($count == 0) {
            self::log($client, "Auth fail : Invalid token");
            $client->write('{"auth":false,"message":"Invalid token"}');
        } elseif ($count == 1) {
            $client->set_user($ws_token[0]->get_user()->get_id());
            $ws_token[0]->set_token("");
            $ws_token[0]->update();
            self::log($client, "Auth Success");
            $client->write('{"auth":true,"message":"OK"}');
        } else {
            self::log($client, "Auth fail : Token conflict");
            $client->write('{"auth":false,"message":"Token conflict"}');
        }
    }

}
