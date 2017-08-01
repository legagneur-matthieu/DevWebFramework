<?php

class config {

    public static $_prefix = "";

}

/**
 * Service permettant l'envoi et la réception des clés publiques du serveur et de l'utilisateur
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class rsa {

    /**
     * Service permettant l'envoi et la réception des clés publiques du serveur et de l'utilisateur
     */
    public function __construct() {
        if (isset($_POST["prefix"])) {
            config::$_prefix = $_POST["prefix"];
            session::start(false);
            switch ($_POST["action"]) {
                case "get_ssl_public_key":
                    $this->get_ssl_public_key();
                    break;
                case "set_client_ssl_public_key":
                    $this->set_client_ssl_public_key();
                    break;
                default:
                    echo json_encode(array("error" => "undefined action"));
                    break;
            }
        }
    }

    /**
     * Affiche la clé publique du serveur
     */
    private function get_ssl_public_key() {
        if (session::get_val("ssl_public_key")) {
            echo json_encode(array("ssl_public_key" => session::get_val("ssl_public_key")));
        } else {
            echo json_encode(array("error" => "ssl_public_key not found"));
        }
    }

    /**
     * Receptionne la clé publique de l'utilisateur
     * 
     */
    private function set_client_ssl_public_key() {
        if (stristr($_POST["public_key"], "-----BEGIN PUBLIC KEY-----") and stristr($_POST["public_key"], "-----END PUBLIC KEY-----")) {
            session::set_val("client_ssl_public_key", $_POST["public_key"]);
        } else {
            echo json_encode(array("error" => "public_key invalid"));
        }
    }

}
