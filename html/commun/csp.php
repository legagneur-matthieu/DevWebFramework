<?php

/**
 * Description of csp
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class csp_report {

    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (
                strpos($contentType, 'application/csp-report') === false &&
                strpos($contentType, 'application/json') === false &&
                strpos($contentType, 'application/reports+json') === false
        ) {
            http_response_code(415); // Unsupported Media Type
            exit;
        }
        if (isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > 10240) {
            http_response_code(413); // Payload Too Large
            exit;
        }
        $json = file_get_contents('php://input');
        if (empty($json)) {
            http_response_code(400);
            exit;
        }
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            exit;
        }
        session_start();
        if (!isset($_SESSION["csp"])) {
            $_SESSION["csp"] = [
                "title" => "Unknow",
                "prefix" => "Unknow"
            ];
        }
        $date = date("Y-m-d");
        $dateH = date("Y-m-d H:i:s");
        $title = $_SESSION["csp"]["title"];
        $prefix = $_SESSION["csp"]["prefix"];
        $userID = (isset($_SESSION[$prefix . "_auth"]) and $_SESSION[$prefix . "_auth"]) ? $_SESSION[$prefix . "_user"] : 0;
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $report = "{$dateH} | User {$userID} ({$_SERVER['REMOTE_ADDR']})\n{$json}\n\n";
        file_put_contents(
                __DIR__ . "/../../dwf/log/csp-{$title}-{$date}.log",
                $report,
                FILE_APPEND
        );

        http_response_code(204);
    }
}

new csp_report();
