<?php

/**
 * Cette classe permet l'envois de SSE (Server-Sent Events)
 * Optimisé pour http/2 et supperieur
 * à utiliser en JS avec new EventSource("./services/index.php?service=sse");
 */
class sse_sender extends singleton {

    /**
     * Cette classe permet l'envois de SSE (Server-Sent Events)
     * Optimisé pour http/2 et supperieur
     * à utiliser en JS avec new EventSource("./services/index.php?service=sse");
     */
    public function __construct() {
        entity_generator::generate([
            "sse_event" => [
                ["id", "int", true],
                ["mt", "int", false],
                ["event", "string", false],
                ["data", "string", false],
                ["user", "int", false],
            ]
        ]);
        if (!file_exists($sfile = "./services/sse.service.php")) {
            file_put_contents($sfile, file_get_contents(__DIR__ . "/sse_tpl/sse"));
        }
    }

    /**
     * Envois un nouvel événement SSE
     * @param string $event nom de l'événement
     * @param string|array $data données de l'événement (chaine de caractère ou tableau)
     * @param int $user Id de l'utilisateur concerné (0 = tout les utilisateurs)
     */
    public function send($event, $data, $user = 0) {
        sse_event::ajout(microtime(true), $event, (is_array($data) ? json_encode($data) : $data), $user);
    }

}
